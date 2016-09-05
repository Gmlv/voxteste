<?php

/**
 * SocioDb
 *
 * 
 */
class Application_Model_Db_SocioDb extends Zend_Db_Table_Abstract
{
	protected $_name = 'socio';
	protected $db = null;
    
	/**
     * @var Socio
     *
     * 
     */
    private $socio;
	
	public function __construct() {
		parent::__construct();
        $this->db = Zend_Db_Table::getDefaultAdapter ();
    }
	
    /**
     * Inserir 
     *
     * 
     */
    public function inserir()
    {
		try{
			$this->db->beginTransaction();
			$row = $this->createRow();
			$row->cpf = $this->socio->getCpf();
			$row->email = $this->socio->getEmail();
			$row->nome = $this->socio->getNome();
			$row->save();
			
			if ($this->socio->getEmpresas() != NULL) {
				$socioEmpDb = new Application_Model_Db_SocioEmpresaDb();			
				foreach ($this->socio->getEmpresas() as $emp) {
					$socioEmpDb->inserir($row->id,$emp->getId());
				}
			}
			$this->db->commit();
			return $row;
			
		}catch(Exception $e ){
			$this->db->rollBack();
			return false;
		}

    }
	
    /**
     * Editar 
     *
     * 
     */
    public function editar()
    {
		try{
			$this->db->beginTransaction();
			$row = $this->fetchRow(array('id = ?'=>$this->socio->getId()));
			$row->cpf = $this->socio->getCpf();
			$row->email = $this->socio->getEmail();
			$row->nome = $this->socio->getNome();
			$row->save();
			
			$this->db->delete('socio_empresa','socio_id ='.$row['id']);
			if ($this->socio->getEmpresas() != NULL) {
				$socioEmpDb = new Application_Model_Db_SocioEmpresaDb();			
				foreach ($this->socio->getEmpresas() as $emp) {
					$socioEmpDb->inserir($row->id,$emp->getId());
				}
			}
			$this->db->commit();
			return $row;
			
		}catch(Exception $e ){
			$this->db->rollBack();
			return false;
		}

    }
    /**
     * remover socio
     *
     * @param int $socioId
     * 
     */
    public function remover($socioId)
    {
		$this->db->delete('socio_empresa','socio_id ='.$socioId);
		$this->delete('id = '.$socioId);        
    }
    /**
     * Get por id
     *
     * @return Socio 
     */
    public function getPorId($id)
    {
		$row = $this->fetchRow(array('id=?'=>$id));
		$empresaDb = new Application_Model_Db_EmpresaDb();
		if($row){
			$empresas = $empresaDb->getPorSocioId($row['id']);
			$this->socio = new Application_Model_Socio($row,$empresas);
		}else{
			throw new Application_Model_Exceptions_NotFoundException('Não encontrado');
		}
        return $this->socio;
    }

    /**
     * Get todos
     *
     * @return array 
     */
    public function getTodos()
    {
		$all = $this->fetchAll();
		$empresaDb = new Application_Model_Db_EmpresaDb();
		$arraySocios = array();
		foreach ($all as $row) {
			$empresas = $empresaDb->getPorSocioId($row['id']);
			$arraySocios[$row['id']] = new Application_Model_Socio($row,$empresas);
		}
		
        return $arraySocios;
    }
    /**
     * Get por cpf
     *
     * @return array 
     */
    public function getPorCpf($cpf)
    {
		$all = $this->fetchAll(array('cpf LIKE ?'=>'%'.$cpf.'%'));
		$empresaDb = new Application_Model_Db_EmpresaDb();
		$arraySocios = array();
		foreach ($all as $row) {
			$empresas = $empresaDb->getPorSocioId($row['id']);
			$arraySocios[$row['id']] = new Application_Model_Socio($row,$empresas);
		}
		
        return $arraySocios;
    }

    /**
     * Get pelo id da empresa
     *
     * @return array 
     */
    public function getPorEmpresaId($id)
    {
		$sql = $this->db->select()->from('socio_empresa')->join('socio','socio.id = socio_empresa.socio_id')->where('empresa_id = ?',$id);
		$all = $this->db->fetchAll($sql);
		
		$arraySocios = array();
		foreach ($all as $row) {			
			$arraySocios[] = new Application_Model_Socio($row);
		}
		
        return $arraySocios;
    }

    /**
     * Set socio
     *
     * @param Socio $socio
     * @return SocioDb
     */
    public function setSocio($socio)
    {
        $this->socio = $socio;

        return $this;
    }

    /**
     * Get socio
     *
     * @return Socio 
     */
    public function getSocio()
    {
        return $this->socio;
    }


}
