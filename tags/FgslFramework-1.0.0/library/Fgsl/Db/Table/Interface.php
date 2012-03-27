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
 * Fgsl_Db_Table_Interface
 */
interface Fgsl_Db_Table_Interface
{
	public function getKeyValue(array $data);
	public function getTypeElement($fieldName);
	public function getFieldLabel($fieldName);
	public function getSelectOptions($fieldName, $where);
	public function getCastValue($fieldName,$value);
	public function getFieldKey();
	public function getFieldNames();
	public function getSearchField();
	public function getOrderField();
	public function isLocked($fieldName);
	public function getCustomSelect($where,$order,$limit);
	public function fetchAllAsArray($select);	
}