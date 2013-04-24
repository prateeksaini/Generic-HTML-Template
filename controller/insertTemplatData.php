<?php
/*
 **************************** Creation Log *******************************
File Name                   -  insertTemplateData.php
Project Name                -  Template Master
Description                 -  Class file for inserting into Template,
academic information,
professional information
Version                     -  1.0
Created by                  -  Prateek Saini
Created on                  -  April 15, 2013
***************************** Update Log ********************************
Sr.NO.		Version		Updated by           Updated on          Description
-------------------------------------------------------------------------
*************************************************************************
*/
ini_set ( "display_errors", 1 );
require_once '../model/DbConnectionModelAbstract.php';
// print_r($_POST);
// die;
class insertTemplateData extends model {
	private $result;
	private $tempTableName;
	
	private $fields = array ();
	private $fieldValue = array ();
	
	public function __construct() {
		parent::__construct ();
	}
	public static function setResult() {
		$temp = new insertTemplateData ();
		$temp->setData ();
	}
	private function setData() {
		if(!empty($_POST['templateId'])){
			$this->tempTableName = $this->db->getTemplateName($_POST['templateId']);
		}
		foreach ( $_POST as $key => $value ) {
			
			if (substr ( $key, 0, 6 ) == 'txtBox') {
				
					$this->setFields ( $key, $value );					
			}
			if (substr ( $key, 0, 7 ) == 'txtArea') {
				
					$this->setFields ( $key, $value );
			}
			if (substr ( $key, 0, 8 ) == 'checkBox') {

					$this->setFields ( $key, "true" );
			}
			if (substr ( $key, 0, 11 ) == 'radioButton') {

					$this->setFields ( $key ."_". $value, "true" );
			}
			if(substr($key, 0, 11) == 'selectLabel'){
			    $this->setFields ( $key, $value );
			}
		}
		$this->db->insertIntoTemplateTable( $this->tempTableName, $this->fields, $this->fieldValue);
	}
	private function setFields($field, $fieldValue) {
		$this->fields [] = $field;
		$this->fieldValue [] = $fieldValue;
	}
}
insertTemplateData::setResult ();
?>