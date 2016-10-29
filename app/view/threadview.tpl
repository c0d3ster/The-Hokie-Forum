<?php $top = $thread->get('topic'); ?>

<div class="topic">
	<h2 class="topic-title"> <?= $top->get('title') ?> </h2>
	<div class="topic-user"> Posted By: <?= User::loadByID($top->get('user_id'))->get('username')?> </div>
	<p class="topic-post"> <?=$top->get('post') ?> </p>
	<input class="hidden-id" type="hidden" value="<?=$top->get('id') ?>"> 	
</div> 

<div class='topic-rating'> 

</div>

<div id='replies'>
	<?php foreach($thread->get('replies') as $reply) { ?>
		<div class='reply'>
			<p><?=$reply->get('post') ?></p>
			<h5><?php 
				$user = User::loadById($reply->get('user_id'));
				$uname = $user->get('username');?>
				<?=$uname?>
			</h5>
			<input class="hidden-id" type="hidden" value="<?=$reply->get('id') ?>"> 
		</div>
	
	<?php } ?>
</div>


	<div id="map"> 
		<img src='<?=IMAGES?>/blacksburg.png' id='map-image'> 
	</div>

	<textarea id='response'> </textarea>
	<div id='response-options'>
		<div id='add-location'>
			<h3 id='add-location-text'> Add Location </h3>
			<img id='add-location-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
		</div>
		<button id='submit-response' name="submit">Submit Response! </button>
	</div>
	


	
