<?php $top = $thread->get('topic'); 
			$topicUsername = User::loadByID($top->get('user_id'))->get('username');
			$isFavorite = false;
			$favorites = 0;
?>
<script type='text/javascript'>
	topic_id = <?=$top->get('id')?>;
</script>

<a href="<?=BASE_URL?>/recentTopics/">&#60 Go Back</a>

<div class="topic">
	<h2 class="topic-title"> <?= $top->get('title') ?> </h2>	
	<div class="topic-user"> By: <?= $topicUsername  ?> </div>

			<p class='fav-count'> <?=count($favorites)?> </p>
			<?php if($this->currUser and $isFavorite):?> 
			<img src='<?=IMAGES?>/favoriteitem.png' class='unfavorite-item'>
	<?php else:?> 
			<img src='<?=IMAGES?>/favoriteitem.png' class='favorite-item'>
	<?php endif;?>

<?php if($this->currUser and $topicUsername == $this->currUser->get('username')):?> 
		<img src='<?=IMAGES?>/edititem.png' class='edit-item'>
<?php endif;?>	
<?php if(($this->currUser and $topicUsername == $this->currUser->get('username')) or $this->admin):?> 
		<img src='<?=IMAGES?>/deleteitem.png' class='delete-item'>
<?php endif;?>		
	<p class="topic-post"> <?=$top->get('post') ?> </p>
	<label class="topic-time"> <?= $top->get('date_created') ?> </label>
	<input class="hidden-id" type="hidden" value="<?=$top->get('id') ?>"> 	
</div> 

<div class='topic-rating'> 

</div>

	<div id='replies'>
<?php if($thread->get('replies')) {
	foreach($thread->get('replies') as $reply) { ?>
		<div class='reply'>
			<p class='editable'><?=$reply->get('post') ?></p>
			<h5 class="reply-name"><?php 
				$user = User::loadById($reply->get('user_id'));
				$uname = $user->get('username');?>
				<?=$uname?>
			</h5>			
			<label class="topic-time"> <?= $reply->get('date_created') ?> </label>
		<?php if($this->currUser and $uname == $this->currUser->get('username')):?> 
				<img src='<?=IMAGES?>/edititem.png' class='edit-item'>
		<?php endif;?>	
		<?php if(($this->currUser and $uname == $this->currUser->get('username')) or $this->admin):?> 
				<img src='<?=IMAGES?>/deleteitem.png' class='delete-item'>
		<?php endif;?>	

		<input class="hidden-id" type="hidden" value="<?=$reply->get('id') ?>"> 
		</div>
	
<?php }
} else { ?>
	<h2 id='no-replies'> Looks like no one has replied to this topic yet... :( </h2>
<?php } ?>
	</div>

	<div class='background-fade-map'></div>
	<div id="map"> 
	</div>

	<textarea id='response' placeholder='reply to this thread here...'></textarea>
	<div id='response-options'>
		<div id='add-location'>
			<h3 id='add-location-text'> Add Location </h3>
			<img id='add-location-image' src='<?= IMAGES ?>/additem.png' width='50' height='50'>
		</div>
		<button id='submit-response' name="submit">Submit Response! </button>
		<button id='cancel-response'>Cancel</button>
	</div>
	
	<div id='location-adder'>
		<label>Location Title: </label>
		<input id='location-title' placeholder="e.g. 'Great taco joint'">
		<label>Latitude</label>
		<input id='lat-in'>
		<label>Longitude</label>
		<input id='long-in'>
	</div>
	
</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		


	
