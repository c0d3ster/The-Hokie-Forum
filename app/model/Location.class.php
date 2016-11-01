<?php

class Location extends DbObject {
    // name of database table
    const LOC_TABLE = 'locations';

    // database fields
  protected $id;
	protected $topic_id;
	protected $title;
  protected $location;
	protected $description;

    // constructor
	public function __construct($args = array()) {
    $defaultArgs = array(
    	'id' => null,
      'topic_id' => null,
      'title' => '',
      'location' => null,
      'description' => ''
    );
    $args += $defaultArgs;

		$this->id = $args['id'];
    $this->topic_id = $args['topic_id'];
    $this->title = $args['title'];
    $this->location = $args['location'];
    $this->description = $args['description'];
  }

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
        	'id' => $this->id,
        	'topic_id' => $this->topic_id,
            'title' => $this->title,
            'location' => $this->location,
            'description' => $this->description
            );
		$error = $db->store($this, self::LOC_TABLE, $db_properties);
        if($error) {
			return $error;
		}
		return null;
    }

	public function remove() {
		
		$db = Db::instance();
		$error = $db->delete($this, self::LOC_TABLE);
		if($error) {
			return $error;
		}
		return null;
		
	}

	/*=======================Static functions========================*/
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::LOC_TABLE);
        return $obj;
    }

	/*+===============================================+
	  |	loadByLocation() when maps API is figured out |
	  +===============================================+*/
	  

    public static function getAllLocations() {
    	$query = sprintf("SELECT X(location) as Xcoord, Y(location) as Ycoord, topic_id, title FROM locations;");
        
        $db = Db::instance();
        
        $result = $db->lookup($query);
    	if(!mysql_num_rows($result))
            return null; 
        $locs = array ();
        while($row = mysql_fetch_assoc($result)) {

            array_push($locs,$row);
        }

        return $locs;
    }
    
    public static function getLocationsByTopic($t_id) {
    	
    	$query = sprintf("SELECT id FROM %s WHERE topic_id = %s;",
            self::LOC_TABLE,
            $t_id
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $locs = array();
            while($row = mysql_fetch_assoc($result)) {
            	$obj = self::loadById($row['id']);
            	array_push($locs, $obj);
            }
            return ($locs);
        }
    }
        
    
}
