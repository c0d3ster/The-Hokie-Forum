<h2 style="text-align:center;">Welcome to your profile <?=$this->currUser->get('username');?>!</h2>

<div id="leftcol">
	<h3 class="profiletitle">Information</h3>
	<p>Username: <?= $user->get('username') ?></p>
r
	<p>Email: <?= $user->get('email') ?></p>

	<button id="changePass">Change password</button>

	<div id="chPass" style="text-align:center;">
		</br>
		<label>Current password: 
			<input type="text" id="currPass">
		</label>
		</br>
		<label>New password: 
			<input type="text" id="newPass">
		</label>
		
		<label>Reenter new password: 
			<input type="text" id="newPass2">
		</label>
		
		<input type="submit" value="Update Password" id="updatePass">
	</div>
	<br><br>

	<label>Email preferences:</label>
	<form action="">
		<input type="radio" name="selection" value="daily"> Daily
		<input type="radio" name="selection" value="weekly"> Weekly
		<input type="radio" name="selection" value="monthly"> Monthly
		<input type="radio" name="selection" value="never"> Neverrr
		<button type="submit" name="submit" value="Update Preferences"
	</form>
</div>

<div id="rightcol">
	<h3 class="profiletitle">Statistics</h3>
	<p>Your Impact: </p>
	<p>Number of topics posted: </p>
	<p>Number of replies: </p>
	<p>Number of topics favorited: </p>
</div>
