<?php

class AuthPlugin extends Zend_Controller_Plugin_Abstract{
	private $acl;
	
	public function __construct(){
		$this->acl = new Zend_Acl();
    	
    	$none = new Zend_Acl_Role('none');
    	$guest = new Zend_Acl_Role('guest');
    	$admin = new Zend_Acl_Role('admin');
    	$superAdmin = new Zend_Acl_Role('superadmin');
    	
    	$this->acl->addRole($none);
    	$this->acl->addRole($guest);
    	$this->acl->addRole($admin, $guest);
    	$this->acl->addRole($superAdmin, $admin);
    	
    	//A resource-ok a controllerek
    	$this->acl->add(new Zend_Acl_Resource('index'));
    	$this->acl->add(new Zend_Acl_Resource('error'));
		$this->acl->add(new Zend_Acl_Resource('beosztas'));
		$this->acl->add(new Zend_Acl_Resource('felhasznalok'));
    	
    	$this->acl->allow('guest', 'index');
    	$this->acl->allow('guest', 'error');
	    $this->acl->allow('admin', 'beosztas');
	    $this->acl->allow('superadmin', 'felhasznalok');
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request){
		$module = $request->getModuleName();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		
		$resource = $controller;
		
		$auth = Zend_Auth::getInstance();
		
		
		if($controller !== 'auth' && $action !== 'login'){	
			if($auth->hasIdentity()){
				$data = $auth->getStorage()->read();
				$role = $data->role;
			}
			else{
				$role = 'none';
			}
		
			if (!$this->acl->has($resource)){
				$resource = null;
			}
			if (!$this->acl->isAllowed($role, $resource)){
				$controller = "auth";
				
				if (!$auth->hasIdentity()){
					$action = "login";
				}
				else{
					$action = "privileges";
				}
				
				$request->setModuleName( $module );
				$request->setControllerName( $controller );
				$request->setActionName( $action );
				$request->setDispatched( false );
			}
		}
    }
}

?>
