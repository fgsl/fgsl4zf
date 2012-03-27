<?php

class BookCrudController extends Fgsl_Controller_Action_Crud_Abstract
{

	public function init()
	{
		parent::init();
		$this->_useModules = false;
		$this->_model = new Application_Model_Book();
		$this->_title = 'Book Listing';
		$this->_searchOptions = array($this->_model->getDbTable()->getSearchField()=>'Title');
		$this->_searchButtonLabel = 'Search';
		$this->_config();
	}
}

