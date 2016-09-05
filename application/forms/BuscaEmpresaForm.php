<?php

class Application_Form_BuscaEmpresaForm extends Zend_Form {

	public function __construct() {

        $cnpj = new Zend_Form_Element_Text('cnpj');
        $cnpj ->setAttrib('placeholder','Cnpj')
			  ->setAttrib('class','form-control')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addValidator('NotEmpty')
			  ->addValidator('StringLength', false, array(1, 45));
			  		


        $this->addElements(array($cnpj));
	
	}
}