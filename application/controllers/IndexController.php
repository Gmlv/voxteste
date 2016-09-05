<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout("main");
		$this->view->msg = $this->_helper->getHelper('FlashMessenger')->getMessages();
		$this->redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');	
		
		$this->_auth = Zend_Auth::getInstance();
        $this->_auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Restrito'));
		$this->view->auth = $this->_auth;	
        if ($this->_auth->hasIdentity()) {            
			$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true); 
        }		
    }

    public function indexAction()
    {
		$form = new Application_Form_LoginForm();
		if($this->_request->isPost()){
			if (!$form->isValid($_POST)) {
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Verifique as informações digitadas"));
				$this->redirector->gotoRoute(array(),'default',true);	
			}
			$dados = $form->getValues();
			$usuarioDb = new Application_Model_Db_UsuarioDb();
			$retorno = $usuarioDb->login($dados['login'],$dados['senha']);
			
			if (!$retorno) {
				$this->_helper->getHelper('FlashMessenger')->addMessage(array("danger","Login e/ou senha incorretos"));
				$this->redirector->gotoRoute(array(),'default',true);	
			}
			$this->redirector->gotoRoute(array('controller'=>'empresa'),'default',true);	
		}
		
		
		$this->view->form = $form;
    }


}

