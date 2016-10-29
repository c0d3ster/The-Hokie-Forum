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
				if($this->currUser->get('user_id') == $r->get('user_id') || $admin) {
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



	public function processDelete($id) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS)
			or die ('Error: Could not connect to MySql database');
		mysql_select_db(DB_DATABASE);

		$q = "DELETE FROM product WHERE id=$id";
		mysql_query($q);
		header('Location: '.BASE_URL.'/myProducts/');
	}


}
