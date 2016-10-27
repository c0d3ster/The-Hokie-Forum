<?php

class Db {

	private static $_instance = null;
    private $conn;

	private function __construct() {
		$host     = DB_HOST;
		$database = DB_DATABASE;
		$username = DB_USER;
		$password = DB_PASS;

		$conn = mysql_connect($host, $username, $password)
			or die ('Error: Could not connect to MySql database');

		mysql_select_db($database);
	}

	public static function instance() {
		if (self::$_instance === null) {
			self::$_instance = new Db();
		}
		return self::$_instance;
	}

	public function fetchById($id, $class_name, $db_table) {
		if ($id === null) {
			return null;
		}

		$query = sprintf("SELECT * FROM `%s` WHERE id = %s;",
				$db_table,
				$id
			     );
		$result = $this->lookup($query);

		if(!mysql_num_rows($result)) {
			return null;
		} else {
			$row = mysql_fetch_assoc($result);
			$obj = new $class_name($row);
			return $obj;
		}
	}

	public function store(&$obj, $db_table, $data)
	{
		//does item already exist?
		if($obj->getId() === null) {
			$query = $this->buildInsertQuery($db_table, $data);
			$error = $this->execute($query);
			if($error) {
				return $error;			
			}
			$obj->setId($this->getLastInsertID());
		} else {
			if($obj->getModified()) {
				$query = $this->buildUpdateQuery($db_table, $data, $obj->getId());
				$error = $this->execute($query);
				if($error) {
					return $error;			
				}
			}
		}
		$obj->setModified(false); // reset the flag
		return null;
	}
	
	public function delete($obj, $db_table) {
		
		$query = sprintf("DELETE FROM %s WHERE id = %s;", $db_table, $obj->get('id'));
		$error = $this->execute($query); 
		if ($error) {
			return $error;
		}
		return null;
	}

	// Formats a string for use in SQL queries.
	// Use this on ANY string that comes from external sources (i.e. the user).
	public function quoteString($s) {
		return "'" . mysql_real_escape_string($s) . "'";
	}

	// Formats a date (i.e. UNIX timestamp) for use in SQL queries.
	public function quoteDate($d) {
		return date("'Y-m-d H:i:s'", $d);
	}

	//Query the database for information
	public function lookup($query) {
		$result = mysql_query($query);
		if(!$result)
			die('Invalid query: ' . $query);
		return ($result);
	}

	//Execute operations like UPDATE or INSERT
	public function execute($query) {		
		$ex = mysql_query($query);
		if(!$ex) {
			return mysql_error();}
		return null;
	}

	//Build an INSERT query.  Mostly here to make things neater elsewhere.
	//$table  -> Name of the table to insert into
	//$fields -> List of attributes to populate
	//$values -> Values that will populate the new row
	//RETURN  -> A mysql insert query in the form of:
	//					 "INSERT INTO <table> (<fields>) VALUES (<values>)"
	//NOTE: This function DOES NOT actually EXECUTE the query, only gives a
	//			string to be used elsewhere.
	public function buildInsertQuery($table = '', $data = array()) {
		$fields = '';
		$values = '';

		foreach ($data as $field=>$value) {
			if($value !== null) { // skip unset fields
				$fields .= "`".$field . "`, ";
				$values .= $this->quoteString($value) . ", ";
			}
		}

		 // cut off the last ', ' for each
		$fields = substr($fields, 0, -2);
		$values = substr($values, 0, -2);

		$query = sprintf("INSERT INTO `%s` (%s) VALUES (%s);",
				$table,
				$fields,
				$values
			     );

		return ($query);
	}

	public function buildUpdateQuery($table = '', $data = array(), $id=0) {
		$all_null = true;
		$query = "UPDATE `" . $table . "` SET `";

		foreach ($data as $field => $value) {
			if($value === null) {
				$query .= $field . "` = NULL, `";
      } else {
				$query .= $field . "` = " . $this->quoteString($value) . ", `";
				$all_null = false;
			}
		}

		$query = substr($query, 0, -3); // cut off the last ', `'
		$query .= " WHERE id = '" . $id . "';";

		// only return a real query if there's something to update
		if($all_null)
			return '';
		else
			return ($query);
	}

	//Get the ID of the last row inserted into the database.  Useful for getting
	//the id of a new object inserted using AUTO_INCREMENT in the db.
	//RETURN -> The ID of the last inserted row
	public function getLastInsertID() {
		$query = "SELECT LAST_INSERT_ID() AS id";
		$result = mysql_query($query);
		if(!$result)
			die('Invalid query.');

		$row = mysql_fetch_assoc($result);
		return ($row['id']);
	}

}
