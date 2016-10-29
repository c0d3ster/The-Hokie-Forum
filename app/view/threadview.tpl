<?php $top = $thread->get('topic'); ?>

<a href="<?=BASE_URL?>/recentTopics/">&#60 Go Back</a>

<div class="topic">
	<h2 class="topic-title"> <?= $top->get('title') ?> </h2>
	<div class="topic-user"> Posted By: <?= User::loadByID($top->get('user_id'))->get('username')?> </div>
	<p class="topic-post"> <?=$top->get('post') ?> </p>
	<input class="hidden-id" type="hidden" value="<?=$top->get('id') ?>"> 	
</div> 

<div class='topic-rating'> 

</div>

	<div id='replies'>
<?php if($thread->get('replies')) {
	foreach($thread->get('replies') as $reply) { ?>
		<div class='reply'>
			<p class='editable'><?=$reply->get('post') ?></p>
			<h5><?php 
				$user = User::loadById($reply->get('user_id'));
				$uname = $user->get('username');?>
				<?=$uname?>
			</h5>
			<?php if($uname == $currUser->get('username')): ?>
				<img src='<?=IMAGES?>/edititem.png' class='edititem'>
				<img src='<?=IMAGES?>/deleteitem.png' class='deleteitem'>
			<?php endif;?>
			<input class="hidden-id" type="hidden" value="<?=$reply->get('id') ?>"> 
		</div>
	
<?php }
} else { ?>
	<h2> Looks like no one has replied to this topic yet... :( </h2>
<?php } ?>
	</div>

	<div class='background-fade-map'></div>
	<div id="map"> 
		<img src='<?=IMAGES?>/blacksburg.png' id='map-image'> 
	</div>

	<textarea id='response'> </textarea>
	<div id='response-options'>
		<div id='add-location'>
			<h3 id='add-location-text'> Add Location </h3>
			<img id='add-location-image' src='<?= IMAGES ?>/additem.png' width='50' height='50'>
		</div>
		<button id='submit-response' name="submit">Submit Response! </button>
	</div>
	


	
