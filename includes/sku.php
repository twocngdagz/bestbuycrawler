<?php
require_once(LIB_PATH.DS.'database.php');

class SKU extends DatabaseObject {
	protected static $table_name = "skustocrawl";
	protected static $db_fields = array('productid', 'bbysku', 'upc');
	
	public $productid;
	public $bbysku;
	public $upc;
}
?>