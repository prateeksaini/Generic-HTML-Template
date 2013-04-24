<?php
/*
 **************************** Creation Log *******************************
File Name                   -  dbAccess.php
Project Name                -  Template Master
Description                 -  Class file for Database Access,
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
class dbAccess{
    private $db_user = 'root';
    private $db_pass = 'root';
    private $db_name= "templateMasters";
    private $db_host = "localhost";
    private static $instance;
    private $db;
    private $connection;

    public static function getInstance(){
        if (is_null(dbAccess::$instance)) {
            self::$instance = new dbAccess();                                
        }
        return self::$instance;
    }
    public function connection(){
        try
        {
            $this->connection = new PDO( "mysql:host={$this->db_host};dbname={$this->db_name}", $this->db_user, $this->db_pass);
         } catch ( Exception $e ) {
            exit( "Error connecting to database: " . $e->getMessage() );
         }
    }
    public function getFieldType(){        
        $statement = $this->connection->prepare( "SELECT * FROM fields" );
        $statement->execute();
        $userRow = $statement->fetchAll(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
        return $userRow;
    }
    public function getDataType(){
    	$statement = $this->connection->prepare( "SELECT * FROM datatype" );
    	$statement->execute();
    	$userRow = $statement->fetchAll(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
    	return $userRow;
    }
    public function getTemplateList($name){
    	$search = '%'.$name.'%';
    	$search = $this->connection->quote($search, PDO::PARAM_STR);
    	$statement = $this->connection->query("SELECT * FROM templates WHERE name like($search) AND status = 'A' ");
    	
//     	$statement = $this->connection->prepare( "SELECT * FROM templates WHERE name like(':labName%') " );
//     	$statement->bindParam(":labName",$name,PDO::PARAM_STR);
//     	$statement->execute();
    	$userRow = $statement->fetchAll(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
    	return $userRow;    	
    }
    public function deleteTemplate($id){
    	
    	$id = $this->connection->quote($id, PDO::PARAM_INT);
    	$statement = $this->connection->query("UPDATE templates SET status = 'D' WHERE id = $id ");
    	 
    }
    public function getTemplate($id){

    	$tablename = $this->getTemplateName($id);
//     	print($tablename);
//     	die;
    	$statement = $this->connection->prepare( "DESC $tablename" );
    	//$statement->bindValue( ':tabname', $tablename, PDO::PARAM_STR );
    	$statement->execute();
    	$userRow = $statement->fetchAll(PDO::FETCH_COLUMN);//fetch( PDO::FETCH_ASSOC );    	
    	return  $userRow;
    }
    public function insertTemplateName($tempName){
        
	    $tabName = "fields_template_".$tempName;        
	
	    $str = "INSERT INTO templates (name) VALUES (:tabname)";
	    $statement = $this->connection->prepare($str);
	    $statement->bindValue( ':tabname', $tabName, PDO::PARAM_STR );
	    
	    $statement->execute();
    }
    
    public function getTemplateName($id){
    	
    	$statement = $this->connection->prepare( "SELECT name FROM templates where id = :id" );
    	$statement->bindValue( ':id', $id, PDO::PARAM_INT );
    	$statement->execute();
    	$userRow = $statement->fetch(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
    	return $userRow['name'];
    }
    public function getTemplateId($name){

        if(!strstr($name, "fields_template_")){
            $name = "fields_template_".$name;
        }       
        
    	$statement = $this->connection->prepare( "SELECT id FROM templates where name = :name" );
    	$statement->bindValue( ':name', $name, PDO::PARAM_STR );
    	$statement->execute();
    	$userRow = $statement->fetch(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
    	return $userRow['id'];
    }
    public function createTemplateTable($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength){
    	
    	$tabName = "fields_template_".$tempName;
    	$str =  "create table $tabName (".
    			"`id` int(9) PRIMARY KEY AUTO_INCREMENT COMMENT 'Auto generated ID',".
    			"`user_id` int(9) DEFAULT '1' COMMENT 'Auto generated ID',".
    			"`status` enum('A','D') DEFAULT 'A' COMMENT 'status about user entry',";
    	
    	$tempQuery = $this->setFieldsAndDataType($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength);    	
    	$query = implode($tempQuery, ',');    	    	
    	$str .= $query . ")";
//     	print($str);
//     	die;
    	
    	$statement = $this->connection->prepare($str);
    	$statement->execute();    	
    }
    public function alterTemplateTableColumns($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength,$oldFields){
    	
    	$tabName = "fields_template_".$tempName;
    	$str =  "ALTER TABLE $tabName CHANGE ";
    	$tempAlterQuery = array();
    	
    	$tempQuery = $this->setFieldsAndDataType($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength);
    	
    	$colCount = count($oldFields);
    	for($i = 0 ; $i < $colCount ; $i++){
    	    $tempAlterQuery[] = $str . $oldFields[$i] . " " . $tempQuery[$i];   
    	}
    	
        foreach ($tempAlterQuery as $key => $value){
     	    $this->connection->query($value);
     	}
    }
    public function alterTemplateTable($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength,$oldFields,$count){

        $tabName = "fields_template_".$tempName;
    	$str =  "ALTER TABLE $tabName ADD ";
    	$tempAlterQuery = array();
    	
    	$tempQuery = $this->setFieldsAndDataType($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength);    	
    	$colCount = count($fields);
   	    	
    	for($i = $count ; $i < $colCount ; $i++){
    	    $tempAlterQuery[] = $str . $tempQuery[$i];   
    	}
//    	print_r($tempAlterQuery);
//    	die;
        foreach ($tempAlterQuery as $key => $value){
     	    $this->connection->query($value);
     	}
    }    
    private function setFieldsAndDataType($tempName,$fields,$fieldType,$fieldValue,$dataTmpType,$maxLength){
        
        $tempQuery = array();
    	$colCount = count($fieldType);
    	$dataType = '';
    	$charSet = '';
    	
    	for($i = 0 ; $i < $colCount ; $i++){
    		switch($fieldType[$i]){
    			case 1 :    			    
    			    if($dataTmpType[$i] == 1){
    			        $dataType =  "int($maxLength[$i])";
    			    }
    			    else if($dataTmpType[$i] == 2){
    			        $dataType =  "varchar($maxLength[$i])";
    			        $charSet = " CHARACTER SET utf8 COLLATE utf8_unicode_ci ";
    			    }
    			    else if($dataTmpType[$i] == 3){
    			        $dataType =  "float($maxLength[$i])";
    			    }
    			    else{
    			        $dataType =  "varchar(50)";
    			        $charSet = " CHARACTER SET utf8 COLLATE utf8_unicode_ci ";
    			    }
    				
      				$tempQuery[] = $fields[$i] ." ". $dataType . $charSet .
    					" DEFAULT '$fieldValue[$i]' ";
    			break;
    			case 2 : 
    				$dataType = "text";
      				$tempQuery[] = $fields[$i] ." ". $dataType . " CHARACTER SET utf8 COLLATE utf8_unicode_ci ";    			
    			break;
    			case 3 :
    				$dataType =  "varchar(20)";
    				$tempQuery[] = $fields[$i] ." ". $dataType . " CHARACTER SET utf8 COLLATE utf8_unicode_ci " .
    						" DEFAULT 'false' ";
    			break;    			
    		}
    	}
    	return $tempQuery;
    }
    public function insertIntoTemplateTable($tempName, $fields, $fieldValue){
    	 
    	$tabName = $tempName;
    	$str =  "INSERT INTO $tabName ";   	

    	$tempFields = implode($fields, ',');
    	$tempValues = implode($fieldValue, "','");

    	$str .= "(".$tempFields .") "; 
    	$str .= "VALUES ('";
    	$str .= $tempValues;
    	$str .= "')";
//     	    	print($str);
//     	    	die;
    	 
    	$statement = $this->connection->prepare($str);
    	$statement->execute();
    }
     public function insertIntoOptions($options){

     	$str =  "INSERT INTO selectoption ";
     	$str .= "(name) ";
     	$str .= "VALUES ('";
     	$tempValues = implode($options, "'),('");     	
     	$str .= $tempValues;
     	$str .= "')";
     	
//      	print($str);
//      	die;
     	
     	$statement = $this->connection->prepare($str);
     	$statement->execute();  
     	   	
     }
     public function alterOptions($options,$optionID,$tempTableID,$colName){

     	$str =  "UPDATE selectoption SET name = ";
     	$updateQuery = array();
     	$optionCount = count($optionID);
     	
     	for($i = 0 ; $i < $optionCount ; $i++  ){
     	    $optionID[$i]['selectoption_id'] = $this->connection->quote($optionID[$i]['selectoption_id'], PDO::PARAM_INT);
     	    $options[$i] = $this->connection->quote($options[$i], PDO::PARAM_STR);
     	    
     	    $updateQuery[] = $str . $options[$i] . " where id = "  . $optionID[$i]['selectoption_id'];
     	}
//      	print_r($updateQuery);
//      	die;
     	foreach ($updateQuery as $key => $value){
     	    $this->connection->query($value);
     	}
     }     
     public function insertIntoTemplateSelectOptionMapping($options,$tempTableName,$colname){

     	$tabName = "fields_template_".$tempTableName;
     	$templateId = $this->getTemplateId($tabName);

     	$str =  "INSERT INTO template_select_option_mapping ";
     	$str .= "(template_id,selectoption_id,col_name) ";
     	$str .= "VALUES ";
     	$tmpStr = array();
     	
     	foreach($options as $key => $value){
     		$tmpStr[] .= "('$templateId','".$this->getOptionID($value)."','".$colname."')";
     	}
     	$str .= implode($tmpStr, ',');
//      	     	print($str);
//      	     	die;
     	$statement = $this->connection->prepare($str);
     	$statement->execute();
     }
     public function getOptionID($name){
     	$statement = $this->connection->prepare( "SELECT id FROM selectoption where name = :name" );
     	$statement->bindValue( ':name', $name, PDO::PARAM_STR );
     	$statement->execute();
     	$userRow = $statement->fetch(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
     	return $userRow['id'];
     }
     public function getSelectOption($id,$colName){

        $userRow = $this->getSelectOptionID($id,$colName);
     	$options = array();
     	
     	foreach($userRow as $key => $value){
     		$statement = $this->connection->prepare( "SELECT name FROM selectoption where id = :id" );
     		$statement->bindValue( ':id', $value['selectoption_id'], PDO::PARAM_INT);
     		$statement->execute();
     		$result = $statement->fetch(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
     		$options[] = $result['name']; 
     	}

     	return $options;
     }
     public function getSelectOptionID($id,$colName){
        $statement = $this->connection->prepare( "SELECT selectoption_id FROM template_select_option_mapping where template_id = :id AND col_name = :colname" );
     	$statement->bindValue( ':id', $id, PDO::PARAM_INT);
     	$statement->bindValue( ':colname', $colName, PDO::PARAM_STR);     	
     	$statement->execute();
     	return $statement->fetchAll(PDO::FETCH_ASSOC);//fetch( PDO::FETCH_ASSOC );
     }
 
}
// $temp = dbAccess::getInstance();
// $temp->connection();
// echo '<pre>';
// print_r( $temp->getFieldType() );

// $sUserName = "";
// if(isset($_REQUEST['username'])) {
//     $sUserName = $_REQUEST['username'];
// }


// $sUserName = mysql_real_escape_string($sUserName);
// $statement = $db->prepare( "SELECT * FROM users WHERE username=:user" );
// $statement->bindValue( ':user', $sUserName, PDO::PARAM_STR );
// $statement->execute();
// $userRow = $statement->fetch( PDO::FETCH_OBJ );
// echo '<pre>';
// print_r( $userRow );
?>