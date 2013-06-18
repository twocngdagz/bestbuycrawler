<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>BestBuy.com</title>
    <style type="text/css" title="currentStyle">
    	@import "includes/media/css/jquery-ui-1.9.2.custom.css";
    	@import "includes/TableTools-2.1.4/media/css/TableTools.css";
		@import "includes/media/css/demo_table.css";
		@import "includes/media/css/demo_table_jui.css";		
	</style>	
    <script type="text/javascript" language="javascript" src="includes/media/js/jquery-1.8.3.js"></script>
	<script type="text/javascript" language="javascript" src="includes/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="includes/TableTools-2.1.4/media/js/TableTools.min.js"></script>
	<script type="text/javascript" language="javascript" src="includes/media/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" language="javascript" src="includes/media/js/bestbuy.js"></script>
	
  </head>
  <body>
  	<div>
  		<?php $max_file_size = 10485760; ?>
		<button type="button" id="opener">Reload Table</button>
		<!--  <button type="button" id="refresh">Upload List</button>  -->
		
		<form action="upload.php" enctype="multipart/form-data" method="POST">
		    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
		    <p><input type="file" name="filename" id="filename"/></p>
		    <input type="submit" name="upload" value="Upload" />
        </form>
		<span id="timer" style="width:50%;"></span>
	</div>
	<div id="progressbar"></div>
  	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	  <thead>
		  <tr>
		    <th>SKU</th>
		    <th>UPC</th>
		    <th>Main_SKU</th>
		    <th>Price</th>	
		    <th>Seller</th>
			<th>Shipping</th>
			<th>Match</th>
		  </tr>
	  </thead>
	  <tbody id = "crawler">
	  </tbody>
	</table>
	<div id="loadpage" style="position:absolute; 
	    left:0px; top:31px; background-color:white; 
	    layer-background-color:white; height:100%; 
	    width:100%;"> 
      <p align="center" style="font-size: medium;">
        <img src="includes/media/images/spinner.gif">
      </p>
	</div>
	
	<div id="dialog" title="BestBuy.com Crawler">
	    <p>This dialog will closed and automatically refresh after 1 sec.</p>
	</div>
  </body>
</html>

