<?php

//require_once ('Zend/Form.php');

class Application_Form_CadastroSocioForm extends Zend_Form {

	public function __construct($empresas = null) {

        $nome = new Zend_Form_Element_Text('nome');
        $nome->setAttrib('placeholder','Nome')
			 ->setRequired(true)
			 ->setAttrib('class','form-control')
			 ->addFilter('StripTags')
			 ->addValidator('NotEmpty')
			 ->addValidator('StringLength', false, array(0, 255));

        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('placeholder','E-mail')
				->setRequired(true)
				->setAttrib('class','form-control')
				->addFilter('StripTags')
				->addValidator('NotEmpty')
				->addValidator('StringLength', false, array(3, 255));

        $cpf = new Zend_Form_Element_Text('cpf');
        $cpf ->setAttrib('placeholder','CPF')
			  ->setAttrib('class','form-control')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addValidator('NotEmpty')
			  ->addValidator('StringLength', false, array(5, 20));
			  		
		$arrayEmpresas = array();
		foreach ($empresas as $soc) {
			$arrayEmpresas[$soc->getId()] = $soc->getNomeFantasia();
		}
			  
		$empresasForm = new Zend_Form_Element_Multiselect('empresas');
		$empresasForm->setMultiOptions($arrayEmpresas)->setAttrib('class','form-control');

        $this->addElements(array($nome,$email, $cpf,$empresasForm));
	
	}
}