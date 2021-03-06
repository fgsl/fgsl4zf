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
 * @subpackage CategoryCrudController
 * @copyright  Copyright (c) 2010 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0rc4
 */

class CategoryCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();		
		
		Zend_Loader::loadClass('Category');			

		$this->_useModules = false;
		$this->_model = new Category();		
		$this->_title = 'Category Listing';
		$this->_searchOptions = array('name'=>'Name');
		$this->_searchButtonLabel = 'pesquisar'; 
		$this->_config();
	}	
	
}