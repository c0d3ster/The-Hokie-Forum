<h1 id="profilewelcome">Welcome to your profile <?=$this->currUser->get('username');?>!</h1>

<div id="leftcol">
	<h2 class="profiletitle">Information</h2>
	<h2 class="profileinfo">Username:</h2> <p><?= $this->currUser->get('username') ?></p>
	<h2 class="profileinfo">Email:</h2> <p><?= $this->currUser->get('email') ?></p>
 
	<button id="changePass">Change password</button>

	<div id="chPass" style="text-align:center;">
		</br>
		<label>Current password: 
			<input type="text" id="currPass">
		</label>
		<label>New password: 
			<input type="text" id="newPass">
		</label>
		<label>Reenter new password: 
			<input type="text" id="newPass2">
		</label>
		<input type="submit" value="Update Password" id="updatePass">
	</div>
	<br><br>

	<h2>Email preferences:</h2>
	<form action="">
		<input type="radio" name="selection" value="daily"> Daily
		<input type="radio" name="selection" value="weekly"> Weekly
		<input type="radio" name="selection" value="monthly"> Monthly
		<input type="radio" name="selection" value="never"> Never
		<button type="submit" name="submit" value="Update Preferences"> Update Preferences </button>
	</form>
</div>

<div id="rightcol">
	<h2 class="profiletitle">Statistics</h2>
	<p>Topics posted.......... <?=$topicCount?></p>
	<p>Replies................... <?=$replyCount?></p>
	<p>Topics favorited....... <?=$favCount?></p>
	<p>Your Impact............ <?=$yourImpact?></p>
</div>
