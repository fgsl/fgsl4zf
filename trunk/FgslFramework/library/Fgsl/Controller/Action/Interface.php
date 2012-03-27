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
 * @subpackage Fgsl_Controller_Action_Interface
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */
/**
 * Fgsl_Controller_Interface
 */
interface Fgsl_Controller_Action_Interface
{
	/**
	 * @param string $action
	 * @param string $controller
	 * @param string $module	  
	 * @return string
	 */
	public function getUrl($action = null,$controller = null ,$module = null);
}