//Document.Ready equivilant
$(function() {

  //event listeners
	$('#login').click(loginClicked);
	$('#signup').click(signupClicked); 
	$('.exit').click(exitClicked);
	$('#logout').click(logoutClicked);
	$('#submit-response').click(submitReply);
	$('.edititem').click(function() {
		var text = $(this).siblings('p').filter('editable');
		var val = text.innerHTML;
		text.replaceWith("<textarea class='editing'>"+val+"</textarea>";
		$(this).replaceWith("<button class='submitedit'>Save</button>");
		
	});
	$('.submitedit').click(function() {
		if ($(this).parent().attr('class') == 'reply') {
			editReply($(this).siblings('input').filter('.hidden-id').val(), $(this).parent());
		}
		$(this).replaceWith("<img src='<?=IMAGES?>/edititem.png' class='edititem'>");
	});

	//subheader menu control

});

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
		  			alert(data.status);
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
      if(!checkString(arguments[i]) || /\s/.test(arguments[i]) || arguments[0].length > 10) {
    		$('#pass').val('');
				$('.popsignup').append('<p> Please fill out all information (username 1-10 chars)</p>');
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

function deleteClicked(id) {
	//use this->syntax for parameter when each listener is created
	/*		<div class="background-fade-red"></div>
		<form class="popup-red" action="baseURL/deleteProduct/process/" method="POST">
			<h2 id="confirm"> Are you sure you would like to get rid of this beautiful discussion forever??? </h2>
				<input id="tid" type="text" name="pid" value="id" readonly>
				<input id="submit" type="submit" name="submit" value="DESTROY IT NOW!">
				<input id="letitlive" type="button" value="WAIT... let it live">
		</form>*/
}

function submitReply() {

	var post = $('#response').val();
	var topic_id = $('#topic.hidden-id').val();
	$.ajax({    
		type: "POST",
	    url: baseURL+'/addReply/process', 
    	data: {
    		'post': post,
    		'topic_id': topic_id
    	},      
      	dataType: 'html',                   
      	success: function(data){
  			if(data.status == 1){
  				$('#replies').prepend(data);
  			}
		},  
		error: function () {
			alert(data.status);
		}                                 
    });
}

function editReply(id, replyVar) {
	
	var post = replyVar.find('.editing').val();
	
	$.ajax({    
		type: "POST",
	    url: baseURL+'/editReply/process/'+id, 
    	data: {
    		'post': post,
    	},      
      	dataType: 'json',                   
      	success: function(data){
  			if(data.status == 1){
  				replyVar.find('.editing').replaceWith("<p class='editable'>"data.post"</p>");
  			}
		},  
		error: function () {
			alert(data.status);
		}                                 
    });
}


























