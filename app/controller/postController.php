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

	public function console_log( $data ){
	  echo '<script>';
	  echo 'console.log('. json_encode( $data ) .')';
	  echo '</script>';
	}
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
				$this->console_log($r);
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
	
	/**For AJAX, does not reload page, use exit()! **/
	public function processAddReply() {
	
		$newReply = new Reply(array(
			'post' => $_POST['post'],
			'user_id' => $this->currUser->get('id'),
			'topic_id' => $_POST['topic_id']
		));
		
		$added = $this->processInsert($newReply, 'reply');
		$html = null;
		if ($added) {
			$user = User::loadById($added->get('user_id'));
			$uname = $user->get('username');
			$html = "<div class='reply'>
				<p class='editable'>".$added->get('post')."</p>
				<h5 class='reply-name'>".$uname."</h5>
				<img src='".IMAGES."/edititem.png' class='edit-item'>
				<img src='".IMAGES."/deleteitem.png' class='delete-item'>
				<input class='hidden-id' type='hidden' value='".$added->get('id')."'> 
			</div>";
		}
		echo $html;
		exit();
		
	}

	/**For AJAX, does not reload page, use exit()! **/
	/* No editing locations yet */
	public function processEditReply($editReply) {
		$editReply->set('post',$_POST['post']);
		
		$edited = $this->processInsert($editReply, 'reply');
		echo json_encode($edited);
		exit();
	}
	
	/**Not sure if using AJAX or PHP for adding topic **/
	/**Assuming AJAX now, probably will be PHP though**/
	public function processAddTopic() {
		$newTopic = new Topic(array(
			'title' => $_POST['title'],
			'post' => $_POST['post'],
			//'location' => $_POST['location'],
			'user_id' => $this->currUser->get('id')
		));
		
		$added = $this->processInsert($newTopic, 'topic');
		header('Location: '.BASE_URL.'/view/'.$added->get('id'));
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
			return $error;
		}
		return $obj;
	}


	public function processDeleteReply($r) {
		$r->remove();
		header('Location: '.BASE_URL.'/view/'.$r->get('topic_id'));
	}

	public function processDeleteTopic($t) {
		$t->remove();
		header('Location: '.BASE_URL.'/myActivity/');
	}


}
