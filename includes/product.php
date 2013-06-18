<?php
require_once(LIB_PATH.DS.'database.php');

class Product extends DatabaseObject {
	protected static $table_name = "product";
	protected static $db_fields = array('id', 'SKU', 'UPC', 'Main_SKU', 'price', 'shipping', 'seller', 'MatchUPC');
	
	public $id;
	public $SKU;
	public $UPC;
	public $Main_SKU;
	public $price;
	public $seller;
	public $shipping;
	public $MatchUPC;
}
?>