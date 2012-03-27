<?php
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
		$this->_searchButtonLabel = 'Name';		
		$this->_config();
	}	
	
}