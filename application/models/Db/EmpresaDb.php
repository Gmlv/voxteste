<?php

/**
 * EmpresaDb
 *
 * 
 */
class Application_Model_Db_EmpresaDb extends Zend_Db_Table_Abstract
{
	protected $_name = 'empresa';
	protected $db = null;
    
	/**
     * @var Empresa
     *
     * 
     */
    private $empresa;
	
	public function __construct() {
		parent::__construct();
        $this->db = Zend_Db_Table::getDefaultAdapter ();
    }

    /**
     * Get por id
     *
     * @return Empresa 
     */
    public function getPorId($id)
    {
		$row = $this->fetchRow(array('id=?'=>$id));
		if ($row) {
			$socioDb = new Application_Model_Db_SocioDb();
			$socios = $socioDb->getPorEmpresaId($row['id']);
			$this->empresa = new Application_Model_Empresa($row,$socios);
		} else {
			throw new Application_Model_Exceptions_NotFoundException('Não encontrado');
		}
        return $this->empresa;
    }

    /**
     * Get todos
     *
     * @return array 
     */
    public function getTodos()
    {
		$all = $this->fetchAll();
		$socioDb = new Application_Model_Db_SocioDb();
		$arrayEmpresas = array();
		foreach ($all as $row) {
			$socios = $socioDb->getPorEmpresaId($row['id']);
			$arrayEmpresas[$row['id']] = new Application_Model_Empresa($row,$socios);
		}
		
        return $arrayEmpresas;
    }
    /**
     * Get pelo id do socio
     *
     * @return array 
     */
    public function getPorSocioId($id)
    {
		$sql = $this->db->select()->from('socio_empresa')->join('empresa','empresa.id = socio_empresa.empresa_id')->where('socio_id = ?',$id);
		
		$all = $this->db->fetchAll($sql);
		$arrayEmpresas = array();		
		foreach ($all as $row) {			
			$arrayEmpresas[] = new Application_Model_Empresa($row);
		}
		
        return $arrayEmpresas;
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
			$row->cnpj = $this->empresa->getCnpj();
			$row->razao_social = $this->empresa->getRazaoSocial();
			$row->nome_fantasia = $this->empresa->getNomeFantasia();
			$row->save();
			
			if ($this->empresa->getSocios() != NULL) {
				$socioEmpDb = new Application_Model_Db_SocioEmpresaDb();			
				foreach ($this->empresa->getSocios() as $soc) {
					$socioEmpDb->inserir($soc->getId(),$row->id);
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
			$row = $this->fetchRow(array('id = ?'=>$this->empresa->getId()));
			$row->cnpj = $this->empresa->getCnpj();
			$row->razao_social = $this->empresa->getRazaoSocial();
			$row->nome_fantasia = $this->empresa->getNomeFantasia();
			$row->save();
			
			$this->db->delete('socio_empresa','empresa_id ='.$row['id']);
			if ($this->empresa->getSocios() != NULL) {
				$socioEmpDb = new Application_Model_Db_SocioEmpresaDb();			
				foreach ($this->empresa->getSocios() as $soc) {
					$socioEmpDb->inserir($soc->getId(), $row->id);
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
     * Get por cnpj
     *
     * @return array 
     */
    public function getPorCnpj($cnpj)
    {
		$all = $this->fetchAll(array('cnpj LIKE ?'=>'%'.$cnpj.'%'));
		$arrayEmpresas = array();
		foreach ($all as $row) {
			$arrayEmpresas[$row['id']] = new Application_Model_Empresa($row);
		}
		
        return $arrayEmpresas;
    }
	
    /**
     * remover empresa
     *
     * @param int $empresaId
     * 
     */
    public function remover($empresaId)
    {
		$this->db->delete('socio_empresa','empresa_id ='.$empresaId);
		$this->delete('id = '.$empresaId);        
    }
    /**
     * Set empresa
     *
     * @param Empresa $empresa
     * @return EmpresaDb
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }


}
