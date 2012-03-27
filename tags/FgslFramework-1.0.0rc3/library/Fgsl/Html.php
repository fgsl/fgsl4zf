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
 * @package    Fgsl_Html
 * @subpackage Fgsl_Html
 * @copyright  Copyright (c) 2009 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    0.0.2
 */

/**
 * Fgsl_HTml
 */
class Fgsl_Html
{	
	protected $_decorators = array();	
	
	/**
	 * Add an object that whose methods will be used as they belongs to this class. 
	 * @param unknown_type $decorator
	 * @return unknown_type
	 */
	public function addDecorator($decorator)
	{
		$instance = new $decorator();
		$this->_decorators[] = $instance;
	}	
	
	/**
	 * Calls a method of a decorator instance.
	 * $arguments is an array, in which a element 0 is method argument
	 * to be executed and element 1 is decorator owner of method. 
	 * @param $method
	 * @param $arguments
	 * @return unknown_type
	 */
	public function __call($method,$arguments)
	{
		foreach ($this->_decorators as $decorator) {
				$class = get_class($decorator);
				$methods = get_class_methods($class);
				if (in_array($method,$methods) && $class == $arguments[1])
				{
					return $decorator->$method($arguments[0],$arguments[2]);
                       
					break;
				}			
		}
	}
}
?>
