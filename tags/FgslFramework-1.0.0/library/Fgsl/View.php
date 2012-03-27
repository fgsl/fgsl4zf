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
 * @package    Fgsl_View
 * @subpackage Fgsl_View
 * @copyright  Copyright (c) 2011 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_View
 */
class Fgsl_View extends Zend_View implements Fgsl_View_Interface
{
	/**
	 * Content for tag <title>
	 * @var string
	 */
	protected $_title = null;
	/**
	 * hyperlink for inserting action
	 * @var string
	 */
	protected $_insertLink = null;
	/**
	 * hyperlink for listing action
	 * @var string
	 */
	protected $_listLink = null;
	/**
	 * hyperlink for return to listing action
	 * @var string
	 */
	protected $_returnLink = null;
	/**
	 * hyperlink for return to main menu
	 * @var string
	 */
	protected $_menuLink = null;
	/**
	 *
	 * @var integer
	 */
	protected $_currentPage = null;
	/**
	 *
	 * @var integer
	 */
	protected $_lastPage = null;
	/**
	 * field used in the search
	 * @var string
	 */
	protected $_searchField = null;
	/**
	 * label for field used in the search
	 * @var string
	 */
	protected $_labelSearchField = null;
	/**
	 *
	 * @var Zend_Form
	 */
	protected $_searchForm = null;
	
	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title;
	}

	public function getInsertLink()
	{
		return $this->_insertLink;
	}

	public function setInsertLink($insertLink)
	{
		$this->_insertLink = $insertLink;
	}

	public function getListLink($page = null)
	{
		return $this->_listLink . (is_null($page) ? ''  : '/page/' . $page);
	}

	public function setListLink($listLink)
	{
		$this->_listLink = $listLink;
	}

	public function getReturnLink()
	{
		return $this->_returnLink;
	}

	public function setReturnLink($returnLink)
	{
		$this->_returnLink = $returnLink;
	}

	public function getMenuLink()
	{
		return $this->_menuLink;
	}

	public function setMenuLink($menuLink)
	{
		$this->_menuLink = $menuLink;
	}

	public function getCurrentPage()
	{
		return $this->_currentPage;
	}


	public function setCurrentPage($currentPage)
	{
		$this->_currentPage = $currentPage;
	}

	public function getlastPage()
	{
		return $this->_lastPage;
	}

	public function setlastPage($lastPage)
	{
		$this->_lastPage = $lastPage;
	}

	public function getSearchField()
	{
		return $this->_searchField;
	}

	public function setSearchField($searchField)
	{
		$this->_searchField = $searchField;
	}

	public function getlabelSearchField()
	{
		return $this->_labelSearchField;
	}

	public function setlabelSearchField($labelSearchField)
	{
		$this->_labelSearchField = $labelSearchField;
	}

	public function getSearchForm()
	{
		return $this->_searchForm;
	}

	public function setSearchForm($searchForm)
	{
		$this->_searchForm = $searchForm;
	}
	
	/**
	 * creates HTML text for a table
	 * @param array $records
	 * @return void|string
	 */
	public function renderHTMLTableContent(array $records)
	{
		if (empty($records)) return;
	
		$htmlTable = '';
		$header = $records[0];
	
		$htmlTable = '<thead>';
		foreach($header as $label => $field)
		{
			$htmlTable .= '<th>';
			$htmlTable .= $label;
			$htmlTable .= '</th>';
		}
		$htmlTable .= '</thead>';
	
		foreach ($records as $record)
		{
			$htmlTable .= '<tr>';
			foreach($record as $field)
			{
				$htmlTable .= '<td>';
				$htmlTable .= $field;
				$htmlTable .= '</td>';
			}
			$htmlTable .= '</tr>';
		}
		return $htmlTable;
	}
	
	
}