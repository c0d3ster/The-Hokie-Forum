<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];



// instantiate a SiteController and route it
$pc = new SiteController();
$pc->route($action);

class SiteController {

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
			case 'exploreMap':
				$this->exploreMap();
				break;

			case 'exploreBubbles':
				$this->exploreBubbles();
				break;

  		case 'recentTopics':
				$this->recentTopics();
				break;

			case 'hotTopics':
				$this->hotTopics();
				break;

			case 'myActivity':
				if($this->currUser) {
					$this->myActivity();
				}
				break;
			
			case 'myFavorites':
				if($this->currUser) {
					$this->myFavorites();
				}
				break;

			case 'processLogin':
				$username = $_POST['un'];
				$password = $_POST['pw'];
				$this->processLogin($username, $password);
				break;

			case 'processSignup':
				$user = $_POST['u'];
				$pass = $_POST['p'];
				$mail = $_POST['m'];
				$this->processSignup($user, $pass, $mail);
				break;

			case 'profile':
				if($this->currUser) {
					$this->profile();
				}
				break;

			case 'updatePassword':
				$user = $this->currUser;
				$this->updatePassword($user);
				break;

			case 'logout':
				session_unset();
				session_destroy(); // destroy session 			
				setcookie("PHPSESSID","",time()-3600,"/"); // delete session cookie 
				header('Location: '.BASE_URL);
				break;

			// redirect to home page if all else fails
			default:
  			header('Location: '.BASE_URL);
  			exit();
		}
	}
  public function exploreMap() {
		$pageName = 'Explore Map';
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/explore.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

  public function exploreBubbles() {
		$pageName = 'Explore Bubbles';
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/explore.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

  public function recentTopics() {
    $pageName = 'Recent Topics';
		$topics = Topic::getAllTopics();
		$threads = array();
        foreach($topics as $topic) {
            $th = Thread::getThreadByTopic($topic->get('id'));
            array_push($threads, $th);
        }

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/topics.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }
  
  public function hotTopics() {
		$pageName = 'Hot Topics';
		$topics = Topic::getHotTopics();
		$threads = array();
        foreach($topics as $topic) {
            $th = Thread::getThreadByTopic($topic->get('id'));
            array_push($threads, $th);
        }
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/topics.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

  public function myActivity() {
		$pageName = 'My Activity';

		//this should return an array of topics which you have partipated in
		$activities = Thread::getThreadsByUsername($_SESSION['user']);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/myactivity.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }
  
  public function myFavorites() {
  		$pageName = 'My Favorites';
  		
  		$favorites = Favorite::getFavoritesByUserId($this->currUser->get('id'));
  		$activities = array();
  		foreach($favorites as $fav) {
  			$act = Topic::loadById($fav->get('topic_id'));
  			array_push($activities, $act);
  		}
  		
  		
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/myactivity.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

	public function processLogin($u, $p) {
		$user = User::loadByUsernameAndPassword($u, $p);
		if($user == null) {
			$status = array('status' => 0);
			echo json_encode($status); //error invalid password
			exit();
		}
		else { 
			$_SESSION['user'] = $u;
			$status = array('status' => 1);
			echo json_encode($status); //successful login return js to refresh base login page
			exit();
		}	
	}

	public function processSignup($u, $p, $m) {
		if(User::loadByUsername($u)) {
			$status = array('status' => 0); 
			echo json_encode($status); //error username is already taken
			exit();
		}
		else {
			$newUser = new User(
				array(
					'username' => $u,
					'password' => $p,
					'email' => $m
				)
			);	
			$newUser->save();

			$status = array('status' => 1);
			echo json_encode($status);
			exit();
		}
	}

	public function profile() {
		$pageName = 'Profile';
		
		$userId = $this->currUser->get('id');
		
		$replyCount = count(Reply::getRepliesById($userId));
		$topicArray = Topic::getTopicsById($userId);
		$topicCount = count($topicArray);
		$favCount = count(Favorite::getFavoritesByUserId($userId));
		$yourImpact = $replyCount + $topicCount;
		
		if($topicArray) {
			foreach ($topicArray as $topic) {
				$count = count(Reply::getAllReplies($topic->get('id')));
				$yourImpact += $count;
			}
		}
		
		
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/profile.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	/* update the user's password in the database */
	public function updatePassword($user) {
		// user's current password
		$currentPassword = $user->get('password');

		// old password user entered in form
		$oldPass = $_POST['oldPass'];

		// if they match, update to new password
		if ($oldPass == $currentPassword) {
			$newPass = $_POST['newPass']; 
			$user->set('password', $newPass);
			$user->save();
		}
	}
}
