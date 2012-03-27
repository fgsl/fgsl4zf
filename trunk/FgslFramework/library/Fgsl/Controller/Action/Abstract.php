<?php
/**
 * Fgsl Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license, you can get it at www.fgsl.eti.br.
 *
 * @category   Fgsl
 * @package    Fgsl_Controller
 * @subpackage Fgsl_Controller_Action_Abstract
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */
/**
 * Fgsl_Controller_Abstract
 */
abstract class Fgsl_Controller_Action_Abstract extends Zend_Controller_Action implements Fgsl_Controller_Action_Interface
{
	/**
	 * model object used by controller
	 * @var Fgsl_Model_Abstract
	 */
	protected $_model;
	/**
	 * action name
	 * @var string
	 */
	protected $_actionName;	
	/**
	 * controller action name
	 * @var string
	 */
	protected $_controllerName;
	/**
	 * module name
	 * @var string
	 */
	protected $_moduleName;
	/**
	 * indicates if application use modules structure
	 * @var boolean
	 */
	protected $_useModules;

	public function init()
	{
		$this->_actionName = $this->getRequest()->getActionName();
		$this->_controllerName = $this->getRequest()->getControllerName();
		$this->_moduleName = $this->getRequest()->getModuleName();
		$this->_useModules = (boolean) (strpos($this->getFrontController()->getModuleDirectory(), 'modules')); 
		// session namespace below allows to store data needs between requests
		Zend_Registry::set('request',new Zend_Session_Namespace('request'));
	}

	/**
	 * Return url to be used in hyperlinks
	 * @return unknown_type
	 */
	public function getUrl($action = null, $controller = null, $module = null ,$arguments = array(), $routeName = 'default')
	{
		$route = array();
		
		if (is_null($action))
		{
			$action = $this->_actionName;
		}
		$route['action'] = $action;	
		
		if (is_null($controller))
		{
			$controller = $this->_controllerName;
		}
		$route['controller'] = $controller;
		
		if ($this->_useModules)
		{
			if (is_null($module))
			{
				$module = $this->_moduleName;
			}
			$route['module'] = $module;
		}
		foreach($arguments as $key => $value)
		{
			$route[$key] = $value;
		}

		return $this->view->url($route,$routeName);
	}

	/**
	 *
	 * @param Exception $e
	 */
	protected function _redirectToErrorController(Exception $e)
	{
		$this->_redirect('error/error/message/' . $e->getMessage());
	}

}