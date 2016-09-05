<?php

class EmpresaController extends Zend_Controller_Action
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
		$empDb = new Application_Model_Db_EmpresaDb();
		$this->view->empresas = $empDb->getTodos();

    }
	
	public function novoAction()
	{
		$socDb = new Application_Model_Db_SocioDb();
		$socios = $socDb->getTodos();
		
		$form = new Application_Form_CadastroEmpresaForm($socios);		
		
		if ($this->_request->isPost()) { 
			if (!$form->isValid($_POST)) {				
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique os campos digitados"));
				$this->redirector->gotoRoute(array('controller'=>'empresa','action'=>'novo'),'default',true);				
			}
			
			try {
				
				$empDb = new Application_Model_Db_EmpresaDb();			
				$dados = $form->getValues();
				$empresa = new Application_Model_Empresa($dados);
				if (count($dados['socios'])>0) {
					$empresa->setSociosPorId($dados['socios']);
				}	
				$empDb->setEmpresa($empresa);	
				$retorno = $empDb->inserir();
				
				if (!$retorno) {
					$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Ocorreu um erro"));
					$this->redirector->gotoRoute(array('controller'=>'empresa','action'=>'novo'),'default',true);						
				}
			
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("success","Cadastrado coms sucesso"));
				$this->redirector->gotoRoute(array('controller'=>'empresa','action'=>'novo'),'default',true);				
			} catch(Exception $e) {
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Ocorreu um erro"));
				$this->redirector->gotoRoute(array('controller'=>'empresa','action'=>'novo'),'default',true);				
			}
			
		}
		
		$this->view->form = $form;
	}
	
	public function editarAction() 
	{
		try{
			$id = $this->_getParam('id');
			$empDb = new Application_Model_Db_EmpresaDb();			
			$empresa = $empDb->getPorId($id);

			$socDb = new Application_Model_Db_SocioDb();
			$socios = $socDb->getTodos();
			
			$form = new Application_Form_CadastroEmpresaForm($socios);	
			$arrayPopulate = array();
			$arrayPopulate['cnpj'] = $empresa->getCnpj() ;
			$arrayPopulate['nome_fantasia'] = $empresa->getNomeFantasia() ;
			$arrayPopulate['razao_social'] = $empresa->getRazaoSocial() ;
			$form->populate($arrayPopulate);
			$form->getElement('socios')->setValue($empresa->getSociosId());
			
			if($this->_request->isPost()){
				if (!$form->isValid($_POST)) {				
					$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique os campos digitados"));
					$this->redirector->gotoRoute(array('id'=>$id),'editar-empresa',true);				
				}
				
				$dados = $form->getValues();
				$empresa = new Application_Model_Empresa($dados);
				$empresa->setId($id);
				if (count($dados['socios'])>0) {
					$empresa->setSociosPorId($dados['socios']);
				}	
				
				$empDb->setEmpresa($empresa);	
				$retorno = $empDb->editar();
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("success","Editado com sucesso"));
				$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true);
			}
			
			$this->view->form = $form;
			
		}catch(Application_Model_Exceptions_NotFoundException $e){
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Empresa não encontrada"));
			$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true);	
		}
		
	}
	
	public function buscarAction() 
	{
		$form = new Application_Form_BuscaEmpresaForm();
		
		if($this->_request->isPost()){
			if (!$form->isValid($_POST)) {				
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique os campos digitados"));
				$this->redirector->gotoRoute(array('controller'=>"empresa",'action'=>'buscar'),'default',true);				
			}
			
			$dados = $form->getValues();
			$empDb = new Application_Model_Db_EmpresaDb();			
			$empresas = $empDb->getPorCnpj($dados['cnpj']);

			$this->view->empresas = $empresas;		
		}
		$this->view->form = $form;
		
	}	
	
	public function visualizarAction() 
	{
		try{
			$id = $this->_getParam('id');
			$empDb = new Application_Model_Db_EmpresaDb();			
			$empresa = $empDb->getPorId($id);

			$this->view->empresa = $empresa;		
		}catch(Application_Model_Exceptions_NotFoundException $e){
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Empresa não encontrada"));
			$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true);	
		}
		
	}
	public function removerAction()
	{
		try{
			$empDb = new Application_Model_Db_EmpresaDb();	
			$empDb->remover($this->_getParam("id"));
			
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("success","Removido com sucesso"));
			$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true);			
			
		}catch (Exception $e) {
			$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Não foi possivél remover."));
			$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true);	
		}
	}

}

