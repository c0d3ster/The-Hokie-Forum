<?php

function isSelected($pn, $link) {
	if($pn == $link) {
		return ' id="this-tab" ';
	}
}

if(session_status() == PHP_SESSION_DISABLED || session_status() == PHP_SESSION_NONE) {
	session_start();
}

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="description" content="MyFit - Personalized Workouts and Equipment">

	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/fonts.css">
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/style.css">	
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/shop.css">		
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/header.css">
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/footer.css">
	<script src="https://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
	<script src="<?= SCRIPTS ?>/index.js" type="text/javascript"> </script>
	<script type="text/javascript">
		var baseURL = '<?= BASE_URL ?>';
	</script>

<?php if(isset($_SESSION['user']) and $_SESSION['user'] == 'Admin'): ?>
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/AdminFonts.css">
	<link rel="stylesheet" type="text/css" href="<?= STYLES ?>/Admin.css">
	<script src="<?= SCRIPTS ?>/Admin.js" type="text/javascript"> </script>
<?php endif; ?>

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
if(isset($_SESSION['user']) and User::loadByUsername($_SESSION['user'])->get('admin')) { ?>
		<div id="login-menu">
			<p> hi <?= $_SESSION['user'] ?>, you DA man... </p>
		  <button id="logout"> Log Out </button>  
		</div>
		<ul id="primary-nav">
			<li <?= isSelected($pageName, 'Explore') ?>> <a href="<?= BASE_URL ?>/" > Explore </a>  </li>
	  	<li <?= isSelected($pageName, 'Recent Topics') ?>> <a href="<?= BASE_URL ?>/Recent Topics/"> Recent Topics </a>  </li>
	  	<li <?= isSelected($pageName, 'My Posts') ?>> <a href="<?= BASE_URL ?>/posts/" class="not-active"> My Posts </a>  </li>
	  	<li <?= isSelected($pageName, 'Profile') ?>> <a href="<?= BASE_URL ?>/profile/" class="not-active"> Profile/Preferences </a>  </li>
		</ul>

<?php } else if(isset($_SESSION['user'])) { ?>
		<div id="login-menu">
			<p> Check out the latest in the Burg <?= $_SESSION['user'] ?>... </p>	  
		  <button id="logout"> Log Out :( </button>  
		</div>
		<ul id="primary-nav">
	  	<li <?= isSelected($pageName, 'Explore') ?>> <a href="<?= BASE_URL ?>/" > Explore </a>  </li>
	  	<li <?= isSelected($pageName, 'Recent Topics') ?>> <a href="<?= BASE_URL ?>/Recent Topics/"> Recent Topics </a>  </li>
	  	<li <?= isSelected($pageName, 'My Posts') ?>> <a href="<?= BASE_URL ?>/posts/" class="not-active"> My Posts </a>  </li>
	  	<li <?= isSelected($pageName, 'Profile') ?>> <a href="<?= BASE_URL ?>/profile/" class="not-active"> Profile/Preferences </a>  </li>
		</ul>

<?php } else { ?>
		<div id="login-menu">
			<button id="login"> Login </button>
			<button id="signup"> Sign Up </button>
		</div>
		<ul id="primary-nav">
			<li <?= isSelected($pageName, 'Explore') ?>> <a href="<?= BASE_URL ?>/" > Explore </a>  </li>
			<li <?= isSelected($pageName, 'Recent Topics') ?>> <a href="<?= BASE_URL ?>/recent/"> Recent Topics</a>  </li>
		</ul>
<?php } ?>

	<!--html elements for pop ups
	    image of shopping cart created through Jquery citation: http://www.clker.com/cliparts/V/o/l/z/d/6/shopping-cart-hi.png-->
		<div class="background-fade"></div>
		<form class="popup" action="<?= BASE_URL ?>/login/process" method="POST">
			<!--http://sweetclipart.com/multisite/sweetclipart/files/x_mark_red_circle.png-->
			<img src="<?= IMAGES ?>/exit.png" class="exit" alt="exit" width="50" height="50">
			<h2 id="hype"> Get Back in the Game! </h2>
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

	<!--need to add submenu which is also based on whether user is logged in or not-->