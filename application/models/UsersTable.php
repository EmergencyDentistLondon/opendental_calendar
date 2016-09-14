<?php

/**
 * DBTable
 *  
 * @author barnabee
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class UsersTable extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'web_users';

	public function __construct(){
		$db = new Zend_Db_Adapter_Pdo_Mysql(array(
			'host'     => 'localhost',
			'username' => '',
			'password' => '',
			'dbname'   => ''
		));
		parent::__construct(array('name' => $this->_name, 'db'=>$db));
	}
	
	public function delete($query, $data){
		$where = $this->getAdapter()->quoteInto($query, $data);
		parent::delete($where);
	}
}
