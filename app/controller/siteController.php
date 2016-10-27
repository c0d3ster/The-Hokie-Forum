<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$pc = new SiteController();
$pc->route($action);

class SiteController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'explore':
				$this->explore();
				break;

			case 'processLogin':
				$username = $_POST['un'];
				$password = $_POST['pw'];
				$this->processLogin($username, $password);
				break;

			case 'checkUser':
				$username = $_POST['un'];
				$this->checkUsername($username);
				break;

			case 'processSignup':
				$user = $_POST['u'];
				$pass = $_POST['p'];
				$first = $_POST['f'];
				$last = $_POST['l'];
				$mail = $_POST['m'];
				$this->processSignup($user, $pass, $first, $last, $mail);
				break;

			case 'logout':
				if(isset($_SESSION['user'])) {
					session_unset();
					session_destroy(); // destroy session 
				} 					
				setcookie("PHPSESSID","",time()-3600,"/"); // delete session cookie 
				header('Location: '.BASE_URL);
				break;

			// redirect to home page if all else fails
      default:
        header('Location: '.BASE_URL);
        exit();
		}
	}

  //returns true if username is available false if it is taken
	public function checkUsername($username) {
		$user = User::loadByUsername($username);
		if($user == null) {
			// user doesn't exist; username is available
			return true;
		}
		else {
			return false;
		}
	}

  public function explore() {
		$pageName = 'Explore';
		include_once SYSTEM_PATH.'/view/header.tpl';
 		if(!isset($_SESSION['user'])) {
			include_once SYSTEM_PATH.'/view/intro.tpl';
		}
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
			session_start();
			$_SESSION['user'] = $u;
			if($u == 'Admin') {
				$status = array('status' => 2);
				echo json_encode($status); //return to admin page
				exit();
			}
			$status = array('status' => 1);
			echo json_encode($status); //otherwise just return for js to refresh base login page
			exit();
		}	
	}

	public function processSignup($u, $p, $m) {
		if(!$this->checkUsername($u)) {
			$status = array('status' => 0); 
			echo json_encode($status); //error username is already taken
			exit();
		}
		else {
			$newUser = new User(
				array(
					'username' => $u,
					'pw' => $p,
					'email' => $m
				)
			);	
			$newUser->save();

			$status = array('status' => 1);
			echo json_encode($status);
			exit();
		}
	}
}
