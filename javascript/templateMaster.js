var $dataTypeOption;
var $temp;
var $maxElm;
var $LastIdNotModify;

function addfield($choice,$label = '',$value = '', $delete = ''){
    switch ($choice){
        case '1':
        	
        	$id = getLastid();

            $("#divAddItem").append("<div class=\"item\" id = \"div"+$id+"\">");    
	            createTextBox($id,"Text Box","txtBoxLabel","txtBoxValue",$label,$value);
	            addDeleteButton($id,$delete);
            $("#divAddItem").append("</div>");
            
            addValidate("txtBoxLabel"+$id);
            addValidate("txtBoxValue"+$id);
            
            addDataType($id);
            $("#accordion").append($("#div"+$id)).accordion('destroy').accordion({header : "> div > h3",active : false, collapsible : true});
        break;
        case '2':

        	$id = getLastid();
        	
            $("#divAddItem").append("<div class=\"item\" id = \"div"+$id+"\">");
            	createTextBox($id,"Text Area","txtAreaLabel","txtAreaValue",$label,$value);
            	addDeleteButton($id,$delete);
            $("#div"+$id).append("</div>");
                       

            $("#divAddItem").append("</div>");
            $("#accordion").append($("#div"+$id)).accordion('destroy').accordion({header : "> div > h3",active : false, collapsible : true});
            
            addValidate("txtAreaLabel"+$id);
            addValidate("txtAreaValue"+$id);
        break;                
        case '3':
        	
        	$id = getLastid();
        	
            $("#divAddItem").append("<div class=\"item\" id = \"div"+$id+"\">");
            	createTextBox($id,"Check Box","checkBoxLabel","checkBoxValue",$label,$value);
            	addDeleteButton($id,$delete);
            $("#div"+$id).append("</div>");
            
            $("#divAddItem").append("</div>");
            $("#accordion").append($("#div"+$id)).accordion('destroy').accordion({header : "> div > h3",active : false, collapsible : true});
            
            addValidate("checkBoxLabel"+$id);
            addValidate("checkBoxValue"+$id);
        break;
        case '4': 
        	
        	$id = getLastid();
        	
            $("#divAddItem").append("<div class=\"item\" id = \"div"+$id+"\">");
            $("#div"+$id).append("<h3>Drop Down</h3>");
            $("#div"+$id).append("<div>");
            $("#div"+$id+" > div").append("<label class=\"font\">Label</label>");                        
            $("#div"+$id+" > div").append("<input type = \"text\" id=\"selectLabel"+$id+"\" class=\"txtBox\" name=\"selectLabel"+$id+"\" value=\""+$label+"\" />");
            $("#div"+$id).append("</div>");
            
            addDeleteButton($id,$delete);
            
            $("#div"+$id+" > div").append("</br>");  
            $("#div"+$id+" > div").append("<label class=\"font\">Add Option</label>");
            $("#div"+$id+" > div").append("<input type = \"button\" id=\"insertOption"+$id+"\" value = \"Add option\" />");
            $("#divAddItem").append("</div>");
            $("#accordion").append($("#div"+$id)).accordion('destroy').accordion({header : "> div > h3",active : false, collapsible : true});
            
            addoption($id);
            addValidate("selectLabel"+$id);
        break;
        case '5':
        	
        	$id = getLastid();

            $("#divAddItem").append("<div class=\"item\" id = \"div"+$id+"\">");
            	createTextBox($id,"Radio button","radioButtonLabel","radioButtonValue",$label,$value);
            	addDeleteButton($id,$delete);
            $("#div"+$id).append("</div>");
            
            $("#divAddItem").append("</div>");
            $("#accordion").append($("#div"+$id)).accordion('destroy').accordion({header : "> div > h3",active : false, collapsible : true});
            addValidate("radioButtonLabel"+$id);
            addValidate("radioButtonValue"+$id);
        break;                    
        default: alert("no choice");
        break;
    }
}
function createTextBox($id, $heading, $labelName, $valueName,$label,$value){
	
    $("#div"+$id).append("<h3>"+$heading+"</h3>");
    $("#div"+$id).append("<div>");
    $("#div"+$id+" > div").append("<label class=\"font\">Field Label</label>");
    $("#div"+$id+" > div").append("<input type = \"text\" id=\""+$labelName+$id+"\" class=\"txtBox\" name=\""+$labelName+$id+"\" value=\""+$label+"\" />");
    $("#div"+$id+" > div").append("<label class=\"font\">Field value</label>");
    $("#div"+$id+" > div").append("<input type = \"text\" id=\""+$valueName+$id+"\" class=\"txtBox\" name=\""+$valueName+$id+"\" value=\""+$value+"\" />");
    $("#div"+$id).append("</div>");

}
function getLastIdNotModify(){
	var $id = 0;
	alert($("[class^=deleteBtn]:last").attr("id"));
    if($("[class^=deleteBtn]:last").attr("id")){
        $id = $("[class^=deleteBtn]:last").attr("id");
    }	
	return $id;	
}
function getLastid(){
    var $id = 0;
    if($("[class^=deleteBtn]:last").attr("id")){
        $id = $("[class^=deleteBtn]:last").attr("id");
        $id++;
    }	
	return $id;
}
function addDeleteButton($id,$type){
    $("#div"+$id + " > div").append("<input type = \"button\" value=\"Delete Field\" class=\"deleteBtn\" id = \""+$id+"\" />");
    if($type == "noDelete"){
    	$("#"+$id).hide();
    	return true;
    }
    addDeleteScript($id);
}
function addDeleteScript($id){
	$("#dynamicScript").append("<script>$(\"#"+$id+"\")"+
        	".click(function(){"+
        	"$(\"#div"+$id+"\").remove();"+
        	"});<\/script>");            
}
function addoption($id){    
	$("#dynamicScript").append("<script>$(\"#insertOption"+$id+"\")"+
        	".click(function($object,$value = 0){"+
        	"$(\"#div"+$id+" > div\").append(\"</br>\");"+
        	"$(\"#div"+$id+" > div\").append(\"<label class = 'font'>option</label>\");" +
        	"$(\"#div"+$id+" > div\").append(\"<input type = 'text' class = 'selectOptionValue txtBox' value=\"+$value+\" name='selectOptionValue"+$id+"[]'/>\");"+
        	"});<\/script>");
}
function addValidate($label){
	$("#dynamicScript").append("<script>$(\"#"+$label+"\")"+
        	".rules(\"add\","+
        	"{required: true, maxlength: 50,"+
        	"messages: {"+$label+":\"Provide Data\"} "+
        	"});"+
        	"$(\"#"+$label+"\")"+
        	".click(function(){"+
        	"$(\"#"+$label+"\").valid();"+
        	"});"+
        	"<\/script>");     	
}
function addDataType($id){
    $temp = $($dataTypeOption).clone();
    $("#div"+$id + " > div").append("</br>");
	$("#div"+$id + " > div").append($temp);
	$($temp).attr("id","dataTypeSelect"+$id);
	$($temp).attr("name","dataTypeSelect"+$id);
	addDataTypeScriptValidation($id);
	addDataTypeLength($id);
}
function addDataTypeLength($id){
	$("#div"+$id + " > div").append("<label class='font'>Max Length</label>");
	$("#div"+$id + " > div").append("<input type = \"text\" id=\"maxLength"+$id+"\" name=\"maxLength"+$id+"\" />");
	addMaxLengthValidate($id);
}
function addDataTypeScriptValidation($id){
	$("#dynamicScript").append("<script id=\"dataTypeScript"+$id+"\">"+			
			"$.validator.addMethod(\"dataTypeSelect"+$id+"\", function(value, element) {" +
					"return value != \"default\"" +
					"}, \"* Amount must be greater than zero\");"+
			"$(\"#dataTypeSelect"+$id+"\")"+
		        	".rules(\"add\","+
		        	"{required: true,\"dataTypeSelect"+$id+"\": true,"+
		        	"messages: {dataTypeSelect"+$id+":\"Please select your data type choice\"} "+
		        	"});"+
			"$(\"#dataTypeSelect"+$id+"\")"+
        	".on(" +
        	"\"click change\"," +
        	"function(){" +        	
        	"$(\"#dataTypeSelect"+$id+"\").valid();" +
        	"});"+
        	"<\/script>");     	
}
function addMaxLengthValidate($id){
	
			
	$("#dynamicScript").append("<script id=\"scriptMax"+$id+"\">" +
			"$.validator.addMethod(\"maxLength"+$id+"\", function(value, element) {" +
			"return this.optional(element) || /^[0-9]{1,2}$/.test(value)" +
			"}, \"* Max number of digits 2 (MAX 99)\");"+			
			
			"$(\"#maxLength"+$id+"\")"+
        	".rules(\"add\","+
        	"{required: true,\"maxLength"+$id+"\": true, "+
        	"messages: {} "+
        	"});"+
			"$(\"#maxLength"+$id+"\")"+
        	".on(" +
        	"\"click change\"," +
        	"function(){" +        	
        	"$(\"#maxLength"+$id+"\").valid();" +
        	"});"+
        	"<\/script>");     	
}
function insertTemplate(){
    if ($('#templateMaster').valid()) // check if form is valid
    {
        $tempData = $('#templateMaster').serialize();
        $tempData += "&modify="+false;
        $.ajax({ 
            type: "POST",
            url: 'controller/data.php',                  //the script to call to get data          
            //data: "",                        //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
            data: $tempData,
              success: function(data){
                  
              }
          });
    }
    else 
    {
        alert("errors");
    }
}
function savetemplate($choice = ''){
	if($choice == "save"){
    	$("#divSaveTemplate").html("");
	    $("#divSaveTemplate").append("<input type=\"button\" id = \"saveTemplate\" value=\"Save template\" onclick = insertTemplate() />");
	    $("#saveTemplate").css("display","block");		
	}
	else{
    	$("#divSaveTemplate").html("");
	    $("#divSaveTemplate").append("<input type=\"button\" id = \"saveModifiedTemplate\" onclick =\"saveModifiedTemplate()\" value=\"Modify template\" />");
	    $("#saveTemplate").css("display","block");		
	}
}
function getFieldType(){
    $.getJSON("../controller/getFieldType.php","",function(data){
        $("#typeSelect").html("<option value=\"default\">Select...</option>");
       $.each(data,function(i, field){
     	  $("#typeSelect").append("<option value=\""+field['id']+"\">"+field['name']+"</option>");
       });
    });	
}
function getDataType(){
    $.ajax({
        async : false,
        url :  "../controller/getDataType.php",
        type : "post",
        dataType : "json",
        success : function(data){
            $temp = "<select id = \"dataTypeSelect\">";
            $temp += "<option value=\"default\">Select...</option>";                   
            $.each(data,function(i, field){
          	  $temp += "<option value=\""+field['id']+"\">"+field['name']+"</option>";
            });
            $temp += "<\/select>";
            $dataTypeOption = $temp;                    
        }
    });	
}