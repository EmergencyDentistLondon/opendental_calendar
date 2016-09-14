<?php

/**
 * DBTable
 *  
 * @author barnabee
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class DBTable extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'db';

	public function __construct($tableName){
		$this->_name = $tableName;
		parent::__construct(array('name' => $tableName));
	}
	
	public function delete($query, $data){
		$where = $this->getAdapter()->quoteInto($query, $data);
		parent::delete($where);
	}
}
