<ul id="sub-primary-nav">
	<a href="<?= BASE_URL ?>/myActivity/"><li <?= isSelected($pageName, 'My Activity') ?>> My Activity </li> </a> 
	<a href="<?= BASE_URL ?>/myFavorites/"> <li <?= isSelected($pageName, 'My Favorites') ?>> My Favorites </li></a> 
</ul>

<?php if($activities) {	?>
	<h3 id='recent-text'> Heres a list of every discussion you're a part of: </h3>
	<div id='start-thread'>
		<h3 id='start-thread-text'> Start a New Thread </h3>
		<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
	</div>

	<?php	foreach($activities as $top) {
		$topicUsername = User::loadByID($top->get('user_id'))->get('username');
		$favorites = Favorite::getFavoritesByTopicId($top->get('id')); //should return array of favorite data
		$isFavorite = false;
		if($favorites) {
			foreach($favorites as $fav) {
				if($fav['user_id'] == $this->currUser->get('id')) {
					$isFavorite = true;
				}
			}
		}
	?>

	<a href="<?= BASE_URL ?>/view/<?=$top->get('id')?>"> 
		<div class="topic">
			<h2 class="topic-title"><?=$top->get('title')?></h2>
			<div class="topic-user">By: <?=$topicUsername?></div>	
	</a>

	<?php if($this->currUser and $isFavorite):?> 
			<img src='<?=IMAGES?>/favoriteitem.png' class='unfavorite-item'>
	<?php else:?> 
			<img src='<?=IMAGES?>/unfavoriteitem.png' class='favorite-item'>
	<?php endif;?>
	<p class='fav-count'> <?=count($favorites)?> </p>

<?php if($this->currUser and $topicUsername == $this->currUser->get('username')):?> 
		<img src='<?=IMAGES?>/edititem.png' class='edit-item'>
<?php endif;?>	
<?php if(($this->currUser and $topicUsername == $this->currUser->get('username')) or $this->admin):?> 
		<img src='<?=IMAGES?>/deleteitem.png' class='delete-item'>
<?php endif;?>		
				<a href="<?= BASE_URL ?>/view/<?=$top->get('id') ?>"> 
					<p class="topic-post"><?=substr($top->get('post'), 0, 160)?>...</p>
				</a>
			<label class="topic-time"><?=$top->get('date_created') ?></label>
			<input class="hidden-id" type="hidden" value="<?=$top->get('id') ?>"> 
		</div> 

	<?php } 
} else {?>
	<h1 id = 'sad-message'> Looks like you haven't ever participated in any discussions . . . :( </h1>
	<div id='start-thread'>
		<h3 id='start-thread-text'> Start a New Thread </h3>
		<img id='start-thread-image' src='<?= IMAGES ?>/additem.png?>' width='50' height='50'>
	</div>		
	<h2 id = 'change'> You can change that by clicking this > > ></h2>
<?php } ?>
