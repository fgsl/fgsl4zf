<?php

class Application_Model_DbTable_Book extends Fgsl_Db_Table_Abstract
{

    protected $_name = 'books';
    
    public function __construct()
    {
    	parent::__construct();
    	$this->_fieldKey = 'id';
    	$this->_fieldNames = array('id','title','id_category');
    	$this->_fieldLabels = array(
    			'id' => 'Id',
    			'title' => 'Title',
    			'id_category' =>
    			'Category');
    	$this->_orderField = 'title';
    	$this->_searchField = 'title';
    	$this->_selectOptions = array();
    	$this->_typeElement = array('id_category' =>
    			Fgsl_Form_Constants::SELECT);
    	$this->_typeValue = array(
    			'id'
    			=> Fgsl_Db_Table_Abstract::INT_TYPE,
    			'id_category'
    			=> Fgsl_Db_Table_Abstract::INT_TYPE
    	);
    	$this->_lockedFields = array('id');
    	$this->_addRelation('Category', 'id_category', 'Category', 'id');
    }
    
    
}

