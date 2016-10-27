//Document.Ready equivilant
$(function() {

  //event listeners
  $('#login').click(loginClicked);
  $('#signup').click(signupClicked); 
	$('.exit').click(exitClicked);
	$('#logout').click(logoutClicked);

	//subheader menu control

  //sets current date
  var date = new Date();
  var day = date.getDate();
  var month = date.getMonth() + 1;
  var year = date.getFullYear();
  $('.curr-date').html(month + '/' + day + '/' + year);
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
	  			if(data.status == 2) {
	  				window.location.replace(baseURL + "/myProducts/");
	  				return true;
	  			}
	  			else if(data.status == 1){
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
      if(!checkString(arguments[i]) || /\s/.test(arguments[i])) {
    		$('#pass').val('');
				$('.popsignup').append('<p> Please fill out all information with 1-10 chars</p>');
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
    }) 	
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
