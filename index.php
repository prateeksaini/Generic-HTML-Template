<html>
    <head>        
        <title>Master Template</title>
        <script src="javascript/jquery-1.9.1.min.js"></script>
        <script src="javascript/jquery.validate.min.js"></script>
        <script src="javascript/templateMaster.js"></script>
        <link rel = "stylesheet" href = "css/jquery.ui.theme.css" />
        <link rel = "stylesheet" href = "css/jquery.ui.accordion.css" />
        <link rel = "stylesheet" href = "css/style.css" />
        <script src="javascript/jquery.ui.core.js"></script>
        <script src="javascript/jquery.ui.widget.js"></script>
        <script src="javascript/jquery.ui.accordion.js"></script>
        <script>
        var $dataTypeOption;
        var $temp;
        $(function(){
            $("#divFieldSelect").ready(function(){
               $.getJSON("controller/getFieldType.php","",function(data){
                   $("#typeSelect").html("<option value=\"default\">Select...</option>");
                  $.each(data,function(i, field){
                	  $("#typeSelect").append("<option value=\""+field['id']+"\">"+field['name']+"</option>");
                  });
               });
            });
            $("#dataType").ready(function(){
                $.getJSON("controller/getDataType.php","",function(data){
                   $temp = "<select id = \"dataTypeSelect\">";
                   $temp += "<option value=\"default\">Select...</option>";                   
                   $.each(data,function(i, field){
                 	  $temp += "<option value=\""+field['id']+"\">"+field['name']+"</option>";
                   });
                   $temp += "<\/select>";
                   $dataTypeOption = $temp;
                });
             });            
            $("#addField").click(function(){
              var $choice = $("#typeSelect").val();
              addfield($choice);
              savetemplate("save");
            });
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
            $("#deleteField").click(function(){
                alert("sadfsadfsadf");
            });
            $( "#accordion" ).accordion();
        });
        </script>
    </head>
    <body>
    <div id = "mainWrapper">
    <div id="prevTemplate"><a href="./view/allTemplates.php">View All templates</a></div>    
    <form id = "templateMaster" name = "templateMaster" >
    <div id = "divTempltWrapper">
        <div id = "templateNameWrapper">
            <label>Template Name</label>
            <input type = "text" name="templateName" id = "templateName" /><br/>
    	</div>        
        <div id = "divFieldSelect">            
            <select id = "typeSelect"></select>
        </div>
        <input type = "Button" class = "ui-button ui-widget ui-state-default ui-button-text-only" id = "addField" value = "Add Field" />
        <div id="divAddItem"></div>
        <div id="accordion"></div>
        <div id="divSaveTemplate"></div>
    </div>
    </form>
    </div>
    </body>
    <div id="dataType"></div>
    <div id="dynamicScript"></div>
</html>