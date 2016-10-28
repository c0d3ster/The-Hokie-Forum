
<?php foreach($topics as $top) { ?>

		<div class="topic"><a href="<?= BASE_URL ?>/view/<?=$top->get('id') ?>"> 
		
			<h2 class="topic-title"> <?= $top->get('title') ?> </h2>
			<a class="topic-user"> <?= User::loadByID($top->get('user_id'))->get('username')?> </a>
			<p class="topic-post"> <?= substr($top->get('post'), 0, 5) ?> </p>
		</a> </div>
<?php } ?>