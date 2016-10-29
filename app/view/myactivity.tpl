<?php if($activities) {	?>
		<h3 id='recent-text'> Heres a list of every discussion you're a part of: </h3>
		<div id='start-thread'>
			<h3 id='start-thread-text'> Start a New Thread </h3>
			<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
		</div>




	<?php	foreach($activities as $top) { ?>

		<a href="<?= BASE_URL ?>/view/<?=$top->get('id') ?>"> 
			<div class="topic">
				<h2 class="topic-title"> <?= $top->get('title') ?> </h2>
				<div class="topic-user"> Posted By: <?= User::loadByID($top->get('user_id'))->get('username')?> </div>
				<p class="topic-post"> <?= substr($top->get('post'), 0, 160) ?>... </p>
			</div> 
		</a>
	<?php } 
} else {?>
		<h1 id = 'sad-message'> Looks like you haven't ever participated in any discussions... :( </h1>
		<div id='start-thread'>
			<h3 id='start-thread-text'> Start a New Thread </h3>
			<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
		</div>		
		<h2 id = 'change'> You can change that by clicking this >>>  </h2>
<?php } ?>