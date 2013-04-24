<?php
/*
 **************************** Creation Log *******************************
File Name                   -  deleteTemplate.php
Project Name                -  Template Master
Description                 -  Class file for deleting template,
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

class deleteTemplate extends model  {

    public function __construct(){
        parent::__construct();
    }    
    public static function delete(){
        $temp = new deleteTemplate();
        $temp->delTemplate();
    }
    private function delTemplate(){
        $this->db->deleteTemplate($_POST['id']);
    }
}
deleteTemplate::delete();