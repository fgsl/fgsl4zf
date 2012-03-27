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
    	
    	echo 'controller:'.$controller.'<br/>';
    	echo 'action:'.$action.'<br/>';
    	echo 'module:'.$module.'<br/>';
    	exit;
    }


}

