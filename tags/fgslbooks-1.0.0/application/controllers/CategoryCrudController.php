<?php

class CategoryCrudController extends Fgsl_Controller_Action_Crud_Abstract
{

	public function init()
	{
		parent::init();
		$this->_model = new Application_Model_Category();
		$this->_title = 'Category Listing';
		$this->_searchOptions = array($this->_model->getDbTable()->getSearchField()=>'name');
		$this->_searchButtonLabel = 'Name';
		$this->_config();
	}


}

