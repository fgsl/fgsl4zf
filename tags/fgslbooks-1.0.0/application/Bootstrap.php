<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initNamespace()
	{
		Zend_Loader_Autoloader::getInstance()->registerNamespace('Fgsl');
	}

}

