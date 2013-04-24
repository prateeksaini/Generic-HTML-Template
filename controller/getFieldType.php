<?php
/*
 **************************** Creation Log *******************************
File Name                   -  getFieldType.php
Project Name                -  Template Master
Description                 -  Class file for fetching Field Type,
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
//ini_set("display_errors",1);
require_once '../model/DbConnectionModelAbstract.php';
class getFieldType extends model  {
    private $result;
    public function __construct(){
        parent::__construct();
    }    
    public static function getResult(){
        $temp = new getFieldType();
        $temp->getType();        
    }
    private function getType(){
        echo json_encode($this->db->getFieldType());
    }
}
getFieldType::getResult();