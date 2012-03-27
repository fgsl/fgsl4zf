<?php

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
    	
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();
    	$module = $request->getModuleName();
    	
    	var_dump($controller);
    	var_dump($action);
    	var_dump($module);
    	exit;
    }


}

