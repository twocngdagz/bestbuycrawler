<?php
require_once(LIB_PATH.DS.'database.php');

class Proxy extends DatabaseObject {
	protected static $table_name = "proxy";
	protected static $db_fields = array('id', 'ip', 'port');
	
	public $id;
	public $ip;
	public $port;
}
?>