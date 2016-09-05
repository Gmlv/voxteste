  <?php

/**
 * UsuarioDb
 *
 * 
 */
class Application_Model_Db_UsuarioDb extends Zend_Db_Table_Abstract
{
	protected $_name = 'usuario';
	protected $db = null;

	
	public function __construct() {
		parent::__construct();
        $this->db = Zend_Db_Table::getDefaultAdapter ();
    }


	public function login($login, $senha) {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Restrito'));


        $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table::getDefaultAdapter(), //adaptador do banco de dados
                'usuario', // tabela
                'login', // login
                'senha', //senha
                'SHA1(?)' //regra da senha
        );
        $authAdapter->setIdentity($login)->setCredential($senha);        
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $usuario = $authAdapter->getResultRowObject();

            $auth->getStorage()->write($usuario);            
			return true;
        } else {
            return false;            
        }
    }

}

