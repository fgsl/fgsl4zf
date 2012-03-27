<?php
class Book extends Fgsl_Db_Table_Abstract
{
	protected $_name = 'books';

	protected $_referenceMap = array(
		'Category' => array(
			'columns' => 'id_category',
			'refTableClass' => 'Category',
			'refColumns' => 'id'
			)
	);	
	
	public function __construct()
	{
		parent::__construct();
		$this->_fieldKey = 'id';
		$this->_fieldNames = array('id','title','id_category');
		$this->_fieldLabels = array(
									'id' => 'Id',
									'title' => 'Title',
									'id_category' => 'Category');
		$this->_orderField = 'title';
		$this->_searchField = 'title';
		$this->_selectOptions = array();
		$this->_typeElement = array('id_category' => Fgsl_Form_Constants::SELECT);
		$this->_typeValue = array(
			'id'			=> Fgsl_Db_Table_Abstract::INT_TYPE,
			'id_category'	=> Fgsl_Db_Table_Abstract::INT_TYPE
		);
		$this->_lockedFields = array('id');
	}
	
	/**
	 * inherited method overrided
	 * @param unknown_type $fieldName
	 * @return unknown_type
	 */
	public function getSelectOptions($fieldName)
	{
		$records = null;
		if ($fieldName == 'id_category')
		{
			$records = array();
			$category = new Category();
			$results = $category->fetchAll(null,null);
			foreach ($results as $result) {
				$records[$result->id] = $result->name;
			}
		}
		return $records;
	}
	
	public function setRelationships(array &$records)
	{
		$category = new Category();
		foreach($records as $key => $value)
		{
			$objectCategory = $category->find($records[$key][$this->getFieldLabel('id_category')])->current();
			$records[$key][$this->getFieldLabel('id_category')] = $objectCategory->name;  
		}		
	}
}