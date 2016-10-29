<?php

class Reply extends DbObject {
    // name of database table
    const REP_TABLE = 'replies';

    // database fields
	protected $id;
    protected $post;
    protected $location;
    protected $date_created;
    protected $user_id;
    protected $topic_id;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'post' => '',
            'location' => null,
            'user_id' => null,
            'topic_id' => null,
            'date_created' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->post = $args['post'];
        $this->location = $args['location'];
        $this->user_id = $args['user_id'];
        $this->topic_id = $args['topic_id'];
        $this->date_created = $args['date_created'];
    }

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'post' => $this->post,
            'location' => $this->location,
            'user_id' => $this->user_id,
            'topic_id' => $this->topic_id
          	//leave out date_created, auto
        );
		$error = $db->store($this, self::REP_TABLE, $db_properties);
        if($error) {
			return $error;
		}
		return null;
    }

	public function remove() {
		
		$db = Db::instance();
		$error = $db->delete($this, self::REP_TABLE);
		if($error) {
			return $error;
		}
		return null;
		
	}

	/*=======================Static functions========================*/
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::REP_TABLE);
        return $obj;
    }

	/*+===============================================+
	  |	loadByLocation() when maps API is figured out |
	  +===============================================+*/
	  

    public static function getAllReplies($t_id) {
        
		$query = sprintf("SELECT id FROM %s WHERE topic_id = %s ORDER BY date_created",
            self::REP_TABLE,
            $t_id
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

	public static function getRepliesById($u_id) {
		
		$query = sprintf("SELECT id FROM %s WHERE user_id = %s ORDER BY date_created",
            self::REP_TABLE,
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
