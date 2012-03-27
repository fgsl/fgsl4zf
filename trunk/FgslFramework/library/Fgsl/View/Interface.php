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
 * @subpackage Fgsl_View_Interface
 * @copyright  Copyright (c) 2011 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0
 */

/**
 * Fgsl_View_Interface
 */
interface Fgsl_View_Interface extends Zend_View_Interface
{
	public function getTitle();
	public function getInsertLink();
	public function getListLink();
	public function getReturnLink();
	public function getMenuLink();
	public function getCurrentPage();
	public function getlastPage();
	public function getSearchField();
	public function getlabelSearchField();
	public function getSearchForm();	
	
	public function setTitle($title);
	public function setInsertLink($insertLink);
	public function setListLink($listLink);
	public function setReturnLink($returnLink);
	public function setMenuLink($menuLink);
	public function setCurrentPage($currentPage);
	public function setlastPage($lastPage);
	public function setSearchField($searchField);
	public function setlabelSearchField($labelSearchField);
	public function setSearchForm($searchForm);	
	
}