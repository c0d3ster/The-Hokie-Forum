<?php

class Topic extends DbObject {
    // name of database table
    const TOP_TABLE = 'topics';

    // database fields
	protected $id;
    protected $title;
    protected $post;
    protected $location;
    protected $date_created;
    protected $user_id;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'title' => '',
            'post' => '',
            'location' => null,
            'user_id' => null,
            'date_created' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->post = $args['post'];
        $this->location = $args['location'];
        $this->user_id = $args['user_id'];
        $this->date_created = $args['date_created'];
    }   

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'title' => $this->title,
            'location' => $this->location,
            'post' => $this->post,
            'user_id' => $this->user_id
          	//leave out date_created, auto
            );
		$error = $db->store($this, self::TOP_TABLE, $db_properties);
        if($error) {
			return $error;
		}
		return null;
    }

	public function remove() {
		
		$db = Db::instance();
		$error = $db->delete($this, self::TOP_TABLE);
		if($error) {
			return $error;
		}
		return null;
		
	}

	/*=======================Static functions========================*/
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::TOP_TABLE);
        return $obj;
    }

	/*+===============================================+
	  |	loadByLocation() when maps API is figured out |
	  +===============================================+*/
	  

    public static function getAllTopics() {
        
		$query = sprintf("SELECT id FROM %s ORDER BY date_created DESC",
            self::TOP_TABLE
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
            	$obj = self::loadById($row['id']);
            	array_push($objects, $obj);
            }
            return ($objects);
        }
    }
    
    public static function getTopicsById($u_id) {
    	$query = sprintf("SELECT id FROM %s WHERE user_id = %s ORDER BY date_created DESC",
            self::TOP_TABLE,
            $u_id
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
            	$obj = self::loadById($row['id']);
            	array_push($objects, $obj);
            }
            return ($objects);
        }
    }
        
    
}
