<?php
/**
 * fgslbooks
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. 
 * If you did not receive a copy of the license, you can get it at www.fgsl.eti.br. 
 *
 * @category   fgslbooks
 * @package    Model
 * @subpackage Category
 * @copyright  Copyright (c) 2010 Flávio Gomes da Silva Lisboa (http://www.fgsl.eti.br)
 * @license   New BSD License
 * @version    1.0.0rc4
 */

class Category extends Fgsl_Db_Table_Abstract
{
	protected $_name = 'categories';

	protected $dependentTables = array('Book');
	
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
	}
}