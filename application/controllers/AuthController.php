<?php
class AuthController extends Zend_Controller_Action
{
    public function loginAction(){
        $this->view->assign('title', "Jelentkezzen be");
    }
    
    public function logoutAction(){
    	$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function privilegesAction(){
		$layout = Zend_Layout::getMvcInstance();
		$layout->disableLayout();
    	$this->view->assign('title', "Nincs jogosultsága a lap megtekintéséhez");
    }
    
    public function identifyAction(){
    	//$db = Zend_Registry::get('db');
    	$db = new Zend_Db_Adapter_Pdo_Mysql(array(
			'host'     => 'localhost',
			'username' => '',
			'password' => '',
			'dbname'   => ''
		));
		$authAdapter = new Zend_Auth_Adapter_DbTable($db);
		$authAdapter->setTableName('web_users')->setIdentityColumn('username')->setCredentialColumn('password');
		
		$authAdapter->setIdentity($_POST['username']);
		$authAdapter->setCredential(md5($_POST['password']));
		
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($authAdapter);
		
		if ($result->isValid())
		{
			$auth->getStorage()->write($authAdapter->getResultRowObject());
		}
		
		$this->_redirect('/');
    }
}
?>
