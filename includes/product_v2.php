<?php
require_once(LIB_PATH.DS.'database.php');

class Product_v2 extends DatabaseObject {
	protected static $table_name = "product_v2";
	protected static $db_fields = array('id', 'SKU', 'UPC', 'Main_SKU', 'price', 'shipping', 'seller', 'MatchUPC', 'Status', 'Time');
	
	public $id;
	public $SKU;
	public $UPC;
	public $Main_SKU;
	public $price;
	public $seller;
	public $shipping;
	public $MatchUPC;
	public $Status;
	public $Time;
}
?>