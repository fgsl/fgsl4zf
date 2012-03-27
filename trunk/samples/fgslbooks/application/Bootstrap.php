<?php
/**
 * fgslbooks
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. 
 * If you did not receive a copy of the license, you can get it at www.fgsl.eti.br. 
 *
 * @category   fgslbooks
 * @package    Bootstrap
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2010 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0rc4
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function __construct($application)
	{
		parent::__construct($application);
		$this->_connectDatabase();
		$this->_createInputFilter();
	}
	
	private function _connectDatabase()
	{
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini','database');
		$db = Zend_Db::factory($config->db->adapter,$config->db->config->toArray());
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db',$db);		
	}	

	private function _createInputFilter()
	{
		$post = new Zend_Filter_Input(null,null,$_POST);
		Fgsl_Session_Namespace::init();				
		Fgsl_Session_Namespace::set('post',$post);
	}
}

