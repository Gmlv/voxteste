<?php

class Application_Form_LoginForm extends Zend_Form {

	public function __construct() {

        $login = new Zend_Form_Element_Text('login');
        $login ->setAttrib('placeholder','Login')
			  ->setAttrib('class','form-control')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addValidator('NotEmpty')
			  ->addValidator('StringLength', false, array(6, 20));
		
		$senha = new Zend_Form_Element_Password('senha');
        $senha->setAttrib('placeholder', 'Senha')
		->setAttrib('autocomplete', 'off')
		->setAttrib('class', 'form-control')		
		->setRequired(true)
		->addFilter('StripTags')
		->addValidator('NotEmpty')
		->addValidator('StringLength', false, array(6, 15))
		->setAttrib('maxlength', 15);

        $this->addElements(array($login,$senha));
	
	}
}