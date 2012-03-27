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
 * @package    Fgsl_Plugin
 * @subpackage Fgsl_Plugin
 * @copyright  Copyright (c) 2009 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    0.0.1
 */

/**
 * Fgsl_Plugin
 */
class Fgsl_Plugin extends Zend_Controller_Plugin_Abstract
{
	public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    }
    
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
    }

    /**
     * This method checks permissions to execute actions according to user roles.
     * This method needs a IndexController with methods index and menu.
     * It also needs a AccessController.
     * indexAction of IndexController redirects to AccessController.
     * Authentication action of AccessControllers must redirect to menu action
     * 	of IndexController.				
     * User roles must be into session.	  
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {    	
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();
    	$module = $request->getModuleName();

    	if ($controller == 'access' || ($controller == 'index' && ($action == 'index' || $action = 'menu')))
	   		return;

		$session = Zend_Registry::get('session');	   		
    	
    	if (is_null($session->user))
    	{
			$this->getRequest()->setControllerName('index');
			$this->getRequest()->setActionName('index');
			return;    		
    	}
		
		$acl = Zend_Registry::get('acl');		
		
		$roles = $session->roles;
		
		$allow = false;
		
		foreach ($roles as $role)
		{	
			if 	($acl->isAllowed($acl->getRole($role),$acl->get($module)))
			{
				if ($acl->isAllowed($acl->getRole($role),$acl->get($controller),$action))
				{
					$allow = true;
					break;
				}				
			}
		}

		if (!$allow)			
		{			
			$this->getRequest()->setControllerName('index');
			$this->getRequest()->setActionName('menu');						
		}    	
    }
    
}
?>