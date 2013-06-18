//$(document).ready(function() {
//	load();
//});
$.fx.speeds._default = 100;
$(function() {
	load();
	var oTable;
    $( "#dialog" ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
	        "Reload": function() {
	        	ajaxFunction();
	            $(this).dialog("close");
	        }
        }
    });
    $( "#progressbar" ).hide();
    $("#filename").change(function () {
    	$.ajax({
    		url:"upload.php",
    		type: "POST",
    	})
    });
    
    $( "#opener" ).click(function() {
    	//setTimeout( function() {
    	//	$("#loadpage").hide();
    	//	}, 1000
    	//  );
        //$( "#dialog" ).dialog( "open" );
        //return false;
    	loop();
    });
    
    function loop() {
    	oTable.fnDestroy();
    	load();
    }
    
    $("#refresh").click(function() {
    	proxy();
    });
    
    function ajaxFunction(){
  	  var jqxhr = $.get("run.php", function() {
  	  });
  	  setTimeout( function() {
  		location.reload();
  		}, 1000
  	  );	  
    }
  function datatable() {
  	oTable = $('#example').dataTable({
  	    "bJQueryUI": true,
  	    "sPaginationType": "full_numbers",
  		"sDom": 'T<"clear">lfrtip',
  		"aaSorting": [],
  		"oTableTools": {
  	        "sSwfPath": "includes/TableTools-2.1.4/media/swf/copy_csv_xls_pdf.swf"
  	    }
  	});
  }

  function load() {
  	$.get(
  		"get.php",
  		function(data)
  		{
  			$("#crawler").html(data);
  		});
  }
  //setInterval(function(){loop()},10000);
  
  $(document).ajaxSend(function(event, request, settings) {
		$("#loadpage").show();
	});
	$(document).ajaxComplete(function(event, request, settings) {
		datatable();
		$("#loadpage").hide();
	});
  
});



