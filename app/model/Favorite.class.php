<?php

class Favorite extends DbObject {
    // name of database table
    const FAV_TABLE = 'favorites';

    // database fields
	protected $id;
    protected $user_id;
    protected $topic_id;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'user_id' => null,
            'topic_id' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->user_id = $args['user_id'];
        $this->topic_id = $args['topic_id'];
    }

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'user_id' => $this->user_id,
            'topic_id' => $this->topic_id
          	//leave out date_created, auto
        );
		$error = $db->store($this, self::FAV_TABLE, $db_properties);
        if($error) {
			return $error;
		}
		return null;
    }

	public function remove() {
	
		$db = Db::instance();
		$error = $db->delete($this, self::FAV_TABLE);
		if($error) {
			return $error;
		}
		return null;
	
	}

	/*=======================Static functions========================*/
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::FAV_TABLE);
        return $obj;
    }

	public static function getFavoritesByUserId($u_id) {
		//should we use username from $_SESSION instead?
		$query = sprintf("SELECT * FROM %s WHERE user_id = %s",
            self::FAV_TABLE,
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

	public static function isFavorite($favorite) {
		$fav = self::getFavoritesByTopicId($favorite->get('topic_id'));
		foreach($fav as $f) {
			if($f['user_id'] == $favorite->get('user_id'))
				return self::loadById($f['id']);
		}
		return null;
	}
	
	public static function getFavoritesByTopicId($t_id) {
		
		$query = sprintf("SELECT * FROM %s WHERE topic_id = %s",
            self::FAV_TABLE,
            $t_id
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();		//don't need an array of objects here. only used for counting favorites
            while($row = mysql_fetch_assoc($result)) {
            	array_push($objects, $row);
            }
            return ($objects);
        }
	}
        
    
}
