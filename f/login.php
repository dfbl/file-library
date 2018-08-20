<?php
require_once('config.php');
$uname = $psw = "";
?>
<!DOCTYPE html>
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

<div class="row">
  <div class="col-sm-12">
<div class="navi">
<ul>
  <li><a class="title"><img src=""> File Library</a>
  <li style="float:right"><a href="view.php">View Log</a></li>
  <li style="float:right"><a href="download.php">Download</a></li>
  <li style="float:right"><a href="upload.php">Upload</a></li>
  <li style="float:right"><a class="active" href="#">Login</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>
  </div>
</div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="box">
	<h1>Login to Your Account</h1>
	<div class="registration-container">
	<div>
		<p id="loginError" class="errorMessage hide">LOGIN ERROR:</p>
		<p id="username" class="errorMessage hide">- Username does not exist!</p>
		<p id="password" class="errorMessage hide">- Invalid password!</p>
	</div>
	<center>
	<form name="login" id="loginform" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return validateLogin()" method="post">
              <input type="text" name="l-uname" placeholder="Username" value="<?php print $_POST['l-uname'];?>" >
              <input type="password" name="l-psw" placeholder="Password">
	      <input type="submit" id="login" name="login" class="login loginmodal-submit" value="Login">
	</form>
	<br>New user? <a href="registration.php">Register here!</a>
	</div>
</div></div></div>
<?php 
if(isset($_POST['login'])) {
	if(login()) {
	 	// Successful login, redirect to logged in homepage
		echo "<script type='text/javascript'>window.open('login-home.php', '_self');</script>";
	}
}
 ?>
</body>
</html>
