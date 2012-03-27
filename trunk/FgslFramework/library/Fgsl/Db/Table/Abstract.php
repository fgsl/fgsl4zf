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
 * @package    Fgsl_Db
 * @subpackage Fgsl_Db_Table
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_Db_Table_Abstract
 */
abstract class Fgsl_Db_Table_Abstract extends Zend_Db_Table_Abstract implements Fgsl_Db_Table_Interface
{
	const INT_TYPE = 0;
	const FLOAT_TYPE = 1;
	const BOOLEAN_TYPE = 2;
	const ARRAY_TYPE = 3;
	const OBJECT_TYPE = 4;

	/**
	 * Namespace used in relationship methods
	 * @var string
	 */
	protected static $_dbTableNamespace =  'Application_Model_DbTable_';

	/**
	 * primary key
	 * @var string
	 */
	protected $_fieldKey = null;
	/**
	 * real field names
	 * @var array
	 */
	protected $_fieldNames = array();
	/**
	 * labels used by view
	 * @var array
	 */
	protected $_fieldLabels = array();
	/**
	 * default field used in ordering
	 * @var string
	 */
	protected $_orderField = null;
	/**
	 * field used in searching
	 * @var string
	 */
	protected $_searchField = null;
	/**
	 * Zend_Form_Element types
	 * @var array
	 */
	protected $_typeElement = array();
	/**
	 * equivalent PHP type values
	 * @var array
	 */
	protected $_typeValue = array();
	/**
	 * no editable fields
	 * @var array
	 */
	protected $_lockedFields = array();
	/**
	 * SQL Select
	 * @var Zend_Db_Table_Select
	 */
	protected $_select = null;
	/**
	 * field used as default in a join
	 * @var string
	 */
	protected $_joinField = null;

	public function __construct(array $options = array())
	{
		parent::__construct($options);
		$this->_fieldNames = $this->_getCols();
		$this->setRowClass('Fgsl_Db_Table_Row_Abstract');
		$this->_select = $this->select();
	}
	
	public function getCols()
	{
		return $this->_cols;
	}

	public static function setDbTableNamespace($dbTableNamespace)
	{
		self::$_dbTableNamespace = $dbTableNamespace;
	}

	/**
	 * That is a generic handler for dynamic getters and setters.
	 * It catches calls as setAttribute($value) or getAttribute().
	 * @param string $name
	 * @param array $arguments
	 * @return unknown_type
	 */
	public function __call($name,$arguments)
	{
		$prefix = substr($name,0,3);

		$name = substr($name,3);
		$name = strtolower(substr($name,0,1)).substr($name,1);

		if ($prefix == 'set')
		{
			$this->$name = $arguments[0];
		}
		if ($prefix == 'get')
		{
			return $this->$name;
		}
	}

	/**
	 * Returns field used in join.
	 * When none is defined, use the second field as default (because first should be primary key).
	 * @return string
	 */
	public function getJoinField()
	{
		if (is_null($this->_joinField))
		{
			$fields = $this->getFieldNames();
			$this->_joinField = $fields[1];
		}
		return $this->_joinField;
	}

	/**
	 * Returns table name
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Returns value of primary key from an array with fields data.
	 * @param array $data
	 * @return unknown_type
	 */
	public function getKeyValue(array $data)
	{
		return empty($data) ? null : $data[$this->getFieldKey()];
	}

	/**
	 * Returns type of HTML component that renders the content of attribute.
	 * It needs that attribute $_typeElement is set.
	 * @return unknown_type
	 */
	public function getTypeElement($fieldName)
	{
		if (!isset($this->_typeElement[$fieldName]))
		{
			return Fgsl_Form_Constants::TEXT;
		}
		return $this->_typeElement[$fieldName];
	}

	/**
	 * Returns label that appears before HTML field.
	 * @return unknown_type
	 */
	public function getFieldLabel($fieldName)
	{
		return isset($this->_fieldLabels[$fieldName]) ? $this->_fieldLabels[$fieldName] : 'No label found';
	}

	/**
	 * @param $fieldName foreign key
	 * @param $where filter for filling the select element
	 * @see Fgsl_Db_Table_Interface::getSelectOptions()
	 */
	public function getSelectOptions($fieldName, $where = null)
	{
		$selectOptions = null;

		foreach($this->_referenceMap as $reference)
		{
			$columns = implode('',$reference['columns']);
				
			if ($fieldName == $columns)
			{
				$records = array();
				$refTableClass = $reference['refTableClass'];
				$related = new $refTableClass();
				$results = $related->fetchAll($where);
				$fieldKey = $related->getFieldKey();
				$joinField = $related->getJoinField();
				foreach ($results as $result) {
					$selectOptions[$result->$fieldKey] = $result->$joinField;
				}
				break;
			}
		}
		return $selectOptions;
	}

	/**
	 * Returns content of a field cast to specified type.
	 * String is default and it don't need to be defined.
	 * @return unknown_type
	 */
	public function getCastValue($fieldName,$value)
	{
		if (!isset($this->_typeValue[$fieldName]))
		{
			return $value;
		}
		else
		{
			switch($this->_typeValue[$fieldName])
			{
				case self::INT_TYPE:
					return (int) $value;
					break;
				case self::FLOAT_TYPE:
					return (float) $value;
					break;
				case self::BOOLEAN_TYPE:
					return (boolean) $value;
					break;
				case self::ARRAY_TYPE:
					return (array) $value;
					break;
				case self::OBJECT_TYPE:
					return (object) $value;
					break;
				default:
					return $value;
			}
		}
	}

	/**
	 * Return primary key
	 * @return unknown_type
	 */
	public function getFieldKey()
	{
		return $this->_fieldKey;
	}

	/**
	 * Returns table field names
	 * to be used to modify and remove records.
	 * @return unknown_type
	 */
	public function getFieldNames()
	{
		return $this->_fieldNames;
	}

	/**
	 * Returns default search field.
	 * @return unknown_type
	 */
	public function getSearchField()
	{
		if (in_array($this->_searchField, $this->_cols))
			return $this->_name . '.'  . $this->_searchField;
		else
			return $this->_searchField;
	}

	/**
	 * Returns default field to sorting.
	 * @return unknown_type
	 */
	public function getOrderField()
	{
		if (in_array($this->_orderField, $this->_cols))
			return $this->_name . '.'  . $this->_orderField;
		else 
			return $this->_orderField;
	}

	/**
	 * Indicates if a field is locked to edit
	 * @param $fieldName
	 * @return unknown_type
	 */
	public function isLocked($fieldName)
	{
		return in_array($fieldName,$this->_lockedFields);
	}


	/**
	 * Assembles a custom SQL SELECT statement
	 * @param string $where
	 * @param string $order
	 * @param string $limit
	 * @return Zend_Db_Table_Rowset
	 */
	public function getCustomSelect($where,$order,$limit)
	{
		$select = $this->_select;
		if ($where !== null)
		{
			$select->where($where);
		}
		$select->order($order);
		$select->limit($limit);

		return $select;
	}

	/**
	 * Returns a array with a data rowset
	 * @param Zend_Db_Select | Zend_Db_Table_Select $select
	 * @return array
	 */
	public function fetchAllAsArray($select)
	{
		if ($select instanceof Zend_Db_Select)
		{
			return $this->getAdapter()->fetchAll($select);
		}
		if ($select instanceof Zend_Db_Table_Select)
		{
			$rowSet = $this->fetchAll($select);
			return $rowSet->toArray();
		}

	}

	/**
	 *
	 * @param string $ruleKey
	 * @param string | array $columns
	 * @param string $refTableClass
	 * @param string | array $refColumns
	 */
	protected function _addRelation($ruleKey,$columns,$refTableClass,$refColumns)
	{
		$refTableClass = self::$_dbTableNamespace . $refTableClass;
		$this->addReference($ruleKey, $columns, $refTableClass, $refColumns);
		$this->_addJoin($columns, $refTableClass, $refColumns);
	}

	/**
	 * Adds a join with other mapped table
	 * @param string $columns
	 * @param string $refTableClass
	 * @param string $refColumns
	 */
	protected function _addJoin($columns, $refTableClass, $refColumns)
	{
		if (!(is_array($columns) || is_array($refColumns)))
		{
			$table = $this->_name;
			$refClass = new $refTableClass();
			$refTable = $refClass->getName();

			$condition = "$table.$columns = $refTable.$refColumns";

			$cols = array();
			$fields = $this->getFieldNames();
			foreach ($fields as $index => $value)
			{
				$cols[$value] = $value;
			}
			if ($this->getFieldKey() !== $columns) $cols[$columns] = "$refTable." . $refClass->getJoinField();
			
			$this->_select->setIntegrityCheck(false)
			->from($table,$cols)
			->joinInner($refTable,$condition,null);
		}
	}

	/**
	 * Adds dependent DbTable classes
	 * @param string | array $dependentTables
	 */
	protected function _addDependents($dependentTables)
	{
		$dependentTables = (array) $dependentTables;
		for($i=0;$i<count($dependentTables);$i++)
		{
			$dependentTables[$i] = self::$_dbTableNamespace . $dependentTables[$i];
		}
		$this->setDependentTables($dependentTables);
	}

}