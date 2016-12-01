<ul id="sub-primary-nav">
	  <a href="<?= BASE_URL ?>/recentTopics/"><li <?= isSelected($pageName, 'Recent Topics') ?>>  Recent Topics </li> </a> 
		<a href="<?= BASE_URL ?>/hotTopics/"> <li <?= isSelected($pageName, 'Hot Topics') ?>> Hot Topics </li></a> 
</ul>

		<h3 id='recent-text'> Check out some of the 
		<?php if ($pageName == 'Recent Topics') { ?> 
			most recent
		<?php } else { ?> 
			hottest
		<?php } ?>

		talk of the town: </h3>
		<?php if($this->currUser) { ?>
		<div id='start-thread'>
			<h3 id='start-thread-text'> Start a New Thread </h3>
			<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
		</div>
		<?php } ?>

<?php foreach($threads as $thread) { 
	$top = $thread->get('topic');	
	$topicUsername = User::loadById($top->get('user_id'))->get('username');
	$favorites = Favorite::getFavoritesByTopicId($top->get('id')); //should return array of favorite data
	$isFavorite = false;
	if($favorites && $this->currUser) {
		foreach($favorites as $fav) {
			if($fav['user_id'] == $this->currUser->get('id')) {
				$isFavorite = true;
			}
		}
	}
	?>

		<div class="topic">
			<a href="<?= BASE_URL ?>/view/<?=$top->get('id')?>"> 
				<h2 class="topic-title"><?=$top->get('title')?></h2>
				<div class="topic-user">By: <?=$topicUsername?></div>		
			</a>

			<p class='fav-count'> <?=count($favorites)?> </p>
	<?php if($this->currUser and $isFavorite):?> 
			<img src='<?=IMAGES?>/favoriteitem.png' class='unfavorite-item'>
	<?php else:?> 
			<img src='<?=IMAGES?>/unfavoriteitem.png' class='favorite-item'>
	<?php endif;?>
	<?php if($this->currUser and $topicUsername == $this->currUser->get('username')):?> 
			<img src='<?=IMAGES?>/edititem.png' class='edit-item'>
	<?php endif;?>	
	<?php if(($this->currUser and $topicUsername == $this->currUser->get('username')) or $this->admin):?> 
			<img src='<?=IMAGES?>/deleteitem.png' class='delete-item'>
	<?php endif;?>
				<a href="<?= BASE_URL ?>/view/<?=$top->get('id') ?>"> 
					<p class="topic-post"><?=substr($top->get('post'), 0, 160)?>...</p>	
				</a>			
				<label class="topic-time"><?=$top->get('date_created')?></label>
				<?php if($thread->get('locations')): ?><img class='inactive-marker' src='<?=IMAGES?>/marker.png'><?php endif;?>
				<input class="hidden-id" type="hidden" value="<?=$top->get('id') ?>"> 
		</div> 

<?php } ?>
