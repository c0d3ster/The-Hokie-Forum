<h2 style="text-align:center;">Welcome to your profile!</h2>

<div id="leftcol">
	<h3 class="profiletitle">Information</h3>
	<p>Username: <?= $user->get('username') ?></p>

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
		</br>
		<label>Reenter new password: 
			<input type="text" id="newPass2">
		</label>
		</br>
		<input type="submit" value="Update Password" id="updatePass">
	</div>
	<br><br>

	<label>Email preferences:</label>
	<form action="">
		<input type="radio" name="daily" value="daily"> Daily<br>
		<input type="radio" name="weekly" value="weekly"> Weekly<br>
		<input type="radio" name="monthly" value="monthly"> Monthly
	</form>
</div>

<div id="rightcol">
	<h3 class="profiletitle">Statistics</h3>
	<p>Number of topics posted: </p>
	<p>Number of comments: </p>
</div>