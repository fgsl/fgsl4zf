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
 * @package    Fgsl_View
 * @subpackage Fgsl_View
 * @copyright  Copyright (c) 2009 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    0.0.1
 */

/**
 * Fgsl_View
 */

class Fgsl_View extends Zend_View
{
	/**
	This method calls render() of Zend_View, but cancels next actions.
	 */
	public function render($name)
	{
		echo parent::render($name);
		exit;
	}
}
?>