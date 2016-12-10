		<ul id="sub-primary-nav">
			  <a href="<?= BASE_URL ?>/"><li <?= isSelected($pageName, 'Explore Map') ?>>  Map </li> </a> 
				<a href="<?= BASE_URL ?>/exploreBubbles/"> <li <?= isSelected($pageName, 'Explore Bubbles') ?>> Bubbles </li></a> 
		</ul>

<?php if($this->currUser) { ?>
		<h2 id='recent-text'> Explore the town map, check out some topic bubbles, or start a new conversation! </h2>
		<div id='start-thread'>
			<h3 id='start-thread-text'> Start a New Thread </h3>
			<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
		</div>
<?php } else { ?>
		<div id="intro">
			<h1> What is <br>The Hokie Forum? </h1>
			<p> Blacksburg is a small town but it's full of awesome people and awesome places.  The Hokie Forum is a location based forum that allows you to share your favorite spots around town with other Hokies.  Our goal is to uncover all the secrets of Blacksburg and centralize them into one place for all Hokies to enjoy. This website is made by Hokies for Hokies, so sign up now and start chatting with the community!
			</p>
		</div>
<?php } ?>

<?php if($pageName == "Explore Map") { ?>
		<div id="map-large"> 
		</div>
<?php } else { ?>		
		<div id="button-holder">
			<button id="view-all">Back to All Topics</button>
			<button id="view-single">Individual view</button>
		</div>
		<div id="chart">
		</div>

<?php } ?>
