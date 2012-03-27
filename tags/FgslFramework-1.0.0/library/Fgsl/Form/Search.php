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
 * @package    Fgsl_Form
 * @subpackage Fgsl_Form_Search
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_Form_Search
 */
class Fgsl_Form_Search extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		
		$element = new Zend_Form_Element_Select('key');
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Text('value');
		$this->addElement($element);		
	}
	
	/**
	 * 
	 * @param array $options
	 */
	public function prepare(array $options)
	{		
		$this->getElement('key')->setMultiOptions($options['multioptions']);
		
		$element = new Zend_Form_Element_Submit($options['searchButtonLabel']);
		$this->addElement($element);		
	}
}