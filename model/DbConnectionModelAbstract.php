<?php
/*
 **************************** Creation Log *******************************
File Name                   -  DbConnectionModelAbstract.php
Project Name                -  Template Master
Description                 -  file for All database access,
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
require_once 'dbAccess.php';

abstract class model {
    protected $db = "";    

    function __construct() {
        $this->db = dbAccess::getInstance();
        $this->db->connection();        
    }
}
?>