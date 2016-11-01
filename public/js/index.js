//Document.Ready equivilant
$(function() {
	//mapInit();

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

	//event listeners for editing and removing content	
	$('.edit-item').click(editClicked);
	$('.delete-item').click(deleteClicked);
	//subheader menu control


});

function mapInit() {
//////////////////////////////////////////////////////////////////
	//stuff happens
	var mapMarker;
	if (pageName == 'Explore'){
		var mapObj = new GMaps({
			div: '#map-large',
			lat: 37.229592,
			lng: -80.413960
		});
		//ajax call
		$.ajax({    
		type: "POST",
	    url: baseURL+'/exploreMap/',      
      	dataType: 'json',
      	success: function(data){
				console.log(data);
				for (var i = 0; i < data.length; i++){
					var x = data[i][Xcoord];
					var y = data[i][Ycoord];
					mapMarker = mapObj.addMarker({
						lat: x,
						lng: y,
						title: data[i][title]
					});
				}
			},  
		error: function (data) {
				console.log(data);
				alert(data.status);
			}                                 
		});
	}
	else if (pageName == 'Thread View') {
		var mapObj = new GMaps({
			el: '#map',
			lat: 37.229592,
			lng: -80.413960,
			click: function(e) {
				mapObj.removeMarker(mapMarker);
				var LAT = e.latLng.lat();
				var LNG = e.latLng.lng();
				mapMarker = mapObj.addMarker({
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
	  				$('.popup').append('<p> Incorrect Username or Password. Please try again! (1-10 chars)</p>')
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
 * @name verifyCredentials
 * This function will verify user credentials before submitting a database query
 * @param {string} username the user to find.
 * @param {string} password the password that was entered.
 * @returns {boolean} true if user is a available and created
 */
function verifySignup(user, pass, mail) {
  for (var i = 0, j = arguments.length; i < j; i++){ //more parameters so I just looped through to check for invalid values instead
      if(!checkString(arguments[i]) || /\s/.test(arguments[i]) || arguments[0].length > 16) {
    		$('#pass').val('');
				$('.popsignup').append('<p> Please fill out all information (username 1-16 chars)</p>');
				$('.popsignup > p').delay(2000).fadeOut();
      	return false;
      }
  }
		var datastr = 'u=' + user + '&p=' + pass + '&m=' + mail;
		$.ajax({    
      type: "POST",
      url: baseURL+'/signup/process/', 
      data: datastr,      
      dataType: 'json',                   
      success: function(data){
  			if(data.status == 1){
  				verifyCredentials(user, pass);
					return true; 
  			}
  			else{
  				alert(data.status);
  				$('#pass').val('');
  				var suggestion = Math.floor((Math.random() * 10) + 1);
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
 * This function is the event listener function for the logout button and will display both rest the view to logged out mode
 */
function logoutClicked() {
	//currUser = null;
	//$('#intro').show();
	//$('h2').first().text("Popular Products");
	window.location.replace(baseURL + "/logout");
	//changeLoginMenu("guest");
}

/* 
 * @function
 * @name exitclicked
 * This function is the event listener function for the exit button and will hide the login or sign up popups
 */
function exitClicked() {
	$('.background-fade').fadeOut(1000);
	$('.popup').fadeOut(1000);
	$('.popsignup').fadeOut(1000);
	$('form').off();
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
 * puts the map into focus and blocks everything else
 */
function addLocationClicked() {
	$('.background-fade-map').fadeIn(1000);
}


/* 
 * @function
 * @name deleteClicked
 * this will create a pop up for the confirm delete box
 */
function deleteClicked() {
	//use this->syntax for parameter when each listener is created
	var type = 'Topic';
	var idString = '<input id="rid" type="hidden" name="rid" value="id">';
	if ($(this).parent().attr('class') == 'reply') {
		type = 'Reply';
		idString = '<input id="rid" type="hidden" name="tid" value="id">';
		}
	var id = $(this).siblings('input').filter('.hidden-id').eq(0).val();
	$('body').append(
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
	$.ajax({    
		type: "POST",
	    url: baseURL+'/addReply/process/', 
    	data: {
    		'post': post,
    		'topic_id': topic_id
    	},      
      	dataType: 'html',                   
      	success: function(data){
  				$(data).appendTo('#replies').hide().fadeIn(2000);
  				$("#replies").animate({ scrollTop: $('#replies').prop("scrollHeight")}, 1000);
  				$('#response').val('');
  				$('.background-fade-map').fadeOut(1000);
  				$('#no-replies').fadeOut(500);

  				$('.edit-item').click(editClicked);
  				$('.delete-item').click(deleteClicked);
			},  
		error: function () {
			alert(data);
			}                                 
    });
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
	if(info.data.param1 == 'reply') {
		$('#post').replaceWith("<p class='editable'>"+prevPost+"</p>").hide().fadeIn(1000);
	}
	
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
