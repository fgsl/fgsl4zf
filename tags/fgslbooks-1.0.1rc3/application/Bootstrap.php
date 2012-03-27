<?php
/**
 * 
 * @author Fl�vio Gomes da Silva Lisboa
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function __construct($application)
	{
		parent::__construct($application);
		$this->connectDatabase();
		$this->useTheseComponents();
		$this->createInputFilter();
	}
	
	private function connectDatabase()
	{
		Zend_Loader::loadClass('Zend_Db');
		Zend_Loader::loadClass('Zend_Config_Ini');
		Zend_Loader::loadClass('Zend_Registry');
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini','database');
		$db = Zend_Db::factory($config->db->adapter,$config->db->config->toArray());
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db',$db);		
	}
	
	public function useTheseComponents()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Zend');
		$autoloader->registerNamespace('Fgsl');
	}

	public function createInputFilter()
	{
		$post = new Zend_Filter_Input(null,null,$_POST);
		Fgsl_Session_Namespace::init();
		Fgsl_Session_Namespace::set('post',$post);
	}
}

