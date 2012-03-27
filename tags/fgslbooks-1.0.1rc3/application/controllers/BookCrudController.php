<?php
class BookCrudController extends Fgsl_Crud_Controller_Abstract
{
	public function init()
	{
		parent::init();		
		
		Zend_Loader::loadClass('Book');
		Zend_Loader::loadClass('Category');			

		$this->_useModules = false;
		$this->_model = new Book();		
		$this->_title = 'Book Listing';
		$this->_searchOptions = array('name'=>'Nome');
		$this->_searchButtonLabel = 'Name';		
		$this->_config();
	}	
	
}