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
 * @package    Fgsl_Crud
 * @subpackage Fgsl_Controller_Crud_Action_Interface
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_Controller_Crud_Interface
 */
interface Fgsl_Controller_Action_Crud_Interface extends Fgsl_Controller_Action_Interface
{
	public function init();
	
	public function indexAction();

	public function preEditAction();

	public function saveAction();

	public function removeAction();
	
	public function _config();
	
	public function configureView();
}
?>