<?php
/*
 **************************** Creation Log *******************************
File Name                   -  data.php
Project Name                -  Template Master
Description                 -  Conntroller class for inserting data,
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
ini_set("display_errors",1);
require_once '../model/DbConnectionModelAbstract.php';
//print_r($_POST);
//die;
class insertData extends model  {
    private $result;
    private $tempTableName;
    private $fields = array();
    private $fieldType = array();
    private $fieldValue = array();
    private $fieldDataType = array();
    private $fieldDataLength = array();
    private $templateID;
    
    public function __construct(){
        parent::__construct();
    }
    public static function setResult(){
        $temp = new insertData();
        $temp->setTemplate();
    }
    /* this function will insert table into template table and create  
     * fields with their labels and data type
     * */
    private function setTemplate(){
        if(!empty($_POST['templateName'])){
            $this->tempTableName = str_replace(" ", "$", $_POST['templateName']);            
        }
        if($_POST['modify'] == "false"){
            $this->db->insertTemplateName($this->tempTableName);
            $this->createFields();
        	$this->db->createTemplateTable($this->tempTableName,$this->fields,$this->fieldType,$this->fieldValue,$this->fieldDataType,$this->fieldDataLength);
        }else{            
            if(!empty($_POST['templateName'])){
                $this->templateID = $this->db->getTemplateId($this->tempTableName);                
            }
            $this->createFields();
            $oldColumnNames = $this->db->getTemplate($this->templateID);            
            unset($oldColumnNames[0]);
            unset($oldColumnNames[1]);
            unset($oldColumnNames[2]);
            
            $oldColumnNames = array_values($oldColumnNames);
            
            $this->db->alterTemplateTableColumns($this->tempTableName,$this->fields,$this->fieldType,$this->fieldValue,$this->fieldDataType,$this->fieldDataLength,$oldColumnNames);
            $this->db->alterTemplateTable($this->tempTableName,$this->fields,$this->fieldType,$this->fieldValue,$this->fieldDataType,$this->fieldDataLength,$oldColumnNames,$_POST['count']);
            //print("Modifying " . $templateID);
        }
        //echo json_encode($this->db->getFieldType());
    }
    private function setFields($field,$fieldType,$fieldValue,$fieldDataType = '',$fieldDataLength = ''){
    	$this->fields[] = $field;
    	$this->fieldType[] = $fieldType;
    	$this->fieldValue[] = $fieldValue;
    	$this->fieldDataType[] = $fieldDataType;
    	$this->fieldDataLength[] = $fieldDataLength;
    }
    private function createFields(){
        $tempCount = -1;
            foreach ($_POST as $key => $value){
            if(substr($key, 0, 6) == 'txtBox'){
                if($tempCount != substr($key, 11)){
                    $tempCount = substr($key, 11);
                    
                    $tempLabel = str_replace(" ", "$", $_POST['txtBoxLabel'.$tempCount]);
                    $tempValue = str_replace(" ", "$", $_POST['txtBoxValue'.$tempCount]);
                    $tempDataType = $_POST['dataTypeSelect'.$tempCount];
                    $tempLength = $_POST['maxLength'.$tempCount];
                    
                    $field = "txtBox_" . $tempLabel . "_" .$tempValue;
                    $this->setFields($field, 1, $tempValue,$tempDataType,$tempLength);
                }
            }
            if(substr($key, 0, 7) == 'txtArea'){
                if($tempCount != substr($key, 12)){
                    $tempCount = substr($key, 12);
                    
                    $tempLabel = str_replace(" ", "$", $_POST['txtAreaLabel'.$tempCount]);
                    $tempValue = str_replace(" ", "$", $_POST['txtAreaValue'.$tempCount]);
                    
                    $field = "txtArea_" . $tempLabel . "_" .$tempValue;
                    $this->setFields($field,2,$tempValue);
                }
            }
            if(substr($key, 0, 8) == 'checkBox'){
            	if($tempCount != substr($key, 13)){
            		$tempCount = substr($key, 13);
            		
            		$tempLabel = str_replace(" ", "$", $_POST['checkBoxLabel'.$tempCount]);
            		$tempValue = str_replace(" ", "$", $_POST['checkBoxValue'.$tempCount]);
            		
            		$field = "checkBox_" . $tempLabel . "_" .$tempValue;
            		$this->setFields($field,3,$tempValue);
            	}
            }
            if(substr($key, 0, 11) == 'radioButton'){
            	if($tempCount != substr($key, 16)){
            		$tempCount = substr($key, 16);
            		
            		$tempLabel = str_replace(" ", "$", $_POST['radioButtonLabel'.$tempCount]);
            		$tempValue = str_replace(" ", "$", $_POST['radioButtonValue'.$tempCount]);
            		
            		$field = "radioButton_" . $tempLabel . "_" .$tempValue;
            		$this->setFields($field,3,$tempValue);
            	}
            }
            if(substr($key, 0, 11) == 'selectLabel'){            	
            	if($tempCount != substr($key, 11)){
            		$tempCount = substr($key, 11);
            		
            		$options = array();
            		$tempOptions = array();
            		
            		foreach($_POST['selectOptionValue'.$tempCount] as $key => $value){
            			$options[] = $value;
            		}
            		$tempLabel = str_replace(" ", "$", $_POST['selectLabel'.$tempCount]);
            		
            		if($_POST['modify'] == "false"){
                		$this->db->insertIntoOptions($options);
                		$this->db->insertIntoTemplateSelectOptionMapping($options,$this->tempTableName,$tempLabel);
            		}
            		else{
            		    $optionID = $this->db->getSelectOptionID($this->templateID, $tempLabel);
            		                		    
            		    $this->db->alterOptions($options, $optionID, $this->templateID, $tempLabel);
            		    $countFormOption = count($options);
            		    $countDbOption = count($optionID);

            		    if($countFormOption > $countDbOption){
            		        for($i = $countDbOption; $i < $countFormOption ; $i++ ){
            		            $tempOptions[] = $options[$i];
            		        }
                    		$this->db->insertIntoOptions($tempOptions);
                    		$this->db->insertIntoTemplateSelectOptionMapping($tempOptions,$this->tempTableName,$tempLabel);
            		    }
            		}
            		$tempValue = '';
            		
            		$field = "selectLabel_" . $tempLabel;
            		$this->setFields($field,3,$tempValue);
            	}
            }            
        }        
    }
}
insertData::setResult();
?>