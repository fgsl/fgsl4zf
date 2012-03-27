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
 * @package    Fgsl_Session
 * @subpackage Fgsl_Session_Namespace
 * @copyright  Copyright (c) 2009 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    0.0.1
 */

/**
 * Fgsl_Session_Namespace
 */
class Fgsl_Session_Namespace
{
	const DEFAULT_NAMESPACE = 'session';
	/**
	 * Create a namespace in session called session
	 * @return unknown_type
	 */
	public static function init($namespace = null)
	{
		$namespace = self::getNamespace($namespace);		
		Zend_Registry::set($namespace,new Zend_Session_Namespace($namespace));
	}

	/**
	 * Get a value from the a namespace of session  
	 * @param string $key
	 * @return unknown_type
	 */
	public static function get($key)
	{
		$namespace = self::getNamespace($namespace);		
		$session = Zend_Registry::get($namespace);
		return $session->$key;
	}
	
	/**
	 * Set a value into a namespace of session
	 * @param $key
	 * @param $value
	 * @return unknown_type
	 */
	public static function set($key,$value)
	{
		$namespace = self::getNamespace($namespace);
		$session = Zend_Registry::get($namespace);
		$session->$key = $value;
		Zend_Registry::set($namespace,$session);
	}
	
	/**
	 * Assumes 'session' as namespace if none was specified
	 * @param string $namespace
	 * @return unknown_type
	 */
	private static function getNamespace($namespace)
	{
		return is_null($namespace) ? 'session' : $namespace;
	}
}