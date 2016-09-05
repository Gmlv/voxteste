<?php

class Application_Form_BuscaSocioForm extends Zend_Form {

	public function __construct() {

        $cpf = new Zend_Form_Element_Text('cpf');
        $cpf ->setAttrib('placeholder','CPF')
			  ->setAttrib('class','form-control')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addValidator('NotEmpty')
			  ->addValidator('StringLength', false, array(1, 20));
			  		


        $this->addElements(array($cpf));
	
	}
}