<?php
require_once('config.php');
logout();
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
  <li><a class="title"><img src=""> BFTT Training Library</a>
  <li style="float:right"><a href="view.php">View Log</a></li>
  <li style="float:right"><a href="download.php">Download</a></li>
  <li style="float:right"><a href="upload.php">Upload</a></li>
  <li style="float:right"><a href="login.php">Login</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>
  </div>
</div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="box">
	<center>
	<h1>Successfully logged out!</h1>
	Return to the <a href="index.html">home page</a>.
	</center>
      </div>
   </div>
</div>
</body>
</html>

<script>
$(window).scroll(function() {
	if($(document).scrollTop() > 50) {
		$('nav').addClass('shrink');
	} else {
		$('nav').removeClass('shrink');
	}
);
</script>
