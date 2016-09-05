<?php

/**
 * SocioEmpresaDb
 *
 * 
 */
class Application_Model_Db_SocioEmpresaDb extends Zend_Db_Table_Abstract
{
	protected $_name = 'socio_empresa';
	protected $db = null;
    
	public function __construct() {
		parent::__construct();
        $this->db = Zend_Db_Table::getDefaultAdapter ();
    }

    /**
     * Inserir 
     *
     * @param int $socioId
	 * @param int $empresaId
     */
    public function inserir($socioId,$empresaId)
    {
		$row = $this->createRow();
		$row->socio_id = $socioId;
		$row->empresa_id = $empresaId;		
		$row->save();
		
		return $row;
		
    }


}
