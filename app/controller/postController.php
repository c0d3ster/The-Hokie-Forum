<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];


// instantiate a PostController and route it
$pc = new PostController();
$pc->route($action);



class PostController {

	public $currUser = 0;
	public $admin = 0;

	// route us to the appropriate class method for this action
	public function route($action) {
		session_start();
		if(isset($_SESSION['user'])) {
			$this->currUser = User::loadByUsername($_SESSION['user']);
			$this->admin = $this->currUser->get('admin');
		}

		switch($action) {
			/////cases for topic control/////

			case 'threadView':
				$topicID = $_GET['tid'];
				$this->threadView($topicID);
				break;			

			case 'addTopic':
				if($this->currUser) { //user needs to be logged in to post topics
					$this->addTopic();
				}
				break;

			/////cases for topic control processing//////

			case 'processAdd':
				if($this->currUser) {
					$this->processAddTopic();
				}
				break;

			case 'processEdit':
				$topicID = $_GET['tid'];
				$t = Topic::loadById($topicID);
				if($this->currUser->get('id') == $t->get('user_id')) {
					$this->processEditTopic($t);
				}
				break;

			case 'processDelete':
				$topicID = $_GET['tid'];
				$t = Topic::loadById($topicID);
				if($this->currUser->get('id') == $t->get('user_id') || $this->admin) {
					$this->processDeleteTopic($t);
				}
				break;

			/////cases for reply control processing/////

			case 'processAddReply':
				if($this->currUser) {
					$this->processAddReply();
				}
				break;

			case 'processEditReply':
				$replyID = $_GET['rid'];
				$r = Reply::loadById($replyID);
				if($this->currUser->get('id') == $r->get('user_id')) {
					$this->processEditReply($r);
				}
				break;

			case 'processDeleteReply':
				$replyID = $_GET['rid'];
				$r = Reply::loadById($replyID);
				if($this->currUser->get('id') == $r->get('user_id') || $this->admin) {
					$this->processDeleteReply($r);
				}
				break;
			
			case 'populateExplore':
				$this->populateExplore();				
				break;
				
			case 'populateMap':
				$topic_id = $_GET['tid'];
				$this->populateMap($topic_id);
				break;

			case 'switchFavorite':
				if (!$this->currUser)
					echo json_encode(array('added'=>2));
					break;
				$user_id = $this->currUser->get('id');
				$topic_id = $_POST['tid'];
				$this->switchFavorite($user_id, $topic_id);
				break;
      // redirect to home page if all else fails
      default:
        header('Location: '.BASE_URL);
        exit();
		}
	}

	public function threadView($topicID) {
		//to be continued...
		$pageName = 'Thread View';
		$thread = Thread::getThreadByTopic($topicID);
		
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/threadview.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function addTopic() {
		$pageName = 'Add Topic';

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addtopic.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	
	public function populateExplore() {
		
		$locs = Location::getAllLocations();
		echo json_encode($locs);
		exit();	
		
	}
	
	public function populateMap($t_id) {
		$locs = Location::getLocationsByTopic($t_id);
		echo json_encode($locs);
		exit();
	}
	
	/**For AJAX, does not reload page, use exit()! **/
	public function processAddReply() {
	
		$newReply = new Reply(array(
			'post' => $_POST['post'],
			'user_id' => $this->currUser->get('id'),
			'topic_id' => $_POST['topic_id'],
			'location' => $_POST['loc']
		));
		
		$added = $this->processInsert($newReply, 'Reply');
		if(!$added) {
			echo json_encode(array('err'=>'Error'));
			exit();
		}
		$user = User::loadById($added->get('user_id'));
		$uname = $user->get('username');
		$html = "<div class='reply'>
			<p class='editable'>".$added->get('post')."</p>
			<h5 class='reply-name'>".$uname."</h5>
			<label class='topic-time'>".$added->get('date_created')."</label>
			<img src='".IMAGES."/edititem.png' class='edit-item'>
			<img src='".IMAGES."/deleteitem.png' class='delete-item'>
			<input class='hidden-id' type='hidden' value='".$added->get('id')."'> 
		</div>";

		echo $html;
		exit();
		
	}

	/**For AJAX, does not reload page, use exit()! **/
	/* No editing locations yet */
	public function processEditReply($editReply) {
		$editReply->set('post',$_POST['post']);
				
		$edited = $this->processInsert($editReply, 'Reply');
		if(!$edited) {
			echo json_encode(array('err'=>'Error'));
			exit();
		}
		$return = array('post' => $edited->get('post'));

		echo json_encode($return);
		exit();
	}
	
	/**Not sure if using AJAX or PHP for adding topic **/
	/**Assuming AJAX now, probably will be PHP though**/
	public function processAddTopic() {
	

		$loclat = $_POST['loclat'];
		$loclong = $_POST['loclong'];
		$point = $loclat." ".$loclong;
		$locstr = "GeomFromText('POINT(".$loclat." ".$loclong.")',0)";
		$newTopic = new Topic(array(
			'title' => $_POST['title'],
			'post' => $_POST['post'],
			'location' => $locstr,
			'user_id' => $this->currUser->get('id')
		));
		
		$added = $this->processInsert($newTopic, 'Topic');
		if(!$added) {
			header('Location: '.BASE_URL.'/addTopic');
		}
		else {
			header('Location: '.BASE_URL.'/view/'.$added->get('id'));
		}
		exit();
	}
	
	/* No editing locations yet*/
	public function processEditTopic($editTopic) {
		$editTopic->set('title',$_POST['title']);
		$editTopic->set('post',$_POST['post']);
		
		$edited = $this->processInsert($editTopic, 'Topic');
		if(!$edited) {
			echo json_encode(array('err'=>'Error'));
			exit();
		}
		$return = array('title' => $edited->get('title'),
						'post' => $edited->get('post')
						);
		echo json_encode($return);
		exit();
		
	} 
	
	
	/**need to associate topic/reply locations with locations
		table **/
	public function processInsert($obj, $type) {
		
		$isNew = !$obj->get('id');
		$hasLoc = $obj->get('location');	//location before insertion (GEOMFROMTEXT())
		$error = $obj->save();
		if ($type == 'Topic') {
			$objFull = Topic::loadById($obj->get('id'));
		}
		else if ($type == 'Reply') {
			$objFull = Reply::loadById($obj->get('id'));
		}
		if ($isNew) {
			//if has no id, new insert, allow locations
			if ($hasLoc) {
				$newLoc = new Location(array(
					'title' => $_POST['loctitle'],
					'location' => $hasLoc
				)); 
				if ($type == 'Topic') {
					$newLoc->set('topic_id',$objFull->get('id'));
				}
				else if ($type == 'Reply') {
					$newLoc->set('topic_id',$objFull->get('topic_id'));
				}
				$newLoc->save();
			}
		}
		if ($error) {
			$_SESSION['err'] = $error;
			return null;
		}
		return $objFull;
	}


	public function processDeleteReply($r) {
		$r->remove();
		header('Location: '.BASE_URL.'/view/'.$r->get('topic_id'));
	}

	public function processDeleteTopic($t) {
		$t->remove();
		header('Location: '.BASE_URL.'/myActivity/');
	}

	public function switchFavorite($user_id, $topic_id) {
		
		$added = array('added' => 0); //create data array to send back, initialized added to 0
		$fav = new Favorite(array( //create Favorite object to check against favorite table
			'user_id' => $user_id,
			'topic_id' => $topic_id
			));
		$found = Favorite::isFavorite($fav);
		//search for favorite with $user_id and $topic_id
		if($found) { //if found set data.added to 0, and remove favorite from table
			$fav->remove();
		}
		else { //if not found set data.added to 1, and add favorite to table
			$added['added'] = 1;
			$fav->save();
		}
		
		echo json_encode($added); //return the data
		exit();
	}
}
