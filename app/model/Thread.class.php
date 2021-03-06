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

    /*=====================Private helper functions=================*/
   
	
    private function getCategories($id) {
    	$query = sprintf("SELECT * FROM categories WHERE topic_id = %s;",
        	$id);
        $db = Db::instance();
        $result = $db->lookup($query);
    	if(!mysql_num_rows($result))
            return null;
            
        $cats = array();
        while($row = mysql_fetch_assoc($result)) {
        	array_push($cats, $row);
        }
        return $cats;
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
    	$thread->locations = Location::getLocationsByTopic($t_id);        
        $thread->categories = self::getCategories($t_id);
		
		return $thread;
    }
	
	/* Actually returns an array of Topic objects... better name?*/
	public static function getThreadsByUsername($uname) {
		
		$user = User::loadByUsername($uname);
		if (!$user)
			return null;
		
		$user_id = $user->get('id');

		$topics = Topic::getTopicsById($user_id);
        if(!$topics) //if topics returns null set it to an empty array
            $topics = array();
		$replies = Reply::getRepliesById($user_id);
		
        if($replies) {
    		foreach($replies as $rep) {
    			//is it already the user's topic?
    			$top = Topic::loadById($rep->get('topic_id'), 'topics');
    			if (!in_array($top, $topics)) {
    				array_push($topics, $top);
    			}
    		}
        }
		return $topics;
	}  
}
