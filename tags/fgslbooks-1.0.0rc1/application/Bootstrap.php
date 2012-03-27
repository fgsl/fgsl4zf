<?php

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
		Zend_Loader::loadClass('Fgsl_Crud_Controller_Abstract');
	}

	public function createInputFilter()
	{
		$post = new Zend_Filter_Input(null,null,$_POST);
		Zend_Registry::set('post',$post);
	}
}

