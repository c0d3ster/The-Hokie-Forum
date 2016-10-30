		<h3 id='recent-text'> Check out some of the most recent talk of the town: </h3>
		<?php if($this->currUser) { ?>
		<div id='start-thread'>
			<h3 id='start-thread-text'> Start a New Thread </h3>
			<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
		</div>
		<?php } ?>

<?php foreach($topics as $top) { 
	$topicUsername = User::loadByID($top->get('user_id'))->get('username');
	?>

		<a href="<?= BASE_URL ?>/view/<?=$top->get('id') ?>"> 
			<div class="topic">
				<h2 class="topic-title"> <?= $top->get('title') ?> </h2>
				<div class="topic-user"> By: <?= User::loadByID($top->get('user_id'))->get('username')?> </div>
		<?php if($this->currUser and $topicUsername == $this->currUser->get('username')):?> 
				<img src='<?=IMAGES?>/edititem.png' class='edit-item'>
		<?php endif;?>	
		<?php if(($this->currUser and $topicUsername == $this->currUser->get('username')) or $this->admin):?> 
				<img src='<?=IMAGES?>/deleteitem.png' class='delete-item'>
		<?php endif;?>
				<p class="topic-post"> <?= substr($top->get('post'), 0, 160) ?>... </p>				
				<p class="topic-time"> <?= $top->get('date_created') ?> </p>
				<input class="hidden-id" type="hidden" value="<?=$top->get('id') ?>"> 
			</div> 
		</a>
<?php } ?>