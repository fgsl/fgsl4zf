<?php
/** 
 * Object clonning snippet
 * @author Fla¡vio Gomes da Silva Lisboa 
 * @package snippets 
 * @version 0.1
 */

require_once('snippet-class.php');

// only PHP 5
// original
$sampleOfAObject = new SampleOfAClass();

// clone
$cloneOfAObject = clone $sampleOfAObject;

?>