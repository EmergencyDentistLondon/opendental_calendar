<?php

class FelhasznalokController extends Zend_Controller_Action
{
	public function indexAction(){
		$table = new UsersTable();
		
		$this->view->users = $table->fetchAll();
	}
	
	public function ujAction(){
		$request = $this->getRequest();
		
		if($request->isPost()){
			$table = new UsersTable();
			
			$data = array(
				'username' => $request->getParam('username'),
				'password' => md5($request->getParam('password')),
				'role' => $request->getParam('role'),
			);
			
			$table->insert($data);
			
			$this->_redirect('/felhasznalok');
		}
	}
	
	public function torolAction(){
		$request = $this->getRequest();
		$table = new UsersTable();
		
		$table->delete('id = ?', $request->getParam('id'));
		
		$this->_redirect('/felhasznalok');
	}
}

?>
