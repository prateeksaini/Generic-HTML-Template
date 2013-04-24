<?php
/*
 **************************** Creation Log *******************************
File Name                   -  getSelectOptions.php
Project Name                -  Template Master
Description                 -  Class file for fetching Select Options,
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

class getSelectOptions extends model  {
    private $result;
    public function __construct(){
        parent::__construct();
    }    
    public static function getResult(){
        $temp = new getSelectOptions();
        $temp->getList();
    }
    private function getList(){
        echo json_encode($this->db->getSelectOption($_POST['id'],$_POST['colName']));
    }
}
getSelectOptions::getResult();