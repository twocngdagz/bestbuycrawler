<?php
require_once(LIB_PATH.DS.'database.php');

class BBY extends DatabaseObject {
	protected static $table_name = 'bby';
	protected static $db_fields = array('id', 'sku', 'upc', 'bbysku');
	public $id;
	public $sku;
	public $upc;
	public $bbysku;
}
?>