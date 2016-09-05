<?php

/**
 * Socio
 *
 *
 */
class Application_Model_Socio
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
    private $cpf;

    /**
     * @var string
     *
     * 
     */
    private $nome;

    /**
     * @var string
     *
     * 
     */
    private $email;

    /**
     * @var array
     *
     * 
     */
    private $empresas;

    /**
     * Set id
     *
     * @param $id
     * @return Socio
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
     * Set cpf
     *
     * @param string $cpf
     * @return Socio
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Socio
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Socio
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct($dados,$empresas = null)
    {
		if (isset($dados['id'])) {
			$this->setId($dados['id']);			
		}    
		if (isset($dados['cpf'])) {
			$this->setCpf($dados['cpf']);			
		}
		if (isset($dados['email'])) {
			$this->setEmail($dados['email']);			
		}    
		if (isset($dados['nome'])) {
			$this->setNome($dados['nome']);			
		}      
		$this->setEmpresas($empresas);		

    }

    /**
     * Get empresas
     *
     * @return Array
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }

    /**
     * Get empresas id
     *
     * @return Array
     */
    public function getEmpresasId()
    {
		$arrayAux = array();
		if (count($this->empresas)>0) {
			foreach ($this->empresas as $emp) {
				$arrayAux[] = $emp->getId();
			}
		}
        return $arrayAux;
    }
	 
	/**
     * Set empresas
     *
	 * @param array $empresas
     * @return array
     */
    public function setEmpresas($empresas)
    {
        return $this->empresas = $empresas;
    }
    /**
     * Set empresas por id
     *
	 * @param array $empresas
     * @return array
     */
    public function setEmpresasPorId($empresas)
    {
		$empDb = new Application_Model_Db_EmpresaDb();
		$arrayAux = array();
		foreach ($empresas as $k=>$v) {
			$arrayAux[] = $empDb->getPorId($v);
		}
        return $this->empresas = $arrayAux;
    }
	

}
