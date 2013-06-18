


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
	var ip;
	var port;
	$(document).ready(function(){
		$.getJSON(
			"proxycrawler.php",
			{
			    format: "json"
		  	},
			function(data)
			{
		  		for(var i=0; i<data.length;i++)
				{
			    	$("#ip").html(data[i]["ip"]).find(":hidden").remove();
			    	$("#port").html(data[i]["port"])
			    	ip = $("#ip").text();
			    	port = $("#port").text();
			    	$("#proxy").append('<tr><td>'+ip+'</td><td>'+port+'</td></tr>');
			    	scrape(ip, port);
				}
			});
	});
	function scrape(ip, port){
		$.post( 'proxycrawler.php', 
			{'ipaddress':ip, 'port': port}, 
			function(e){ console.log('Success'); });
	}
</script>
</head>
<body>
	<div>
		<table id='proxy' cellpadding="0" cellspacing="0" border="0">
  	</table>
	</div>
  	<div id="ip"></div>
  	<div id="port"></div>
  	
</body>
</html>