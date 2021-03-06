<?php

function isSelected($pn, $link) {
	if($pn == $link) {
		return ' id="this-tab" ';
	}
}

function isChildSelected($pn, $type) {
	if ($type == 'topic') {
		if($pn == 'Recent Topics' || $pn == 'Hot Topics')
			return ' id="parent-tab" ';
	}
	else if ($type == 'activity') {
		if ($pn == 'My Favorites' || $pn == 'My Activity')
			return ' id="parent-tab" ';
	}
	else if ($type == 'Explore') {
		if( $pn == 'Explore Map' || $pn == 'Explore Bubbles')
			return ' id= "parent-tab" ';
	}
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="description" content="The Hokie Forum - Where the Real Hokies Go">

	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/fonts.css">
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/style.css">		
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/header.css">
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/footer.css">
	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script src="https://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
	<script src="<?= SCRIPTS ?>/index.js" type="text/javascript"> </script>
	<script type="text/javascript">
		var baseURL = '<?= BASE_URL ?>';
		var pageName = '<?= $pageName ?>';
	</script>
	<title> <?= $pageName ?> </title>
</head>

<body>
	<div id="header" style="background-image: url('<?= IMAGES ?>/header.jpg'); background-size: auto 100%;">
		<a href="<?= BASE_URL ?>"> <h1> The Hokie Forum </h1> </a>

		<div id="search">
			<input type="text" placeholder="Buzzwordz"> 
			<button> Search the Forum! </button>
		</div>

<?php
if($this->currUser) { ?>
		<div id="login-menu">
			<p> What's up <?= $_SESSION['user'] ?>? </p>	 
		  <button id="logout"> Log Out :( </button>  
		  <a href="<?= BASE_URL ?>/profile/" ><button id="profile"> Profile & Preferences </button></a>
		</div>
		<ul id="primary-nav">
	  	<a href="<?= BASE_URL ?>/" ><li <?= isChildSelected($pageName, 'Explore') ?>>  Explore </li> </a>
	  	<a href="<?= BASE_URL ?>/recentTopics/"><li <?= isChildSelected($pageName, 'topic') ?>> Topics </li> </a> 
	  	<a href="<?= BASE_URL ?>/myActivity/"><li <?= isChildSelected($pageName, 'activity') ?>> My Stuff </li></a> 
		</ul>
<?php } 
else { ?>
		<div id="login-menu">
			<button id="login"> Login </button>
			<button id="signup"> Sign Up </button>
		</div>
		<ul id="primary-nav">
			<a href="<?= BASE_URL ?>/" > <li <?= isChildSelected($pageName, 'Explore') ?>>  Explore   </li></a>
			<a href="<?= BASE_URL ?>/recentTopics/"> <li <?= isChildSelected($pageName, 'topic') ?>> Topics </li></a> 
		</ul>
<?php } ?>

	<!--html elements for pop ups
	    image of shopping cart created through Jquery citation: http://www.clker.com/cliparts/V/o/l/z/d/6/shopping-cart-hi.png-->
		<div class="background-fade"></div>
		<form class="popup" action="<?= BASE_URL ?>/login/process" method="POST">
			<!--http://sweetclipart.com/multisite/sweetclipart/files/x_mark_red_circle.png-->
			<img src="<?= IMAGES ?>/exit.png" class="exit" alt="exit" width="50" height="50">
			<h2 id="hype"> Get Back to the Talk! </h2>
			<input id="username" type="text" name="un" placeholder="enter your username">
			<input id="password" type="password" name="pw"  placeholder="enter your password">
			<input type="submit" name="submit" id="verify-login" value="Go Hokies!">
		</form>

		<form class="popsignup" action="<?= BASE_URL ?>/signup/process" method="POST">
			<!--http://sweetclipart.com/multisite/sweetclipart/files/x_mark_red_circle.png-->
			<img src="<?= IMAGES ?>/exit.png" class="exit" alt="exit" width="50" height="50">
			<h2 id="hype"> What's a Hokie? </h2>
			<input id="user" type="text" name="un" placeholder="username">
			<input id="pass" type="password" name="pw"  placeholder="password">
			<input id="mail" type="text" name="mail"  placeholder="email">
			<input type="submit" name="submit" id="verify-signup" value="I Am!">
		</form>

	</div>
	<div id="wrapper">	
