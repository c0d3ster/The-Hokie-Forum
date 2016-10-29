	<h2 id='heck-yea'> Start a new conversation: (Heck yea) </h2>
	<form id='add-topic' action='<?= BASE_URL ?>/addTopic/process/' method='POST'>
		<div class='col'>
			<h2> Topic Title </h2>
			<textarea id='title' type='text' name='title' placeholder='Place your topic title here.'></textarea>

			<h2> Topic Description </h2>
			<textarea id='post' type='text' name='post' placeholder='Place your full description here'></textarea>
		</div>
		<div class='col'>
			<h2> Category(s) </h2>
			<span class='fine-print'> (topics can have up to 5 categories) </span>
			<textarea id='category' type='text' name='category' placeholder='category1, category2, category3'></textarea>

		<div id='add-location-new'>
			<h3 id='add-location-text'> Add Location </h3>
			<img id='add-location-image' src='<?= IMAGES ?>/additem.png' width='50' height='50'>
		</div>
			<div id="map-hidden"> 
				<img src='<?=IMAGES?>/blacksburg.png' id='map-image'> 
			</div>


		</div>			
		<input id="submit" type="submit" name="submit" value="Share it with the world">
			<input id="cancel" type="button" value="nevermind, reset!">
	</form>