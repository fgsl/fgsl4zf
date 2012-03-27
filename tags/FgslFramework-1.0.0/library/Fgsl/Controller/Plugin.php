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
 * @subpackage Fgsl_Controller_Plugin
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_Controller_Plugin
 * 
 * This plugin depends on existence of a model that implements Fgsl_Route_Ruler_Interface 
 */
class Fgsl_Controller_Plugin extends Zend_Controller_Plugin_Abstract
{
	protected static $_routeRoler = null;

	public static function setRouteRuler(Fgsl_Controller_Router_Route_Ruler_Interface $routeRuler)
	{
		self::$_routeRoler = $routeRuler;	
	}		
	
	/**
	 * This constructor expects a key whose name is the value of constant ROUTE_RULER, that 
	 * must be a class that implements Fgsl_Route_Ruler_Interface.
	 */
	public function __construct()
	{
		if (empty(self::$_routeRoler))
		{
			throw new Fgsl_Exception('There is no instance that implements Fgsl_Route_Ruler_Interface');
			return;	
		}		
	}
	
	public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    	if (self::$_routeRoler->hasRouteStartup())
    	{
			$newRoute = self::$_routeRoler->getRouteStartup($this->getCurrentRoute($request));
			$this->setNewRoute($newRoute);
    	}
    }
    
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {  	
    	if (self::$_routeRoler->hasRouteShutdown())
    	{
			$newRoute = self::$_routeRoler->getRouteShutdown($this->getCurrentRoute($request));
			$this->setNewRoute($newRoute);			
    	}    	
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {    	
    	if (self::$_routeRoler->hasRoutePreDispatch())
    	{
			$newRoute = self::$_routeRoler->getRoutePreDispatch($this->getCurrentRoute($request));
			$this->setNewRoute($newRoute);			
    	}    	
    }
    
    public function posDispatch(Zend_Controller_Request_Abstract $request)
    {    	
    	if (self::$_routeRoler->hasRoutePosDispatch())
    	{
			$newRoute = self::$_routeRoler->getRoutePosDispatch($this->getCurrentRoute($request));
			$this->setNewRoute($newRoute);			
    	}    	
    }    
    
    /**
     * Sets a new route based on required model called RouteRuler
     * @param array $currentRoute
     */
    protected function setNewRoute($newRoute)
    {
    	$this->getRequest()->setModuleName($newRoute['module']);
    	$this->getRequest()->setControllerName($newRoute['controller']);
    	$this->getRequest()->setActionName($newRoute['action']);
    }
    
    /**
     * Returns original route 
     * @return array
     */
    protected function getCurrentRoute(Zend_Controller_Request_Abstract $request)
    {
    	$currentRoute = array();
    	$currentRoute['module'] = $request->getModuleName();
		$currentRoute['controller'] = $request->getControllerName();
    	$currentRoute['action'] = $request->getActionName();
  	
    	return $currentRoute;    	
    }    
}
?>