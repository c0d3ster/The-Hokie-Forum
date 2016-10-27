<?php

/*	
	DIAGRAM OF THREAD STRUCTURE/DEFINITIONS (tab width 4)

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

class Thread extends DbObject {
    // name of database table

    // database fields
	protected $topic;		/* One Topic object */
	protected $replies;		/* Array of Reply objects */
	protected $locations;	/* Array of location rows */
	protected $categories;	/* Array of category rows */

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
    
    public function deleteThread() {
    	/*TO DO*/
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
    
    public static function getThreadByTopic($t_id) {
    	
    	$thread = new Thread();
    	
    	$thread->topic = Topic::loadById($t_id);
    	$thread->replies = Reply::getAllReplies($t_id);
    	$thread->locations = getLocations($t_id);        
        $thread->categories = getCategories($t_id);
		
		return $thread;
    }

	public static function getThreadsByUsername($uname) {
		
		$threads = array();
		$user = User::loadByUsername($uname);
		if (!$user)
			return null;
		
		$user_id = $user->get('id');
		$topics = Topic::getTopicsByUsername($uname);
		$replies = Reply::getRepliesByUsername($uname);
		
		$t_ids = array();
		foreach($topics as $top) {
			array_push($t_ids, $top->get('id'));
			$th = getThreadByTopic($top->get('id'));
			array_push($threads, $th);
		}
		foreach($replies as $rep) {
			//is it already the user's topic?
			if (!in_array($rep->get('id'), $t_ids)) {
				array_push($t_ids, $rep->('topic_id'));
				$th = getThreadByTopic($rep->('topic_id'));
				array_push($threads, $th);
			}
		}
		return $threads;
	}
	
    
    
    
    /*=====================Private helper functions=================*/
   
    
    private function getLocations($id) {
    	$query = sprintf("SELECT * FROM 'locations' WHERE topic_id = %s;",
        	$id);
        	
        $result = $db->lookup($query);
    	if(!mysql_num_rows($result))
            return null;
            
        $locs = array();
        while($row = mysql_fetch_assoc($result)) {
        	array_push($locs, $row);
        }
        return $locs;
    }
	
	private function getCategories($id) {
    	$query = sprintf("SELECT * FROM 'categories' WHERE topic_id = %s;",
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
