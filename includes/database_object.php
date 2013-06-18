<?php
require_once(LIB_PATH.DS."config.php");

class DatabaseObject {
	public static function find_all() {
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
	}
	
	public static function find_all_limit($offset, $limit) {
		return static::find_by_sql("SELECT * FROM ".static::$table_name. " LIMIT " . $offset . "," . $limit);
	}
	
	public static function find_by_id($id=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_by_where($field = 'id', $value=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE ". $field . " Like ". "'" . $value . "'". " LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function transaction_find_by_where($field = 'id', $value=0) {
		global $database;
		$database->begin();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE ". $field . " Like ". "'" . $value . "'". " LIMIT 1 FOR UPDATE");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_all_by_where($field = 'id', $value=0) {
		return static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE ". $field . " Like ". "'" . $value . "'");
	}
	
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}
	
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
public static function count_all_where($field = 'id', $value=0) {
		global $database;
		$sql = "SELECT COUNT(id) FROM ".static::$table_name . " WHERE " . $field . " Like ". "'" . $value . "'";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	private static function instantiate($record) {
		$class_name = get_called_class();
		$object = new $class_name;
		foreach ($record as $attribute=>$value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
		return array_key_exists($attribute, $this->attributes());
	}
	
	protected function attributes() {
		$attributes = array();
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	
	public function sanitized_attributes() {
		global $database;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}
	
	public function save() {
		return isset($this->id) ? $this->update() : $this->create();
	}
	
	public function transaction_save() {
		return isset($this->id) ? $this->transaction_update() : $this->transaction_create();
	}
	
	public function create() {
		global $database;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)) {
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	public function transaction_create() {
		global $database;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)) {
			$this->id = $database->insert_id();
			$database->commit();
			return true;
		} else {
			$database->rollback();
			return false;
		}
	}
	
	public function transaction_update() {
		global $database;
		
		$attributes = $this->sanitized_attributes();
		$attributs_pairs = array();
		foreach($attributes as $key => $value) {
			$attributes_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attributes_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$database->query($sql);
		if ($database->affected_rows() == 1) {
			$database->commit();
			return true;
		} else {
			$database->rollback();
			return false;
		}
	}
	
	public function update() {
		global $database;
		
		$attributes = $this->sanitized_attributes();
		$attributs_pairs = array();
		foreach($attributes as $key => $value) {
			$attributes_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attributes_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	public function delete() {
		global $database;
		$sql = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

	public static function deleteAll() {
		global $database;
		$sql = "DELETE FROM ".static::$table_name;
		$database->query($sql);
		return ($database->affected_rows() >= 0) ? true : false;
	}
}
?>