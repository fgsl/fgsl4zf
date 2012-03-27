<?php
/**
 * Fgsl Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license, you can get it at www.fgsl.eti.br.
 * It depends on Zend_Form and Zend_Form_Element*
 *
 * @category   Fgsl
 * @package    Fgsl_Form
 * @subpackage Fgsl_Form_Edit
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_Form_Edit
 */
class Fgsl_Form_Edit extends Zend_Form
{

	const ACTION = 'ACTION';
	const MODEL = 'MODEL';
	const DATA = 'DATA';

	const INSERT_LABEL = 'insert';
	const EDIT_LABEL = 'edit';

	private $_options;

	public function __construct($options)
	{
		parent::__construct('edit');
		$this->_options = $options;
		$this->_buildAndFill();
	}

	/**
	 * Builds a form form data
	 * @return Fgsl_Form_Edit
	 */
	protected function _buildAndFill()
	{
		if (!isset($this->_options))
			throw new Fgsl_Exception(
					Fgsl_Exception::MISSING_OPTIONS_FOR_OBJECT_MESSAGE,
					Fgsl_Exception::MISSING_OPTIONS_FOR_OBJECT_CODE);

		$action = $this->_options[self::ACTION];
		$model = $this->_options[self::MODEL];

		$this->setAction($action);
		$this->setMethod('post');

		if (!isset($this->_options[self::DATA]) || empty($this->_options[self::DATA]))
			$this->_options[self::DATA] = $this->_getVoidFieldNames($model);

		foreach($this->_options[self::DATA] as $fieldName => $fieldValue)
		{
			if ($model->getDbTable()->isLocked($fieldName))
			{
				continue;
			}
			try {
				$this->_addElement($fieldName,$model);
			}
			catch(Exception $e)
			{
				// TODO use message of exception
				return null;
			}
		}

		/**
		 * Stores primary key value into a HTML hidden element
		 */
		if ($this->_options[self::DATA][$model->getDbTable()->getFieldKey()] !== '')
		{
			$text = new Zend_Form_Element_Hidden($model->getDbTable()->getFieldKey());

			$text->setValue($model->getDbTable()->getKeyValue($this->_options[self::DATA]));

			$this->addElement($text);
		}

		$formType = $this->_options[self::DATA][$model->getDbTable()->getFieldKey()] === '' ? $this->_getLabel(self::INSERT_LABEL) : $this->_getLabel(self::EDIT_LABEL);

		$text = new Zend_Form_Element_Submit(ucfirst($formType));

		$this->addElement($text);

		$text = new Zend_Form_Element_Submit('Return');
		$text->setAttrib('name','return');

		$this->addElement($text);

		return $this;
	}

	/**
	 *
	 * @param string $fieldName
	 * @param Fgsl_Model_Abstract $model
	 */
	protected function _addElement($fieldName,$model)
	{
		$element = $this->_getFormElement($fieldName, $model->getDbTable()->getTypeElement($fieldName),$model);
		$element->setLabel($model->getDbTable()->getFieldLabel($fieldName));
		isset($this->_options[self::DATA][$fieldName]) ? $element->setValue($this->_options[self::DATA][$fieldName]) : '';
		$element->setFilters($model->getFilters($fieldName));
		$element->setValidators($model->getValidators($fieldName));

		if (isset($this->_options['readonly']))
		{
			if (array_key_exists($fieldName,$this->_options['readonly']))
			{
				$element->setAttrib('readonly','readonly');
			}
		}

		$this->addElement($element);
	}

	/**
	 *
	 * @param string $fieldName
	 * @param string $typeElement
	 * @param Fgsl_Db_Table_Abstract $model->getDbTable()()
	 * @return mixed
	 */
	protected function _getFormElement($fieldName, $typeElement, $model)
	{
		$element = null;
		switch($typeElement)
		{
			case Fgsl_Form_Constants::CHECKBOX:
				$element = new Zend_Form_Element_Checkbox($fieldName);
				break;
			case Fgsl_Form_Constants::MULTICHECKBOX:
				$element = new Zend_Form_Element_MultiCheckbox($fieldName);
				break;
			case Fgsl_Form_Constants::MULTISELECT:
				$element = new Zend_Form_Element_Multiselect($fieldName);
				$selectOptions = $model->getDbTable()->getSelectOptions($fieldName);
				$element->addMultiOptions($selectOptions);
				break;
			case Fgsl_Form_Constants::PASSWORD:
				$element = new Zend_Form_Element_Password($fieldName);
				break;
			case Fgsl_Form_Constants::RADIO:
				$element = new Zend_Form_Element_Radio($fieldName);
				break;
			case Fgsl_Form_Constants::SELECT:
				$element = new Zend_Form_Element_Select($fieldName);
				$selectOptions = $model->getDbTable()->getSelectOptions($fieldName);
				$element->addMultiOptions($selectOptions);
				break;
			case Fgsl_Form_Constants::TEXT:
				$element = new Zend_Form_Element_Text($fieldName);
				break;
			default:				
				$element = new $typeElement($fieldName);
		}
		return $element;
	}


	/**
	 * Allows changing default button labels
	 * @param string $label
	 * @return string
	 */
	protected function _getLabel($label)
	{
		return $label;
	}

	/**
	 * Builds an array only with keys
	 * @param Fgsl_Model_Abstract $model
	 * @return array
	 */
	protected function _getVoidFieldNames(Fgsl_Model_Abstract $model)
	{
		$fieldNames = $model->getDbTable()->getFieldNames();
		$voidFieldNames = array();
		foreach($fieldNames as $fieldName)
		{
			$voidFieldNames[$fieldName] = '';
		}
		return $voidFieldNames;
	}

}
?>