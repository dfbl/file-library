<?php
require_once("config.php");
//register();
?>

<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Roboto">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body> 
<div class="row"><div class="col-sm-12"><div class="navi">
	<ul>
	<li><a class="title"><img src=""> File Library</a>	
	<li style="float:right"><a class="nav" href="view.php">View Log</a></li>
	<li style="float:right"><a class="nav" href="download.php">Download</a></li>
	<li style="float:right"><a class="nav" href="upload.php">Upload</a></li>
	<li style="float:right"><a class="nav" href="login.php">Login</a></li>
	<li style="float:right"><a class="nav" href="index.html">Home</a></li>
	</ul>
</div></div></div> 

<div class="row"><div class="col-sm-12"><div class="box">
	<h1>Register a new account</h1>
	<a href="login.php">Have an account? Login here.</a>
	<div class="registration-container">
	<div>
		<p id="regError" class="errorMessage hide">REGISTRATION ERROR:</p>
		<p id="firstname" class="errorMessage hide">- First name can only contain letters, hyphens, and spaces!</p>
		<p id="lastname" class="errorMessage hide">- Last name can only contain letters, hyphens, and spaces!</p>
		<p id="username" class="errorMessage hide">- Username must meet validation requirements!</p>
		<p id="password" class="errorMessage hide">- Password must meet validation requirements!</p>
		<p id="password2" class="errorMessage hide">- Password confirmation must match!</p>
		<p id="email" class="errorMessage hide">- Email is invalid!</p>

	</div>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
		<input type="text" id="fname" name="fname" placeholder="First name" value="<?php print $_POST['fname'];?>" required>
		<input type="text" id="lname" name="lname" placeholder="Last name" value="<?php print $_POST['lname'];?>" required>
		<input type="text" id="uname" name="uname" placeholder="Username" value="<?php print $_POST['uname'];?>" required>
			<div class="req" id="req1" style="display:none;">
				<p id="ulength" class="req invalid">- At least 3 characters long</p>
				<p id="numlet" class="req invalid">- Can only contain letters and numbers</p>
		            </div> 
		<input type="password" id="psw" name="psw" placeholder="Password" required>
	       		 <div class="req" id="req2" style="display:none;">
			        <p id="length" class="req invalid">- At least 8 characters long</p>
				<p id="letter" class="req invalid">- Contains at least one lowercase letter</p>
				<p id="capital" class="req invalid">- Contains at least one uppercase letter</p>
				<p id="number" class="req invalid">- Contains at least one number</p>
			  	<p id="special" class="req invalid">- Contains at least one special character</p>
                	</div>
		<input type="password" id="psw2" name="psw2" placeholder="Confirm password" required>
			<div class="req" id="req3" style="display:none;">
				<p id="check" class="req invalid">- Passwords match</p>
	        	</div>
		<input type="text" id="email" name="email" placeholder="Email" value="<?php print $_POST['email'];?>" required>
			<div class="req" id="req4" style="display:none;">
				<p id="em" class="req invalid">- Email is valid</p>
	        	</div>
		<input type="submit" name="register" class="login loginmodal-submit" value="Submit">
</form></div></div></div></div>

<?php register(); ?>

<script>
var myUname = document.getElementById("uname");
var myPsw = document.getElementById("psw");
var myEmail = document.getElementById("email");

var ulength = document.getElementById("ulength");
var numlet = document.getElementById("numlet");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var special = document.getElementById("special");
var length = document.getElementById("length");
var check = document.getElementById("check");
var em = document.getElementById("em");

// When user clicks on the field, toggle requirements box
	// Toggle #uname
	$(document).ready(function(){
	    $("#uname").focus(function(){
		$("#req1").slideDown("fast");
	    });
	});
	$(document).ready(function(){
	    $("#uname").blur(function(){
		$("#req1").slideUp("fast");
	    });
	});
	// Toggle #psw
	$(document).ready(function(){
	    $("#psw").focus(function(){
		$("#req2").slideDown("fast");
	    });
	});
	$(document).ready(function(){
	    $("#psw").blur(function(){
		$("#req2").slideUp("fast");
	    });
	});
	// Toggle #psw2
	$(document).ready(function(){
	    $("#psw2").focus(function(){
		$("#req3").slideDown("fast");
	    });
	});
	$(document).ready(function(){
	    $("#psw2").blur(function(){
		$("#req3").slideUp("fast");
	    });
	});
	// Toggle #email
	$(document).ready(function(){
	    $("#email").focus(function(){
		$("#req4").slideDown("fast");
	    });
	});
	$(document).ready(function(){
	    $("#email").blur(function(){
		$("#req4").slideUp("fast");
	    });
	});
// When the user starts to type something inside the field
// Username validation
function unameVal() {
  // Validate length
  if(myUname.value.length >= 3) {
    ulength.classList.remove("invalid");
    ulength.classList.add("valid");
  } else {
    ulength.classList.remove("valid");
    ulength.classList.add("invalid");
  }  
  // Validate numbers and letters
  var numbersLetters = /^[0-9a-zA-Z]+$/g;
  if(myUname.value.match(numbersLetters)) {  
    numlet.classList.remove("invalid");
    numlet.classList.add("valid");
  } else {
    numlet.classList.remove("valid");
    numlet.classList.add("invalid");
  }
}
$(document).ready(function() {
  $("#uname").keyup(unameVal);
});


// Password validation
function pswVal() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myPsw.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myPsw.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myPsw.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate special characters
  var specialCharacters = /[@#\-_$%^&+=?*!]/g;
  if(myPsw.value.match(specialCharacters)) {  
    special.classList.remove("invalid");
    special.classList.add("valid");
  } else {
    special.classList.remove("valid");
    special.classList.add("invalid");
  }
  
  // Validate length
  if(myPsw.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
$(document).ready(function() {
  $("#psw").keyup(pswVal);
});

// Password match validation
function pswCheck() {
var psw1 = document.getElementById("psw").value;
var psw2 = document.getElementById("psw2").value;
  if(psw1 == "") {
    check.classList.remove("valid");
    check.classList.add("invalid");
  } else {
    if(psw1 == psw2) {
      check.classList.remove("invalid");
      check.classList.add("valid");
    } else {
      check.classList.remove("valid");
      check.classList.add("invalid");
    }
  }
}
$(document).ready(function() {
  $("#psw, #psw2").keyup(pswCheck);
});

// Email validation
var emailCheck = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
myEmail.onkeyup = function() {
  if(myEmail.value.match(emailCheck)) {
    em.classList.remove("invalid");
    em.classList.add("valid");
  } else {
    em.classList.remove("valid");
    em.classList.add("invalid");
  }
}

// Page transitions
$(document).ready(function(){
  $(".nav").click(function(){
    $(".box").fadeOut();
  });
});
</script>
</body>
</html>
