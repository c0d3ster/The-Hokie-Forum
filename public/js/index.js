//Document.Ready equivilant
$(function() {

	topic_id = $('.topic .hidden-id').val(); //grab the hidden topic id
	$('#map-hidden').slideToggle('fast'); //quick hide the map!

	//event listeners for user role manipulation
	$('#login').click(loginClicked);
	$('#signup').click(signupClicked); 
	$('.exit').click(exitClicked);
	$('#logout').click(logoutClicked);


	//event listeners to add topic manipulation
	$('#start-thread').click(startThreadClicked);
	$('#add-location-new').click(newLocationClicked);
	$('#cancel').click(resetForm);

	//event listeners for add reply manipulation
	$('#add-location').click(addLocationClicked);
	$('#submit-response').click(submitReply);
	$('#cancel-response').click(cancelReply);

	//event listeners for favoriting, editing, and removing content	
	$('.edit-item').click(editClicked);
	$('.delete-item').click(deleteClicked);
	$('.favorite-item').click(switchFavorite);
	$('.unfavorite-item').click(switchFavorite);

	// event listeners for user to update password
	$('#changePass').click(changePass);
	$('#updatePass').click(updatePassword);
	$('#chPass').hide();

	// don't let user submit form if empty fields
	$('form > input').keyup(function() {
        var empty = false;
        $('form > input').each(function() {
            if ($(this).val() == '') {
                empty = true;
            }
        });

        if (empty) {
            $('#updatePass').attr('disabled', 'disabled'); 
        } else {
            $('#updatePass').removeAttr('disabled'); 
        }
    });

	//subheader menu control (to be implemented)
});

/* 
 * @function
 * @name mapInit
 * creates the maps if necessary and retrieves marker data from database
 */
function mapInit() {
	unsavedMarker = null;
	clickable = false;

	if (pageName == 'Explore'){ //only display maps on three pages
		mapObj = null;
		mapObj = new GMaps({
			div: '#map-large',
			lat: 37.229592,
			lng: -80.413960,
			zoom: 11
		});
		//ajax call
		$.ajax({    
		type: "POST",
	    url: baseURL+'/exploreMap/',      
      	dataType: 'json',
      	success: function(data){ //get coordinate data
			console.log(data);
			for (var i = 0; i < data.length; i++){
				var x = data[i]['Xcoord'];
				var y = data[i]['Ycoord'];
				var content_str = "<a href='"+baseURL+"/view/"+data[i]['topic_id']+"'>"+data[i]['title']+"</a>";
				mapMarker = mapObj.addMarker({
					lat: x,
					lng: y,
					title: data[i]['title'],
					infoWindow: {
						content: content_str
					}
				});
			}
		},  
		error: function (data) {
				console.log(data);
				alert(data.status);
			}                                 
		});
	}
	
	else if (pageName == 'Thread View') { //grabs locations for this thread
		mapObj = null;
		mapObj = new GMaps({
			div: '#map',
			lat: 37.229592,
			lng: -80.413960,
			zoom: 13,
			click: function(e) { //on click listener for adding markers
				if(clickable) {
					mapObj.removeMarker(unsavedMarker);
					var LAT = e.latLng.lat();
					var LNG = e.latLng.lng();
					unsavedMarker = mapObj.addMarker({
						lat: LAT,
						lng: LNG,
						title: 'Temporary Marker'
					});
					document.getElementById('lat-in').value = LAT;
					document.getElementById('long-in').value = LNG; 
				}
			}
		});
		$.ajax({	//ajax call to populate thread specific maps
			type: "POST",

			url: baseURL+'/populateMap/'+topic_id+'/',      
		  	dataType: 'json',
		  	success: function(data){
		  		if(data.length == null){
		  			return;
		  		}
		  		mapObj.removeMarker(unsavedMarker);
				for (var i = 0; i < data.length; i++){
					var x = data[i]['Xcoord'];
					var y = data[i]['Ycoord'];
					var content_str = "<label>"+data[i]['title']+"</label>";
					mapMarker = mapObj.addMarker({
						lat: x,
						lng: y,
						title: data[i]['title'],
						infoWindow: {
							content: content_str
						}
					});
				}
			},  
			error: function (data) {
				console.log(data);
				alert(data.status);
			}                                 
		});
	}
	
	else if (pageName == 'Add Topic') { //add location tied to a topic
		mapObj = null;
		mapObj = new GMaps({
			div: '#map-hidden',
			lat: 37.229592,
			lng: -80.413960,
			click: function(e) {
				mapObj.removeMarker(unsavedMarker);
				var LAT = e.latLng.lat();
				var LNG = e.latLng.lng();
				unsavedMarker = mapObj.addMarker({
					lat: LAT,
					lng: LNG,
					title: 'Temporary Marker'
				});
				document.getElementById('lat-in').value = LAT;
				document.getElementById('long-in').value = LNG; 
			}
		});
	}
}

/* 
 * @function
 * @name loginClicked
 * This function is the event listener function for the signup button and will display both popup elements and apply positioning functions
 */
function signupClicked() {
  $('.background-fade').fadeIn(1000);
	$('.popsignup').fadeIn(1000);
	$('form').submit(function(e){
		e.preventDefault();
	 	verifySignup($('#user').val(), $('#pass').val(), $('#mail').val());
	 	return false;
	});
}

/* 
 * @function
 * @name loginClicked
 * This function is the event listener function for the login button and will display both popup elements and apply positioning functions
 */
function loginClicked() {
  $('.background-fade').fadeIn(1000);
	$('.popup').fadeIn(1000);
	$('form').submit(function(e){
		e.preventDefault();
	 	verifyCredentials($('#username').val(), $('#password').val());
	 	return false;
	});
}

/* 
 * @function
 * @name verifyCredentials
 * This function will verify user credentials before submitting a database query
 * @param {string} username the user to find.
 * @param {string} password the password that was entered.
 * @returns {boolean} true if password is correct, false if password is not correct
 */
function verifyCredentials(username, password) {
	if (checkString(username) && checkString(password) && username.length <= 10  && !/\s/.test(username)  && !/\s/.test(password) ) {
			//make an ajax post to check for valid password
		var datastr = 'un=' + username + '&pw=' + password;
		$.ajax({    
	   	    type: "POST",
			url: baseURL+'/login/process/', 
    	    data: datastr,      
			dataType: 'json',                   
	        success: function(data){
				if(data.status == 1){
  					window.location.replace(baseURL);
  					return true;
	  			}
	  			else{
	  				$('#password').val('');
	  				$('.popup').append('<p> Incorrect Username or Password. Please try again! (1-10 chars)</p>');
					$('.popup > p').delay(2000).fadeOut();
					return false;
	  			}	
        },  
		    error: function (e) {
		        alert(e.responseText);
		    }                                 
	    }) 	
	}
	else {
		$('#password').val('');
		$('.popup').append('<p> Invalid Credentials. Please try again! (1-10 chars)</p>')
		$('.popup > p').delay(2000).fadeOut();
		return false;
	}
}

/* 
 * @functiond
 * @name verifySignup
 * This function will verify username availability before submitting a database query
 * @param {string} username the user to find.
 * @param {string} password the password that was entered.
 * @param {string} mail the email that was entered
 * @returns {boolean} true if user is a available and created
 */
function verifySignup(user, pass, mail) {
  for (var i = 0, j = arguments.length; i < j; i++){ //loop through params
      if(!checkString(arguments[i]) || /\s/.test(arguments[i]) || arguments[0].length > 16) {
      	//checking for spaces, empty fields, and length > 16 on username
    		$('#pass').val('');
				$('.popsignup').append('<p> Please fill out all information (username 1-16 chars)</p>');
				$('.popsignup > p').delay(2000).fadeOut(); //delayed error message fade away
      	return false;
      }
  }
		var datastr = 'u=' + user + '&p=' + pass + '&m=' + mail;
		$.ajax({    
      type: "POST",
      url: baseURL+'/signup/process/', 
      data: datastr,      
      dataType: 'json',                   
      success: function(data){ //add the new user to the database
  			if(data.status == 1){
  				verifyCredentials(user, pass);
					return true; 
  			}
  			else{
  				alert(data.status);
  				$('#pass').val('');
  				var suggestion = Math.floor((Math.random() * 10) + 1); //suggest a different username
  				$('.popsignup').append('<p> Sorry that username is taken, how about '+user+suggestion+'???</p>');
					$('.popsignup > p').delay(2000).fadeOut();
					return false;
  			}	
      },  
      error: function () {
          alert(data.status);
      }                                 
    }); 	
}

/* 
 * @function
 * @name logoutClicked
 * The event listener function for the logout button and will display both rest the view to logged out mode
 */
function logoutClicked() {
	window.location.replace(baseURL + "/logout");
}

/* 
 * @function
 * @name exitclicked
 * The event listener function for the exit button and will hide the login or sign up popups
 */
function exitClicked() {
	$('.background-fade').fadeOut(1000);
	$('.popup').fadeOut(1000);
	$('.popsignup').fadeOut(1000);
	$('form').off(); //turn form off so user cant click enter on accident
}

/*
 * @function
 * @name checkString
 * This function will check to see if a variable is a valid string
 *
 * @param {string} the variable to be checked
 * @param {string} the variable to be checked
 * @returns {boolean} true if myVar is a non-empty string, false otherwise
 */
function checkString(myVar) {
  return ((typeof myVar === 'string' || myVar instanceof String) && myVar != '');
}

/* 
 * @function
 * @name startThreadClicked
 * Takes user to the Add Topic page to post new topic
 */
function startThreadClicked() {
	window.location.replace(baseURL + "/addTopic/");
}

/* 
 * @function
 * @name newLocationClicked
 * Fades the hidden map into view
 */
function newLocationClicked() {
	$('#map-hidden').slideToggle('slow');
	$('#location-adder-new').show();
}

/* 
 * @function
 * @name resetForm
 * Reloads the Add Topic page
 */
function resetForm() {
	window.location.replace(baseURL + "/addTopic/");
}

/* 
 * @function
 * @name addLocationClicked
 * Puts the map into focus and blocks everything but the user inputs and the map
 */
function addLocationClicked() {
	$('.background-fade-map').fadeIn(500);
	$('#location-adder').show();
	clickable = true;
}


function switchFavorite() {
	var thisItem = $(this);
	var topic_id = thisItem.parent().find('.hidden-id').val();
	
	$.ajax({    
		type: "POST",
    url: baseURL+'/switchFavorite/', 
   	data: {
   		'tid': topic_id
   	},
   	dataType: 'json',                        
    success: function(data){ //added is set to 1 if request added a favorite, 0 if it was removed
		if (data.added == 1)
		{ //if empty star is clicked send request to add this thread to favorites
			var newCount = thisItem.prev().text();
			thisItem.prev().text(++newCount);
			thisItem.toggleClass('favorite-item unfavorite-item');
			thisItem.replaceWith('<img src="'+baseURL+'/public/img/favoriteitem.png" class="unfavorite-item">');
			$('.unfavorite-item').click(switchFavorite);
		}
		else if (data.added == 2) {
			alert("Not signed in!");
		}
		else
		{ //otherwise remove this entry from the favorites table
			var newCount = thisItem.prev().text();
			thisItem.prev().text(--newCount);
			thisItem.toggleClass('favorite-item unfavorite-item');
			thisItem.replaceWith('<img src="'+baseURL+'/public/img/unfavoriteitem.png" class="favorite-item">');
			$('.favorite-item').click(switchFavorite);
		}
	},  
	error: function (data) {
		alert(data.added);
	}                                 
  });


}

/* 
 * @function
 * @name deleteClicked
 * this will create a pop up for the confirm delete box
 */
function deleteClicked() {
	var type = 'Topic';
	var idString = '<input id="rid" type="hidden" name="rid" value="id">';
	if ($(this).parent().attr('class') == 'reply') { //distinguish between reply and topic
		type = 'Reply';
		idString = '<input id="rid" type="hidden" name="tid" value="id">';
		}
	var id = $(this).siblings('input').filter('.hidden-id').eq(0).val();
	$('body').append( //populate the delete item confirmation box
	'<div class="background-fade-red"></div>'+
		'<form class="popup-red" action="'+baseURL+'/delete'+type+'/process/'+id+'" method="POST">'+
		'<h2 id="confirm"> Are you sure you would like to get rid of this amazing '+type+' forever??? </h2>'+
				idString+
				'<input id="submit" type="submit" name="submit" value="Destroy it NOW!">'+
				'<input id="letitlive" type="button" value="WAIT... let it live">'+
		'</form>');
	$('.background-fade-red').fadeIn(1000);
	$('.popup-red').fadeIn(1000);

	$('#letitlive').click(cancelDelete);
}

/* 
 * @function
 * @name cancelDelete
 * fade out the popups then remove them
 */
function cancelDelete() {
	var reply = $($(this).parent());
	reply.fadeOut(1000);
	reply.prev().fadeOut(1000);
	setTimeout(function() {
		reply.empty();
		reply.prev().remove();
		reply.remove();
	}, 1000);
}

function submitReply() {

	var post = $('#response').val();
	var topic_id = $('.topic .hidden-id').val();
	var lat = $('#lat-in').val();
	var long = $('#long-in').val();
	var title = $('#location-title').val();
	var loc_str = "GEOMFROMTEXT('POINT("+lat.toString()+" "+long.toString()+")',0)"; 
	
	$.ajax({    
		type: "POST",
	    url: baseURL+'/addReply/process/', 
    	data: {
    		'post': post,
    		'topic_id': topic_id,
    		'loc': loc_str,
    		'loctitle': title
    	},      
      	dataType: 'html',                   
      	success: function(data){ //fade in new response and scroll the div to max to view it
			$(data).appendTo('#replies').hide().fadeIn(2000); 
			$("#replies").animate({ scrollTop: $('#replies').prop("scrollHeight")}, 1000);
			$('#response').val('');
			$('.background-fade-map').fadeOut(1000);
			$('#no-replies').fadeOut(500);

			//set up event listeners again and clear user input fields
			$('.edit-item').click(editClicked);
			$('.delete-item').click(deleteClicked);
			$('#lat-in').val('');
			$('#long-in').val('');
		},  
		error: function () {
			alert(data);
		}                                 
    });
    $('#location-adder').hide();
}

function cancelReply() {
	$('#lat-in').val('');
	$('#long-in').val('');
	$('#location-title').val('');
	$('#response').val('');
    $('#location-adder').hide();
    clickable = false;
    mapObj.removeMarker(unsavedMarker);
	$('.background-fade-map').fadeOut(500);
	
}

function editClicked() {
	//we need to check for topic vs reply here 
	var type = $(this).parent().attr('class');
	var topic_title = null;
	var topic_title_val = null;
	
	var links = $(this).parent().find('a');
	links.click(function(e) {
		e.preventDefault();
	});
	
	if (type == 'topic') {
		topic_title = $(this).parent().find('h2');
		topic_title_val = topic_title.text();
		topic_title.replaceWith('<input id="title" value="'+topic_title_val+'">');
	}
	
	var post = $(this).parent().find('p');
	var val = post.text();
	post.replaceWith('<textarea id="post">'+val+'</textarea>');
	
	$(this).next().replaceWith("<button class='cancel-edit'>Cancel</button>");
	$(this).replaceWith("<button class='submit-edit'>Save</button>");
	$('.submit-edit').click({param1: type}, submitEditClicked);
	$('.cancel-edit').click({param1: val, param2: topic_title_val}, cancelEditClicked); 
}

function submitEditClicked(type) {
	var id = $(this).parent().find('.hidden-id');
	if (type.data.param1 == 'reply') {
		editReply(id.val(), $(this).parent());
	}
	else if (type.data.param1 == 'topic') {
		editTopic(id.val(), $(this).parent());
	}
	
	var links = $(this).parent().find('a');
	links.unbind('click');
	
	$(this).next().replaceWith("<img src='"+baseURL+"/public/img/deleteitem.png' class='delete-item'>");
	$(this).replaceWith("<img src='"+baseURL+"/public/img/edititem.png' class='edit-item'>");
	
	id.siblings('.edit-item').click(editClicked);
	id.siblings('.delete-item').click(deleteClicked);
}

function editReply(id, replyVar) {
	
	var post = replyVar.find('#post').val();
	
	$.ajax({    
		type: "POST",
	    url: baseURL+'/editReply/process/'+id, 
    	data: {
    		'post': post,
    	},      
      	dataType: 'json',                   
      	success: function(data){
  			replyVar.find('#post').replaceWith("<p class='editable'>"+data.post+"</p>");
		},  
		error: function (data) {
			alert(data.status);
		}                                 
    });
}

function editTopic(id, topicVar) {
	var post = topicVar.find('#post').val();
	var title = topicVar.find('#title').val();
	
	$.ajax({    
		type: "POST",
	    url: baseURL+'/editTopic/process/'+id, 
    	data: {
    		'post': post,
    		'title': title
    	},      
      dataType: 'json',                   
      success: function(data){
  			topicVar.find('#post').replaceWith("<p class='topic-post'>"+data.post+"</p>");
  			topicVar.find('#title').replaceWith("<h2 class='topic-title'>"+data.title+"</h2>");
			},  
			error: function (data) {
				alert(data.status);
			}                                 
    });
}

function cancelEditClicked(info) {
	var prevPost = info.data.param1;
	var prevTitle = null;
	$('#post').replaceWith("<p class='editable'>"+prevPost+"</p>").hide().fadeIn(1000);
	
	if(info.data.param2) {
		prevTitle = info.data.param2;
		$('#post').replaceWith("<p class='topic-post'>"+prevPost+"</p>").hide().fadeIn(1000);
		$('#title').replaceWith("<h2 class='topic-title'>"+prevTitle+"</h2>");
	}
	
	$(this).prev().replaceWith("<img src='"+baseURL+"/public/img/edititem.png' class='edit-item'>");
	$(this).replaceWith("<img src='"+baseURL+"/public/img/deleteitem.png' class='delete-item'>");

	$('.edit-item').click(editClicked);
	$('.delete-item').click(deleteClicked);

}

/* show fields for user to update their password */
function changePass() {
	// toggle form show/hide on button click
	if ($("#chPass").is(":visible")){
        $("#chPass").fadeOut(1000);
    } else {
        $("#chPass").fadeIn(1000);
    }
}

/* validates form, updates the user's password in the database */
function updatePassword() {
	// user input
	var currentPass = $('#currPass').val();
	var newPass = $('#newPass').val();
	var newPass2 = $('#newPass2').val();

	// if all fields not filled in
	if (currentPass == '' || newPass == '' || newPass2 == '') {
		alert('Please enter text in all fields.');
	}

	// if new passwords don't match
	else if (newPass != newPass2) {
		alert("New passwords don't match.");
	}

	// if not any of these conditions, we're good
	else {
		$.ajax({    
	        type: "POST",
	        url: baseURL+'/profile/update/', 
	        data: {
	    		'newPass': newPass,
	    		'oldPass': currentPass
	    	},                      
	      	success: function(data) { 
	      		$("#chPass").hide();
	      		alert('Password successfully updated!');
	      		$('#currPass').val('');
				$('#newPass').val('');
				$('#newPass2').val('');
			},  
			error: function(data) {
				alert(err);
				$('#currPass').val('');
				$('#newPass').val('');
				$('#newPass2').val('');
			}                  
	    });
	}
}
