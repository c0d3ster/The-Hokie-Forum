<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a ProductController and route it
$pc = new PostController();
$pc->route($action);

class PostController {

	// route us to the appropriate class method for this action
	public function route($action) {

		switch($action) {

			/////cases for topic control/////

			case 'viewTopic':
				$topicID = $_GET['tid'];	
				$this->viewTopic($topicID);
				break;			

			case 'addTopic':
				if($currUser) { //user needs to be logged in to post topics
					$this->addTopic();
				}
				break;

			case 'editTopic':
				$topicID = $_GET['tid'];
				$t = Thread::getThreadById($topicID);
				if($currUser == $t->get('user_id')) { //always need to compare user_id's for security
					$this->editTopic($t);
				}
				break;

			case 'deleteTopic':
				$topicID = $_GET['tid'];
				$t = Thread::getThreadById($topicID);
				if($currUser == $t->get('user_id') || $admin) { //admins can delete any topic as well
					$this->deleteTopic($t);
				}
				break;

			/////cases for topic control processing//////

			case 'processAdd':
				if($currUser) {
					$this->processAdd($currUser);
				}
				break;

			case 'processEdit':
				$topicID = $_GET['tid'];
				$t = Thread::getThreadById($topicID);
				if($currUser == $t->get('user_id')) {
					$this->processEdit($t);
				}
				break;

			case 'processDelete':
				$topicID = $_GET['tid'];
				$t = Thread::getThreadById($topicID);
				if($currUser == $t->get('user_id') || $admin) {
					$this->processDelete($t);
				}
				break;


			/////cases for reply control/////

			case 'editReply':
				$replyID = $_GET['rid'];
				$r = Thread::getReplyById($replyID);
				if($currUser == $r->get('user_id')) { //always need to compare user_id's for security
					$this->editReply($r);
				}
				break;

			case 'deleteReply':
				$replyID = $_GET['rid'];
				$r = Thread::getReplyById($replyID);
				if($currUser == $r->get('user_id') || $admin) { //admins can delete any reply as well
					$this->deleteReply($r);
				}
				break;

			/////cases for topic control processing/////

			case 'processAddReply':
				if($currUser) {
					$this->processAddReply($currUser);
				}
				break;

			case 'processEditReply':
				$replyID = $_GET['rid'];
				$r = Reply::loadById($replyID);
				if($currUser == $r->get('user_id')) {
					$this->processEditReply($r);
				}
				break;

			case 'processDeleteReply':
				$replyID = $_GET['rid'];
				$r = Reply::loadById($replyID);
				if($currUser == $r->get('user_id') || $admin) {
					$this->processDeleteReply($r);
				}
				break;

      // redirect to home page if all else fails
      default:
        header('Location: '.BASE_URL);
        exit();
		}
	}


  public function recentTopics() {
		$topics = Topic::getAllTopics();

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/recenttopics.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

    public function hotTopics() {
		$topics = Topic::getAllTopics();

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/recenttopics.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

  public function myActivity() {
		$pageName = 'My Activity';

		//this should return an array of topics (or threads?) which you have partipated in
		$activities = Thread::getMyActivities($currUser);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/myactivity.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }

	public function viewProduct($id) {
		$p = Product::loadById($id);
		$pageName = 'View '.$p->get('title');
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/viewproduct.tpl';
		$category = $p->get('category');
		//result = query for category - current item
		include_once SYSTEM_PATH.'/view/productslider.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
  }


	public function addProduct() {
		$pageName = 'Add Product';
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addproduct.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function editProduct($id) {
		$p = Product::loadById($id);
		$pageName = 'Edit '.$p->get('title');
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/editproduct.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function deleteProduct($id) {
		$p = Product::loadByID($id);
		$pageName = 'Confirm?';

		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS)
			or die ('Error: Could not connect to MySql database');
		mysql_select_db(DB_DATABASE);

		$q = "SELECT * FROM product ORDER BY date_created DESC; ";
		$result = mysql_query($q);
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/myproducts.tpl';
		include_once SYSTEM_PATH.'/view/deleteproduct.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function processAdd() {
		$title = $_POST['title'];
		$category = $_POST['category'];
		$description = $_POST['description'];		
		$price = $_POST['price'];
		$sizes = $_POST['sizes'];
		$image_url = $_POST['image_url'];
		if(!isset($_SESSION)) { 
			session_start(); 
		}
		$user = User::loadByUsername($_SESSION['user']);
		$creator_id = $user->get('id');

		$newProduct = new Product(
			array(
				'title' => $title,
				'category' => $category,
				'description' => $description,
				'price' => $price,
				'sizes' => $sizes,
				'image_url' => $image_url,
				'creator_id' => $creator_id
			)
		);
		$newProduct->save();

		session_start();
		$_SESSION['msg'] = "You added a product called ".$title;
		$pageName = 'My Products';
		header('Location: '.BASE_URL.'/myProducts/');
	}


	public function processEdit($id) {
		$title = $_POST['title'];
		$category = $_POST['category'];
		$description = $_POST['description'];		
		$price = $_POST['price'];
		$sizes = $_POST['sizes'];
		$image_url = $_POST['image_url'];


		$p = Product::loadById($id);
		$p->set('title', $title);
		$p->set('category', $category);
		$p->set('description', $description);		
		$p->set('price', $price);
		$p->set('sizes', $sizes);
		$p->set('image_url', $image_url);
		$p->save();

		session_start();
		$_SESSION['msg'] = "You edited the product called ".$title;
		header('Location: '.BASE_URL.'/myProducts/');
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
