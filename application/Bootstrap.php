<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	protected function _initAutoloader()
    {
    	set_include_path(implode(PATH_SEPARATOR, array(
		    realpath(APPLICATION_PATH . '/models'),
		    realpath(APPLICATION_PATH . '/plugins'),
		    get_include_path(),
		)));
		
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->setFallbackAutoloader(true);
    }

    protected function _initDbCharset(){
    	$db = $this->getPluginResource('db')->getDbAdapter();
    	/* kell, kulonben a kapcsolat charset-e default iso-8859-2 */
		$db->query("SET NAMES 'utf8'");
		$db->query("SET CHARACTER SET utf8");
    }
    
	protected function _initFrontControllerPlugins(){
    	$frontController = Zend_Controller_Front::getInstance();
    	$frontController->registerPlugin(new AuthPlugin());
    }
    
    protected function _initRegistry(){
    	Zend_Registry::set('baseURL', '');
    }
}

