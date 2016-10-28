



<div id='replies'>
	<?php foreach($thread->get('replies') as $reply) { 
		echo "$reply->get('id')";?>
		<div class='reply'>
			<p><?=$reply->get('post') ?></p>
			<h5><?php 
				$user = User::loadById($reply->get('user_id'));
				$uname = $user->get('username');?>
				<?=$uname?>
			</h5>
		</div>
	
	<?php } ?>

</div>
	
