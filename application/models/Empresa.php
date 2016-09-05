<?php

/**
 * Empresa
 *
 * 
 */
class Application_Model_Empresa
{
    /**
     * @var integer
     *
     * 
     */
    private $id;

    /**
     * @var string
     *
     * 
     */
    private $cnpj;

    /**
     * @var string
     *
     * 
     */
    private $razaoSocial;

    /**
     * @var string
     *
     *
     */
    private $nomeFantasia;

    /**
     * @var array
     *
     * 
     */
    private $socios;
	
	/** 
     * Constructor 
     */ 
    public function __construct($empresa,$socios = null) 
    {         
		if (isset($empresa['id'])) {
			$this->setId($empresa['id']);			
		}
		$this->setCnpj($empresa['cnpj']);
		$this->setRazaoSocial($empresa['razao_social']);
		$this->setNomeFantasia($empresa['nome_fantasia']);
		$this->setSocios($socios);
	
    }
    
	/**
     * Set id
     *
     * @param $id
     * @return Empresa
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cnpj
     *
     * @param string $cnpj
     * @return Empresa
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get cnpj
     *
     * @return string 
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Set razaoSocial
     *
     * @param string $razaoSocial
     * @return Empresa
     */
    public function setRazaoSocial($razaoSocial)
    {
        $this->razaoSocial = $razaoSocial;

        return $this;
    }

    /**
     * Get razaoSocial
     *
     * @return string 
     */
    public function getRazaoSocial()
    {
        return $this->razaoSocial;
    }

    /**
     * Set nomeFantasia
     *
     * @param string $nomeFantasia
     * @return Empresa
     */
    public function setNomeFantasia($nomeFantasia)
    {
        $this->nomeFantasia = $nomeFantasia;

        return $this;
    }

    /**
     * Get nomeFantasia
     *
     * @return string 
     */
    public function getNomeFantasia()
    {
        return $this->nomeFantasia;
    }

    /**
     * Set socios
     *
	 * @param array $socios
     * @return array
     */
    public function setSocios($socios)
    {
        return $this->socios = $socios;
    }
    /**
     * Set socios por id
     *
	 * @param array $socios
     * @return array
     */
    public function setSociosPorId($socios)
    {
		$socDb = new Application_Model_Db_SocioDb();
		$arrayAux = array();
		foreach ($socios as $k=>$v) {
			$arrayAux[] = $socDb->getPorId($v);
		}
        return $this->socios = $arrayAux;
    }

    /**
     * Get socios
     *
     * @return Array
     */
    public function getSocios()
    {
        return $this->socios;
    }
	
    /**
     * Get socios id
     *
     * @return Array
     */
    public function getSociosId()
    {
		$arrayAux = array();
		if (count($this->socios)>0) {
			foreach ($this->socios as $soc) {
				$arrayAux[] = $soc->getId();
			}
		}
        return $arrayAux;
    }
}
