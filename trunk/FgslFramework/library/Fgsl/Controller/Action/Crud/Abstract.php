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
 * @package    Fgsl_Controller
 * @subpackage Fgsl_Controller_Crud_Abstract
 * @copyright  Copyright (c) 2012 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_Controller_Crud_Abstract
 */
abstract class Fgsl_Controller_Action_Crud_Abstract extends Fgsl_Controller_Action_Abstract implements Fgsl_Controller_Action_Crud_Interface
{
	/**
	 * field names of model
	 * @var unknown_type
	 */
	protected $_fieldNames;
	/**
	 * title of listing
	 * @var unknown_type
	 */
	protected $_title;
	/**
	 * hyperlink that indicates where application has to go when let CRUD page
	 * @var unknown_type
	 */
	protected $_returnLink;
	/**
	 * hyperlink that indicates the menu of application
	 * @var unknown_type
	 */
	protected $_menuLink;
	/**
	 * amount items must be listed per page
	 * @var unknown_type
	 */
	protected $_itemsPerPage;
	/**
	 * current page of listing
	 * @var unknown_type
	 */
	protected $_currentPage;
	/**
	 * last page of listing
	 * @var unknown_type
	 */
	protected $_lastPage;
	/**
	 * indicates if there is unique pair of templates for all application
	 * @var unknown_type
	 */
	protected $_uniqueTemplatesForApp;
	/**
	 * defines the keys used to search records
	 * @var array
	 */
	protected $_searchOptions;
	/**
	 * defines the label of search button
	 * @var string
	 */
	protected $_searchButtonLabel;
	/**
	 * indicates if controller use its own templates (neither module nor application)
	 * @var unknown_type
	 */
	protected $_privateTemplates;

	public function init()
	{
		parent::init();

		$this->_overrideView();

		$this->_itemsPerPage = 20;
	}

	/**
	 * This method creates an instance of Fgsl_View equivalent to default Zend_View
	 */
	protected function _overrideView()
	{
		$module  = $this->getRequest()->getModuleName();
		$dirs    = $this->getFrontController()->getControllerDirectory();
		if (empty($module) || !isset($dirs[$module])) {
			$module = $this->getFrontController()->getDispatcher()->getDefaultModule();
		}
		$baseDir = dirname($dirs[$module]) . DIRECTORY_SEPARATOR . 'views';
		$view = new Fgsl_View(array('basePath' => $baseDir));
		$this->view = $view;
		$this->getHelper('viewRenderer')->setView($view);
	}

	/**
	 * Default action is listing data.
	 * Presents a page with a hyperlink to insert records and an array with current records.
	 * Records can be filtered, edited and deleted.
	 * TODO improve way of getting total of records for $totalOfItems
	 */
	public function indexAction()
	{
		$paginator = $this->_getPaginator();
		$this->_configurePagination($paginator);
		$records = $this->_getProcessedRecords($paginator->getCurrentItems());

		$this->view->assign('records',$records);

		$this->configureView();

		$this->view->render('index.phtml');
	}

	/**
	 * Returns a object Zend_Paginator
	 */
	protected function _getPaginator()
	{
		$where = null;
		if (isset($_POST['key']))
		{
			$key = $_POST['key'];
			$value = $_POST['value'];
			$where = "$key like '%$value%'";
		}

		$select = $this->_model->getDbTable()->getCustomSelect($where,$this->_model->getDbTable()->getOrderField(),$this->_itemsPerPage,($this->_currentPage-1)*$this->_itemsPerPage);
		
		$rows = $this->_model->getDbTable()->fetchAllAsArray($select);

		$paginator = Zend_Paginator::factory($rows);

		$paginator->setCurrentPageNumber($this->_currentPage)
		->setItemCountPerPage($this->_itemsPerPage);
		return $paginator;
	}

	/**
	 * configures pagination attributes
	 */
	protected function _configurePagination(Zend_Paginator $paginator)
	{
		$this->_currentPage = $this->_getParam('page',1);
		$this->_currentPage = $this->_currentPage < 1 ? 1 : $this->_currentPage;

		$this->_lastPage = $paginator->count();
	}

	/**
	 * Create a data array to be displayed as a table by view
	 * @param ArrayIterator $currentItems
	 * @return array
	 */
	protected function _getProcessedRecords(ArrayIterator $currentItems, $remove = true)
	{
		$fieldKey = $this->_model->getDbTable()->getFieldKey();

		$records = array();
		foreach ($currentItems as $row)
		{
			$records[] = array();

			$id = $row[$fieldKey];

			$url = $this->getUrl('pre-edit',$this->_controllerName,$this->_useModules ? $this->_moduleName : null,array($fieldKey => $id));

			$records[count($records)-1][$this->_model->getDbTable()->getFieldLabel($fieldKey)] = '<a href="' . $url . '">'.$id.'</a>';

			foreach ($this->_fieldNames as $fieldName)
			{
				if ($fieldName == $fieldKey || !isset($row[$fieldName])) continue;
				$records[count($records)-1][$this->_model->getDbTable()->getFieldLabel($fieldName)] = $row[$fieldName];
			}

			$url = $this->getUrl('remove',$this->_controllerName,$this->_useModules ? $this->_moduleName : null,array($fieldKey => $id));

			if ($remove) $records[count($records)-1]['remove'] = '<a href="' . $url . '">X</a>';
		}
		return $records;
	}

	/**
	 * Configure items to be assigned to object view
	 */
	public function configureView()
	{
		$this->view->setTitle($this->view->escape($this->_title));
		$this->view->setInsertLink($this->getUrl() . '/pre-edit');
		$this->view->setListLink($this->getUrl() . '/index');
		$this->view->setReturnLink($this->getUrl());
		$this->view->setMenuLink($this->_menuLink);
		$this->view->setCurrentPage($this->_currentPage);
		$this->view->setlastPage($this->_lastPage);
		$this->view->setSearchField($this->_model->getDbTable()->getSearchField());
		$this->view->setlabelSearchField($this->_model->getDbTable()->getFieldLabel($this->_model->getDbTable()->getSearchField()));
		$this->view->setSearchForm($this->_getSearchForm());
	}

	/**
	 * Shows insert form
	 */
	public function preEditAction()
	{
		$action = $this->getUrl('save',$this->_controllerName,$this->_useModules ? $this->_moduleName : null);

		if (isset(Zend_Registry::get('request')->form))
		{
			$form = Zend_Registry::get('request')->form;
			$request = Zend_Registry::get('request');
			unset($request->form);
		}
		else
		{
			$data = $this->_getInputData();

			$options = array(
					Fgsl_Form_Edit::DATA => $data,
					Fgsl_Form_Edit::ACTION => $action,
					Fgsl_Form_Edit::MODEL => $this->_model
			);
			$form = new Fgsl_Form_Edit($options);
		}

		$this->view->assign('form', $form);
		$this->view->render('pre-edit.phtml');
	}

	protected function _getInputData()
	{
		$data = $_POST;
		if (empty($data))
		{
			$fieldKey = $this->_model->getDbTable()->getFieldKey();
			$keyValue = $this->_getParam($fieldKey,null);
			if (!is_null($keyValue))
			{
				$record = $this->_model->getDbTable()->find($keyValue)->current()->toArray();
				$data = $record;
			}
		}
		return $data;
	}

	public function saveAction()
	{
		if (isset($_POST['Return']))
		{
			$this->_redirect($this->getRequest()->getModuleName().'/'.$this->getRequest()->getControllerName());
			return;
		}

		$options = array();
		$options[Fgsl_Form_Edit::ACTION] = '';
		$options[Fgsl_Form_Edit::DATA] = $_POST;
		$options[Fgsl_Form_Edit::MODEL] = $this->_model;

		$form = new Fgsl_Form_Edit($options);

		if (!$form->isValid($_POST))
		{
			Zend_Registry::get('request')->form = $form;
			$this->_forward('index');
			return;
		}

		$this->_model->save($_POST);

		$this->_redirect($this->getRequest()->getModuleName().'/'.$this->getRequest()->getControllerName());
	}

	/**
	 * Method to remove records
	 */
	public function removeAction()
	{
		$key = $this->_getParam($this->_model->getDbTable()->getFieldKey());

		$this->_model->remove($key);

		$this->_redirect($this->getRequest()->getModuleName().'/'.$this->getRequest()->getControllerName());
	}

	/**
	 * Sets attribute $_fieldNames with fieldnames of model
	 */
	public function _config()
	{
		$this->_fieldNames = $this->_model->getDbTable()->getFieldNames();
		// prevents add controller name into script path
		if (!$this->_privateTemplates)
			$this->getHelper('viewRenderer')->setNoController(true);		
	}

	/**
	 * Builds a search form
	 * @return Zend_Form
	 */
	protected function _getSearchForm()
	{
		$options = array();
		$options['multioptions'] = $this->_searchOptions;
		$options['searchButtonLabel'] = $this->_searchButtonLabel;

		$form = new Fgsl_Form_Search();
		$form->setAction($this->getUrl());
		$form->prepare($options);

		return $form;
	}
}
?>