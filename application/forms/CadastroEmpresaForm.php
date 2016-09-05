<?php

//require_once ('Zend/Form.php');

class Application_Form_CadastroEmpresaForm extends Zend_Form {

	public function __construct($socios = null) {

        $nome_fantasia = new Zend_Form_Element_Text('nome_fantasia');
        $nome_fantasia->setAttrib('placeholder','Nome Fantasia')
			 ->setRequired(true)
			 ->setAttrib('class','form-control')
			 ->addFilter('StripTags')
			 ->addValidator('NotEmpty')
			 ->addValidator('StringLength', false, array(0, 255));

        $razao_social = new Zend_Form_Element_Text('razao_social');
        $razao_social->setAttrib('placeholder','Razão Social')
				->setRequired(true)
				->setAttrib('class','form-control')
				->addFilter('StripTags')
				->addValidator('NotEmpty')
				->addValidator('StringLength', false, array(3, 255));

        $cnpj = new Zend_Form_Element_Text('cnpj');
        $cnpj ->setAttrib('placeholder','Cnpj')
			  ->setAttrib('class','form-control')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addValidator('NotEmpty')
			  ->addValidator('StringLength', false, array(5, 45));
			  		
		$arraySocios = array();
		foreach ($socios as $soc) {
			$arraySocios[$soc->getId()] = $soc->getNome();
		}
			  
		$sociosForm = new Zend_Form_Element_Multiselect('socios');
		$sociosForm->setMultiOptions($arraySocios)->setAttrib('class','form-control');

        $this->addElements(array($nome_fantasia,$razao_social, $cnpj,$sociosForm));
	
	}
}