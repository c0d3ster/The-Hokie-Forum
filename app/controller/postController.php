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
					$this->processAddTopic($this->currUser);
				}
				break;

			case 'processEdit':
				$topicID = $_GET['tid'];
				$t = Topic::loadById($topicID);
				if($this->currUser->get('user_id') == $t->get('user_id')) {
					$this->processEditTopic($t);
				}
				break;

			case 'processDelete':
				$topicID = $_GET['tid'];
				$t = Topic::loadById($topicID);
				if($this->currUser->get('user_id') == $t->get('user_id') || $admin) {
					$this->processDeleteTopic($t);
				}
				break;

			/////cases for reply control processing/////

			case 'processAddReply':
				if($this->currUser) {
					$this->processAddReply($this->currUser);
				}
				break;

			case 'processEditReply':
				$replyID = $_GET['rid'];
				$r = Reply::loadById($replyID);
				if($this->currUser->get('user_id') == $r->get('user_id')) {
					$this->processEditReply($r);
				}
				break;

			case 'processDeleteReply':
				$replyID = $_GET['rid'];
				$r = Reply::loadById($replyID);
				if($this->currUser->get('id') == $r->get('user_id') || $admin) {
					$this->processDeleteReply($r);
				}
				break;

      // redirect to home page if all else fails
      default:
        header('Location: '.BASE_URL);
        exit();
		}
	}


	public function addTopic() {
		$pageName = 'add Topic';

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addtopic.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
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
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addtopic.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	
	/**For AJAX, does not reload page, use exit()! **/
	public function processAddReply() {
	
		$newReply = new Reply(array(
			'post' => $_POST('post'),
			'location' => $_POST('location'),
			'u_id' => $this->currUser->get('id'),
			't_id' => $_POST('topic_id')
		));
		
		$this->processInsert($newReply, 'reply');
		exit();
		
	}
	
	/**For AJAX, does not reload page, use exit()! **/
	/* No editing locations yet */
	public function processEditReply($editReply) {
		$editReply->set('post',$_POST['post']);
		$editReply->set('u_id',$_POST['u_id']);
		$editReply->set('t_id',$_POST['t_id']);
		
		$this->processInsert($editReply, 'reply');
		exit();
	}
	
	/**Not sure if using AJAX or PHP for adding topic **/
	/**Assuming AJAX now, probably will be PHP though**/
	public function processAddTopic() {
		$newTopic = new Topic(array(
			'title' = $_POST['title'],
			'post' = $_POST['post'],
			'location' = $_POST['location'],
			'user_id' = $this->currUser->get('id')
		));
		
		$this->processInsert($newTopic, 'topic');
		exit();
	}
	
	/* No editing locations yet*/
	public function processEditTopic($editTopic) {
		$editTopic->set('title',$_POST['title']);
		$editTopic->set('post',$_POST['post']);
		$editTopic->set('title',$_POST['title']);
		
		$this->processInsert($editTopic);
		exit();
	} 
	
	
	/**need to associate topic/reply locations with locations
		table **/
	public function processInsert($obj, $type) {
		
		if (!$obj->get('id')) {
			//if has no id, new insert, allow locations
			if ($obj->get('location')) {
				$newLoc = new Location(array(
					'title' => $_POST['loctitle'],
					'description' => $_POST['locdescription'],
					'location' => $obj->get('location')
				));
				if ($type == 'topic')
					$newLoc->set('topic_id',$obj->get('id'));
				else if ($type == 'reply')
					$newLoc->set('topic_id',$obj->get('topic_id'));
				$newLoc->save();
			}
		}
		$error = $obj->save();
		if ($error) {
			$_SESSION['err'] = $error;
			return false;
		}
		return true;
	}


	public function processDelete($id) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS)
			or die ('Error: Could not connect to MySql database');
		mysql_select_db(DB_DATABASE);

		$q = "DELETE FROM product WHERE id=$id";
		mysql_query($q);
		header('Location: '.BASE_URL.'/myProducts/');
	}


}
