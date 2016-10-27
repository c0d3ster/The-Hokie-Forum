<?php

/*	
	DIAGRAM OF THREAD STRUCTURE/DEFINITIONS

 	+-----------------------This is a 'THREAD'----------------------+	
 	|																|
 	|	+-------------------This is a 'TOPIC'-------------------+	|
 	|	| ==This is the 'TITLE' of the topic					|	|
 	|	|												 		|	|
 	|	| ==This is the 'POST'...Lorem ipsum dolor sit amet,  	|	|
 	|	|	consectetur adipiscing elit. In sodales congue   	|	|
 	|	|	ligula, ut suscipit nunc interdum sed.           	|	|
 	|	+-------------------------------------------------------+	|
 	|																|
 	|		+-------------This is a 'REPLY'-------------------+	|
 	|		| ==This is also the 'POST'...						|	|
 	|		| Lorem ipsum dolor sit amet, consectetur adipiscing|	|
 	|		| elit. In sodales congue ligula, ut suscipit nunc  |	|
 	|		| interdum sed. Aliquam et mauris nec. 	   			|	|
 	|		+---------------------------------------------------+	|
 	|																|
 	|		+-------------This is another 'REPLY'-------------+	|
 	|		| ==This is also the 'POST'...						|	|
 	|		| Lorem ipsum dolor sit amet, consectetur adipiscing|	|
 	|		| elit. In sodales congue ligula, ut suscipit nunc  |	|
 	|		| interdum sed. Aliquam et mauris nec. 	   			|	|
 	|		+---------------------------------------------------+	|
 	|																|
 	+---------------------------------------------------------------+	:)
 
 */

class Thread {
    // name of database table

    // database fields
	protected $topic;
	protected $replies;
	protected $locations;
	protected $categories;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'topic' => null,
            'replies' => null,
            'locations' => null,
            'categories' => null
            );

        $args += $defaultArgs;
		
        $this->topic = $args['topic'];
        $this->replies = $args['replies'];
        $this->locations = $args['locations'];
        $this->categories = $args['categories'];
    }

	/*=======================Static functions========================*/
    public static function loadById($id, $db_table) {
        $db = Db::instance();
        $row = $db->fetchById($id, $db_table);
        return $row;
    }

	/*+===============================================+
	  |	loadByLocation() when maps API is figured out |
	  +===============================================+*/
	  

    public static function getAllTopics($limit=null) {
        
		$query = sprintf("SELECT * FROM %s ORDER BY date_created",
            self::TOP_TABLE
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
               array_push($objects, $row);
            }
            return ($objects);
        }
    }
    
    public static function getThreadByTopic($t_id) {
    	
    	$thread = new Thread();
    	
    	$thread->topic = Topic::loadById($t_id);
    	$thread->replies = Reply::getAllReplies($t_id);
    	        
        $thread = array(
        	'id' = $result['id'];
        	'title' = $result['title'];
	        'post' = $result['post'];
   	     	'date_created' = $result['date_created'];
   	    	'user_id' = $result['user_id'];
		    'replies' = $this->getReplies();
   	     	'locations' = $this->getLocations();
			'categories' = $this->getCategories();
		);
		
		return $thread;
        
    }
    
    /*=====================Private helper functions=================*/
   
    
    private function getLocations() {
    	$query = sprintf("SELECT * FROM %s WHERE topic_id = %s;",
        	self::LOC_TABLE,
        	$this->topic->get('id'));
        	
        $result = $db->lookup($query);
    	if(!mysql_num_rows($result))
            return null;
            
        $locs = array();
        while($row = mysql_fetch_assoc($result)) {
        	array_push($locs, $row);
        }
        return $locs;
    }
	
	private function getCategories() {
    	$query = sprintf("SELECT * FROM %s WHERE topic_id = %s;",
        	self::CAT_TABLE,
        	$this->id);
        	
        $result = $db->lookup($query);
    	if(!mysql_num_rows($result))
            return null;
            
        $cats = array();
        while($row = mysql_fetch_assoc($result)) {
        	array_push($cats, $row);
        }
        return $cats;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
