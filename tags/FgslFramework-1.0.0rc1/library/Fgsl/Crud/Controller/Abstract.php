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
 * @package    Fgsl_Crud
 * @subpackage Fgsl_Crud_Controller_Abstract
 * @copyright  Copyright (c) 2009 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    0.0.1
 */

/**
 * Fgsl_Crud_Controller_Abstract
 */
require('Interface.php');
abstract class Fgsl_Crud_Controller_Abstract extends Zend_Controller_Action implements Fgsl_Crud_Controller_Interface
{
	protected $_model;
	protected $_post;
	protected $_profiler;
	protected $_controllerAction;
	protected $_moduleName;
	protected $_fieldNames;
	protected $_title;
	protected $_view;
	protected $_basePath;
	protected $_returnLink;
	protected $_itemsPerPage;
	protected $_useModules;
	protected $_currentPage;
	protected $_lastPage;
	protected $_table;

	public function init()
	{		
		Zend_Loader::loadClass('Fgsl_Db_Table_Abstract');
		Zend_Loader::loadClass('Fgsl_Form_Edit');
		Zend_Loader::loadClass('Fgsl_Html_Constants');
		Zend_Loader::loadClass('Fgsl_Html');		
		Zend_Loader::loadClass('Fgsl_Html_Table');
		Zend_Loader::loadClass('Fgsl_Session_Namespace');		
		Zend_Loader::loadClass('Fgsl_View');		
		Zend_Loader::loadClass('Zend_Form');
		Zend_Loader::loadClass('Zend_Form_Element_Text');
		Zend_Loader::loadClass('Zend_Form_Element_Select');
		Zend_Loader::loadClass('Zend_Form_Element_Hidden');
		Zend_Loader::loadClass('Zend_Form_Element_Submit');
		Zend_Loader::loadClass('Zend_Paginator');
		Zend_Loader::loadClass('Zend_View');
		Zend_Loader::loadClass('Zend_View_Helper_PaginationControl');

		Fgsl_Session_Namespace::init();

		$this->_itemsPerPage = 20;

		$this->_basePath = $this->getFrontController()->getBaseUrl();

		$this->_view = new Fgsl_View();
		$this->_view->setEscape('htmlentities');
		$this->_view->setEncoding('UTF-8');		

		$this->_post = Zend_Registry::get('post');
		$db = Zend_Registry::get('db');
		$this->_profiler = $db->getProfiler();
		$this->_controllerAction = $this->getRequest()->getControllerName();
		$this->_moduleName = $this->getRequest()->getModuleName();
	}

	/**	
	 * Default action is listing data
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#indexAction()
	 */
	public function indexAction()
	{
		$this->_forward('list');
	}

	/**
	 * Presents a page with a hyperlink to insert records and a table with records.
	 * Records can be filtered, edited and deleted.
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#listAction()
	 */
	public function listAction()
	{
		$module = $this->_useModules ? $this->_moduleName.'/' : '';
		
		Zend_Paginator::setDefaultScrollingStyle('Sliding');

		Zend_View_Helper_PaginationControl::setDefaultViewPartial('list.phtml');

		$this->_currentPage = $this->_getParam('page',1);
		$this->_currentPage = $this->_currentPage < 1 ? 1 : $this->_currentPage;

		$where = isset($this->_post->key) ? ($this->_post->key." like '%".$this->_post->value."%'") : null;

		if ($where == null)
		{
			$select = $this->_model->select()->
			order($this->_model->getOrderField())->
			limit($this->_itemsPerPage,($this->_currentPage-1)*$this->_itemsPerPage);
		}
		else
		{
			$select = $this->_model->select()->		
			where($where)->
			order($this->_model->getOrderField())->
			limit($this->_itemsPerPage,($this->_currentPage-1)*$this->_itemsPerPage);			
		}		
		
		$rows = $this->_model->fetchAll($select);		

		$profile = $this->_profiler->getLastQueryProfile();

		$paginator = Zend_Paginator::factory($rows);

		$paginator->setCurrentPageNumber($thi->_currentPage)
					->setItemCountPerPage($this->_itemsPerPage);
		
		/** TODO get total of records for $totalOfItems  
		 * @var unknown_type
		 */
		$totalOfItems = $this->_itemsPerPage;

		$this->_lastPage = (int)(($totalOfItems/$this->_itemsPerPage));

		$html = new Fgsl_Html();

		$html->addDecorator(Fgsl_Html_Constants::HTML_DECORATOR_TABLE);

		$records = array();

		$fieldKey = $this->_model->getFieldKey();

		$currentItems = $paginator->getCurrentItems();
		foreach ($currentItems as $row)
		{
			$records[] = array();
				
			$id = $row->$fieldKey;
			$records[count($records)-1][$this->_model->getFieldLabel($fieldKey)] = '<a href="'.BASE_URL.$module.$this->_controllerAction.'/edit/'.$fieldKey.'/'.$id.'">'.$id.'</a>';
				
			foreach ($this->_fieldNames as $fieldName)
			{
				if ($fieldName == $fieldKey) continue;
				$records[count($records)-1][$this->_model->getFieldLabel($fieldName)] = $row->$fieldName;
			}
				
			$records[count($records)-1]['remove'] = '<a href="'.BASE_URL.$module.$this->_controllerAction.'/remove/'.$fieldKey.'/'.$id.'">X</a>';
		}
		$this->_model->setRelationships($records);		
		
		$this->_table = $html->create($records,Fgsl_Html_Constants::HTML_DECORATOR_TABLE);		

		$this->configureViewAssign();
		$this->_view->render('list.phtml');
	}

	/**
	 * Shows insert form
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#insertAction()
	 */
	public function insertAction()
	{
		$module = $this->_useModules ? "{$this->_moduleName}/" : '';
		
		$data = $this->_getDataFromPost();

		$options = array(
		Fgsl_Form_Edit::DATA => $data,
		Fgsl_Form_Edit::ACTION => BASE_URL."$module{$this->_controllerAction}/save",
		Fgsl_Form_Edit::MODEL => $this->_model
		);

		$this->_view->assign('form', new Fgsl_Form_Edit($options));
		$this->_view->render('insert.phtml');
	}

	/**
	 * 
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#editAction()
	 */
	public function editAction()
	{

		$fieldKey = $this->_model->getFieldKey();
		$record = $this->_model->fetchRow("{$fieldKey} = {$this->_getParam($fieldKey)}");

		$data = array();
		foreach ($this->_fieldNames as $fieldName)
		{
			$data[$fieldName] = $record->$fieldName;
		}

		$session = Zend_Registry::get('session');

		$session->data = $data;

		Zend_Registry::set('session',$session);

		$this->_forward('insert');
	}

	/**
	 * Gets a Fgsl_Form object
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#getEditForm($dados, $action, $model)
	 */
	public function getEditForm(array $data,$action,$model)
	{
		$options = array(
		Fgsl_Form::DATA => $data,
		Fgsl_Form::ACTION => $action,
		Fgsl_Form::MODEL => $model
		);

		return new Fgsl_Form($options);
	}

	/**
	 * 
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#saveAction()
	 */
	public function saveAction()
	{
		if (isset($this->_post->Return))
		{
			$this->_forward('list');
		}

		$options = array();
		$options[Fgsl_Form_Edit::ACTION] = '';
		$options[Fgsl_Form_Edit::DATA] = $this->_getDataFromPost();
		$options[Fgsl_Form_Edit::MODEL] = $this->_model;

		$form = new Fgsl_Form_Edit($options);

		if (!$form->isValid($_POST))		
		{
			/*
			 * Requires a ErrorController with a method validAction()
			 */
			$this->_redirect('error/valid');
		}

		$fieldNames = $this->_model->getFieldNames();
		
		$data = array();
		$unlockedData = array();
		foreach ($fieldNames as $fieldName)
		{
			$unlockedData[$fieldName] = $this->_model->getCastValue($fieldName,$this->_post->$fieldName);
			if ($this->_model->isLocked($fieldName)) continue;
			$data[$fieldName] = $this->_model->getCastValue($fieldName,$this->_post->$fieldName);
		}

		$this->save($data,$unlockedData);

		$this->_forward('list');
	}

	/**
	 * Method to save records (insert or update)
	 * @see application/controllers/ICrudController#save()
	 */
	public function save(array $data, array $unlockedData)
	{
		try {
			if (isset($this->_post->Insert))			
			{
				$this->_model->insert($data);
			}
			else
			{				
				$fieldKey = $this->_model->getFieldKey();
				$this->_model->update($data,"$fieldKey = {$unlockedData[$fieldKey]}");				
			}
		}
		catch(Exception $e )
		{			
			return false;
		}
		return true;
	}

	/**
	 * Method to remove records
	 * @see application/controllers/ICrudController#removeAction()
	 */
	public function removeAction()
	{
		$key = $this->_getParam($this->_model->getFieldKey());

		$this->_model->delete("{$this->_model->getFieldKey()} = $key");

		$this->_forward('list');
	}

	/**
	 * Sets attribute $_fieldNames with fieldnames of model
	 * and configures path of alternative view object 
	 * (non-PHPdoc)
	 * @see Crud/Controller/Fgsl_Crud_Controller_Interface#_config()
	 */
	public function _config()
	{
		$this->_fieldNames = $this->_model->getFieldNames();

		$viewPath = APPLICATION_PATH."/views";
		if ($this->_useModules)
		{
			$viewPath = APPLICATION_PATH."/modules/{$this->getRequest()->getModuleName()}/views";
		}

		$this->_view->setBasePath($viewPath);		
	}

	/**
	 * Gets data sent by method HTTP POST 
	 * @return unknown_type
	 */
	protected function _getDataFromPost()
	{
		$data = Fgsl_Session_Namespace::get('data');
		if (!isset($data))
		{
			$data = array();
				
			foreach ($this->_fieldNames as $fieldName)
			{
				$data[$fieldName] = '';
			}
		}
		Fgsl_Session_Namespace::set('data',$null);
		return $data;
	}

	/**
	 * Return url to be used in hyperlinks
	 * @return unknown_type
	 */
	public function getUrl()
	{
		$url = $this->_basePath.'/'.$this->_controllerAction;
		if ($this->_useModules)
		{
			$url = $this->_basePath.'/'.$this->_moduleName.'/'.$this->_controllerAction;
		}
		
		return $url;
	}

	/**
	 * Configure items to be assigned to object view
	 * @return unknown_type
	 */
	public function configureViewAssign()
	{
		$this->_view->assign('title',$this->_view->escape($this->_title));
		$this->_view->assign('table',$this->_table);		
		$this->_view->assign('insertLink',$this->getUrl().'/insert');
		$this->_view->assign('listLink',$this->getUrl().'/list');
		$this->_view->assign('returnLink',$this->getUrl().'/list');
		$this->_view->assign('currentPage',$this->_currentPage);
		$this->_view->assign('lastPage',$this->_lastPage);
		$this->_view->assign('searchField',$this->_model->getSearchField());
		$this->_view->assign('labelSearchField',$this->_model->getFieldLabel($this->_model->getSearchField()));		
	}
}
?>