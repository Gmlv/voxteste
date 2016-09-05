<?php

class SocioController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Restrito'));
		$this->view->auth = $this->_auth;
		
		$this->_helper->layout->setLayout("main");
		$this->view->msg = $this->_helper->getHelper('FlashMessenger')->getMessages();
		$this->redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');

        if (!$this->_auth->hasIdentity()) {
            $this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Você precisa estar logado para acessar esta página"));
			$this->redirector->gotoRoute(array(),'default',true);
            
        }
    }

    public function indexAction()
    {
		$socDb = new Application_Model_Db_SocioDb();
		$this->view->socios = $socDb->getTodos();
    }
	
	public function novoAction()
	{
		$empDb = new Application_Model_Db_EmpresaDb();
		$empresas = $empDb->getTodos();
		
		$form = new Application_Form_CadastroSocioForm($empresas);		
		
		if ($this->_request->isPost()) { 
			if (!$form->isValid($_POST)) {				
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique os campos digitados"));
				$this->redirector->gotoRoute(array('controller'=>'socio','action'=>'novo'),'default',true);				
			}
			
			try {
				
				$socDb = new Application_Model_Db_SocioDb();			
				$dados = $form->getValues();
				$socio = new Application_Model_Socio($dados);
				if (count($dados['empresas'])>0) {
					$socio->setEmpresasPorId($dados['empresas']);
				}	
				$socDb->setSocio($socio);	
				$retorno = $socDb->inserir();
				
				if (!$retorno) {
					$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Ocorreu um erro"));
					$this->redirector->gotoRoute(array('controller'=>'socio','action'=>'novo'),'default',true);						
				}
			
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("success","Cadastrado coms sucesso"));
				$this->redirector->gotoRoute(array('controller'=>'socio','action'=>'novo'),'default',true);				
			} catch(Exception $e) {
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Ocorreu um erro"));
				$this->redirector->gotoRoute(array('controller'=>'socio','action'=>'novo'),'default',true);				
			}
			
		}
		
		$this->view->form = $form;
	}
	
	public function editarAction() 
	{
		try{
			$id = $this->_getParam('id');
			$socDb = new Application_Model_Db_SocioDb();			
			$socio = $socDb->getPorId($id);

			$empDb = new Application_Model_Db_EmpresaDb();
			$empresas = $empDb->getTodos();
			
			$form = new Application_Form_CadastroSocioForm($empresas);	
			$arrayPopulate = array();
			$arrayPopulate['cpf'] = $socio->getCpf() ;
			$arrayPopulate['nome'] = $socio->getNome() ;
			$arrayPopulate['email'] = $socio->getEmail() ;
			$form->populate($arrayPopulate);
			$form->getElement('empresas')->setValue($socio->getEmpresasId());
			
			if($this->_request->isPost()){
				if (!$form->isValid($_POST)) {				
					$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique os campos digitados"));
					$this->redirector->gotoRoute(array('id'=>$id),'editar-socio',true);				
				}
				
				$dados = $form->getValues();
				$socio = new Application_Model_Socio($dados);
				$socio->setId($id);
				if (count($dados['empresas'])>0) {
					$socio->setEmpresasPorId($dados['empresas']);
				}	
				
				$socDb->setSocio($socio);	
				$retorno = $socDb->editar();
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("success","Editado com sucesso"));
				$this->redirector->gotoRoute(array('controller'=>'socio'),'default',true);
			}
			
			$this->view->form = $form;
			
		}catch(Application_Model_Exceptions_NotFoundException $e){
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Socio não encontrado"));
			$this->redirector->gotoRoute(array('controller'=>'socio'),'default',true);	
		}
		
	}

	public function visualizarAction() 
	{
		try{
			$id = $this->_getParam('id');
			$socDb = new Application_Model_Db_SocioDb();			
			$socio = $socDb->getPorId($id);

			$this->view->socio = $socio;		
		}catch(Application_Model_Exceptions_NotFoundException $e){
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Socio não encontrado"));
			$this->redirector->gotoRoute(array('controller'=>'socio'),'default',true);	
		}
		
	}
	
	public function buscarAction() 
	{
		$form = new Application_Form_BuscaSocioForm();
		
		if($this->_request->isPost()){
			if (!$form->isValid($_POST)) {				
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique os campos digitados"));
				$this->redirector->gotoRoute(array('controller'=>"socio",'action'=>'buscar'),'default',true);				
			}
			
			$dados = $form->getValues();
			$socDb = new Application_Model_Db_SocioDb();			
			$socios = $socDb->getPorCpf($dados['cpf']);

			$this->view->socios = $socios;		
		}
		$this->view->form = $form;
		
	}
	
	public function removerAction()
	{
		try{
			$socDb = new Application_Model_Db_SocioDb();	
			$socDb->remover($this->_getParam("id"));
			
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("success","Removido com sucesso"));
			$this->redirector->gotoRoute(array('controller'=>'socio'),'default',true);			
			
		}catch (Exception $e) {
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Não foi possivél remover."));
			$this->redirector->gotoRoute(array('controller'=>'socio'),'default',true);	
		}
	}

}

