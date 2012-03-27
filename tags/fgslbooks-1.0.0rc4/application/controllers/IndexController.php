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
 * @package    Controller
 * @subpackage Index
 * @copyright  Copyright (c) 2010 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0rc4
 */

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }
    
    public function indexAction()
    {
        // action body
        $request = $this->getRequest();
        
        $module = $request->getModuleName();
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();

    	var_dump('Module: '.$module);    	
    	var_dump('Controller: '.$controller);
    	var_dump('Action: '.$action);
    	exit;
    }

}

