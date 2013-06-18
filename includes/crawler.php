<?php
require_once(LIB_PATH.DS.'database.php');

class Crawler extends DatabaseObject {
	protected static $table_name = "crawler";
	protected static $db_fields = array('id', 'name', 'start', 'end');
	
	public $id;
	public $name;
	public $start;
	public $end;
}
?>