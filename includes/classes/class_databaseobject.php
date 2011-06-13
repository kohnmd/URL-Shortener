<?php
require_once(INC_PATH.DS."classes".DS."class_mysqldatabase.php");

class DatabaseObject {

	protected static $table_name;
	
	public static function find_all() {
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
	}
	
	public static function find_by_id($id=0) {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id=".$database->trim_escape_value($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}
	
	private static function instantiate($record) {
		$class_name = get_called_class();
		$object = new $class_name;
		
		foreach($record as $attribute=>$value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
		// get_object_vars returns an associative array with all attributes
		// (incl. private ones!) as the keys and their current values as the value
		$object_vars = $this->attributes();
		// We don't care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $object_vars);
	}
	
	protected function attributes() {
		// return an array of attribute names and their values
		$attributes = array();
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	
	protected function sanitized_attributes() {
		global $database;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}
	
	public function save() {
		// A new record won't have an id yet.
		return isset($this->id) ? $this->update() : $this->create();
	}
	
	protected function create() {
		global $database;
		
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO " . static::$table_name ."(";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		
		if($database->query($sql)) {
			$this->id = $database->insert_id();
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	protected function update() {
	
	}
	
	public function delete() {
	
	}

}

?>