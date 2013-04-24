<html>
    <head>        
        <title>All Template</title>
        <script src="../javascript/jquery-1.9.1.min.js"></script>
        <script src="../javascript/jquery.validate.min.js"></script>
        <script src="../javascript/templateMaster.js"></script>
        <script src="../javascript/templateEdit.js"></script>
        <link rel = "stylesheet" href = "../css/jquery.ui.theme.css" />
        <link rel = "stylesheet" href = "../css/jquery.ui.accordion.css" />
        <link rel = "stylesheet" href = "../css/style.css" />
        <script src="../javascript/jquery.ui.core.js"></script>
        <script src="../javascript/jquery.ui.widget.js"></script>
        <script src="../javascript/jquery.ui.accordion.js"></script>        
        <script> 
        $(function(){
            $("#submit").click(function(){
                $.ajax({ 
                    type: "POST",
                    url: '../controller/insertTemplatData.php',                  //the script to call to get data          
                    //data: "",                        //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
                    data: $('#templateInsert').serialize(),
                      success: function(data){
                          
                      }
                  });
            });
            $("#searchTemplate").on(
                "keyup",
                function(){
                    $("#displayTemplates").html("");
                    $.getJSON("../controller/getTemplateList.php",{"name":$("#searchTemplate").val()},function(data){
                    	$("#displayTemplates").append("<tr><th>S. no</th><th>Template Name</th><th>Options</th></tr>");
                       $.each(data,function(i, field){
                     	  $("#displayTemplates").append("<tr><td>"+(i+1)+"</td><td class=\"classListTemplate\" onclick=\"getTemplate("+field['id']+")\">"+putSpace(field['name'].substr(field['name'].lastIndexOf("_")+1))+"</td>"+
                             	  "<td><input id=\"edit"+field['id']+"\" onClick=\"editTemplate("+field['id']+",'"+field['name']+"')\" type=\"button\" value=\"Edit\"/></td>"+
                              	 "<td><input id=\"delete"+field['id']+"\" onClick=\"deleteTemplate("+field['id']+")\" type=\"button\" value=\"Delete\"/></td></tr>");
                       });
                    });
                });
            $("[class~='classListTemplate']").on(
                    "hover",
                    function(){
                        alert("i am looking");
            });
            $("#templateMaster").validate();
            $("#addField").click(function(){
                var $choice = $("#typeSelect").val();
                addfield($choice);                
              }); 
            $( "#accordion" ).accordion();
            $("#templateMaster").validate({
                rules: {
               	 templateName: {
                   	 required: true,
                     maxlength: 50
               	 }
                },
                messages: {
               	 templateName: "Provide template Name"
                }
           });
        });
        </script>
        <style type="text/css">
			#divTempltWrapper {
				display: none;
			}
			#templateNameWrapper {
				display: none;
			}
        </style>
        </head>
	<body>
	<div id = "mainWrapper">
	<label>Search Template</label>
	<input type = "text" id = "searchTemplate" placeholder = "Template Name" />
	<table id = "displayTemplates">
	</table>
	<div id="divTemplateInsert">
	<form id="templateInsert" >
		<div id = "divListTemplate"></div>
		<div id = "templateWrapper">
			<div id = "template"></div>
		</div>
		<div id = "submitForm">
			<input type="button" id = "submit" value = "Insert Record" />
		</div>
	</form>
	</div>
    <div id = "divTempltWrapper">
    <form id = "templateMaster" name = "templateMaster" >
        <div id = "templateNameWrapper">
            <label>Template Name</label>
            <input type = "text" name="templateName" id = "templateName" /><br/>
    	</div>
    	<div id = "divFieldSelect">
            <select id = "typeSelect"></select>
        </div>
        <input type = "Button" id = "addField" value = "Add Field" />
        <div id="accordion"></div>
        <div id="divAddItem"></div>
        <div id="divSaveTemplate"></div>
    </form>
    <div id="dataType"></div>
    <div id="dynamicScript"></div>
    </div>
    </div>
	</body>
</html>