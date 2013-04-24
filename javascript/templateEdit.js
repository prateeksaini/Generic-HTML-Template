function deleteTemplate($id){
    if($id > 0 ){
   	 if(confirm("!!! Do you really want to delete this template")){
            $.post("../controller/deleteTemplate.php",{"id":$id});
            $("#searchTemplate").trigger("keyup");
        }
     return false;
    }else{
    	$("#template").html("");
    	$("#submitForm").css("display","none");
    }            
}
function editTemplate($id,$templateName){
    $("#divAddItem").html("");
    $("#accordion").html("");
    $("#divTemplateInsert").hide();
    
	$last_Index = $templateName.lastIndexOf("_");
	$value = $templateName.substr($last_Index+1);
	$value = putSpace($value);
	
    $("#templateName").val($value);
    
    getFieldType();
    getDataType();
    
             
    $.post("../controller/getTemplate.php",{"id":$id},function(data){
        $.each(data,function(i, $field){
            
            var $textBox = 'txtBox';
            var $txtArea = 'txtArea';
            var $checkBox = 'checkBox';
            var $radioButton = 'radioButton';
            var $selectOption = 'selectLabel';

            $first_Index = $field.indexOf("_");
        	$last_Index = $field.lastIndexOf("_");
        	$value = $field.substr($last_Index+1);
        	$value = putSpace($value);

        	$label = $field.substring($first_Index+1,$last_Index);
        	$label = putSpace($label);
        	
        	if($field.substr(0,6) == $textBox ){
        		addfield('1',$label,$value,"noDelete");
        	}
        	if($field.substr(0,7) == $txtArea ){
        		addfield('2',$label,$value,"noDelete");
        	}
        	if($field.substr(0,8) == $checkBox ){
        		addfield('3',$label,$value,"noDelete");
        	}                	
        	if($field.substr(0,11) == $selectOption ){
        		$label = $field.substr($last_Index+1);
        		$label = putSpace($label);
                addfield('4',$label,$value,"noDelete");
                var $tid = 0;
                if($("[class^=deleteBtn]:last").attr("id")){
    	            $tid = $("[class^=deleteBtn]:last").attr("id");
                }
                $sendData = "id="+$id+"&"+"colName="+$label;
                $.ajax({
                    async : false,
                    url : "../controller/getSelectOptions.php",
                    type : "post",
                    dataType : "json",
                    data : $sendData,
                    success : function(data){
                        $.each(data,function(i, field){
                        	$("#insertOption"+$tid).trigger("click",field);
                        });                                
                    }
                });
        	}
        	if($field.substr(0,11) == $radioButton ){
        		addfield('5',$label,$value,"noDelete");
        	}
        	if(i > 0){
        		$LastIdNotModify = i-1;
        	}
        	else{
        		$LastIdNotModify = 0;
        	}
        	
            });
        
        },"json");
    $("#divTempltWrapper").css("display","block");
    savetemplate("modify");            
}
function saveModifiedTemplate(){        	
	if ($('#templateMaster').valid()){
    	var $tempData = $('#templateMaster').serialize();
    	$tempData += "&modify=true";
    	$tempData += "&count="+($LastIdNotModify-1);

        $.ajax({ 
            type: "POST",
            url: '../controller/data.php',                  //the script to call to get data          
            //data: "",                        //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
            data: $tempData,
              success: function(data){
                  
              }
          });                
          alert("saving");            	
	}
	else{
		alert("errors");
	}
}
function getTemplate($id){
    if($id > 0 ){
    	$("#divTempltWrapper").hide();
    	$("#divTemplateInsert").show();
    	$("#template").html("");
    	$("#divListTemplate").html("");
    	$("#divListTemplate").append("<input type = \"text\" hidden = \"true\" name = \"templateId\" value = \""+$id+"\"/>");
        $.post("../controller/getTemplate.php",{"id":$id},function(data){
            $.each(data,function(i, field){                                                
                	$("#template").append(addInput(field,$id));
                });
            },"json");
        $("#submitForm").css("display","block");                
    }else{
    	$("#template").html("");
    	$("#submitForm").css("display","none");
    }
}
function addInput($field,$id = 0){
    var $textBox = 'txtBox';
    var $txtArea = 'txtArea';
    var $checkBox = 'checkBox';
    var $radioButton = 'radioButton';
    var $selectOption = 'selectLabel';
    var $input = '';
    //alert($field);
    if($field.substr(0,6) == $textBox ){
    	$last_Index = $field.lastIndexOf("_");
    	$input = "<p>";

    	$label = $field.substring(7,$last_Index);
    	$label = putSpace($label);
    	
    	$value = $field.substr($last_Index+1);
    	$value = putSpace($value);

    	            	
        $input += "<label>"+$label+"</label>";
        $input += "<input type = \"text\" value =\""+$value+"\" name = \""+ $field + "\" /> ";
        $input += "</p>";
        return $input;
    }
    if($field.substr(0,11) == $selectOption ){
    	$last_Index = $field.lastIndexOf("_");
    	$colName = $field.substr($last_Index+1);
    	
    	$input = "<label>"+$colName+"</label>";

    	$sendData = "id="+$id+"&"+"colName="+$colName;

    	$.ajax({
        	async : false,
        	url : "../controller/getSelectOptions.php",
        	type : "POST",
        	dataType :"json",
        	data : $sendData,
        	success : function(result){
            	$input += "<select name=\""+$field+"\">";
            	
                $.each(result,function(i, field){
                    
                	$input += "<option value=\""+field+"\">"+field+"<\/option>";
                });
                
                $input += "<\/select>";                                            	
        	}
        	});
    	return $input;
    }            
    if($field.substr(0,7) == $txtArea ){
        $last_Index = $field.lastIndexOf("_");
    	$input = "<p>";
    	
    	$label = $field.substring(8,$last_Index);
    	$label = putSpace($label);
    	            	
        $input += "<label>"+$label+"</label>";
        $input += "<textarea name = \""+ $field + "\">"+putSpace($field.substr($last_Index+1))+"<\/textarea>";
        $input += "</p>";
        return $input; 
    }
    if($field.substr(0,8) == $checkBox ){
        $last_Index = $field.lastIndexOf("_");
    	$input = "<p>";
    	$label = $field.substring(9,$last_Index);
    	$label = putSpace($label);
    	            	
        $input += "<label>"+$label+"</label>";
        $input += "<input type = \"checkbox\" name = \""
            		+ $field + "\""
            		+ "value=\"" + $field.substr($last_Index+1) + "\"/>";
        $input += "</p>";
        return $input; 
    }
    if($field.substr(0,11) == $radioButton ){
        $last_Index = $field.lastIndexOf("_");
    	$input = "<p>";
    	$label = $field.substr($last_Index+1);
    	$label = putSpace($label);
    	            	
        $input += "<label>"+$label+"</label>";
        $input += "<input type = \"radio\" name = \""
            		+ $field.substring(0,$last_Index) + "\""
            		+ "value=\"" + $field.substr($last_Index+1) + "\"/>";
        $input += "</p>";
        return $input; 
    }
}
function putSpace($value){
    while($value.indexOf("$") > 0){
    	$value = $value.replace("$"," ");
    }        
    return $value;
}