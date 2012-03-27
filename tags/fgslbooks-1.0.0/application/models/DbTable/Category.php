<?php

class Application_Model_DbTable_Category extends Fgsl_Db_Table_Abstract
{

    protected $_name = 'categories';
        
    public function __construct()
    {
    	parent::__construct();
    	$this->_fieldKey = 'id';
    	$this->_fieldNames = array('id','name');
    	$this->_fieldLabels = array(
    			'id' => 'Id',
    			'name' => 'Name');
    	$this->_orderField = 'name';
    	$this->_searchField = 'name';
    	$this->_typeValue = array(
    			'id' => Fgsl_Db_Table_Abstract::INT_TYPE,
    	);
    	$this->_lockedFields = array('id'); 
    	$this->_joinField = 'name';
		$this->_addDependents('Book');
    }    
    

}

