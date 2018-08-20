<?php
require_once("config.php");
/// Checks to see if user is logged in for access control page
if(!validateSession()) {
	redirect("/unauthorized.html");
}
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

<div class="row">
  <div class="col-sm-12">
<div class="navi">
<ul>
  <li><a class="title"><img src=""> File Library</a>
  <li style="float:right"><a href="logout.php">Logout</a></li>
  <li style="float:right"><a href="view.php">View Log</a></li>
  <li style="float:right"><a href="download.php">Download</a></li>
  <li style="float:right"><a class="active" href="#">Upload</a></li>
  <li style="float:right"><a href="login-home.php">Home</a></li>
</ul>
  </div>
</div>
</div>
  
  <div class="col-sm-12">
    <div class="box">
        <h1>Upload File</h1><br>

	    <div id="success" class="alert alert-success alert-dismissible fade in hide">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
		<strong>Success!</strong> Your file has been uploaded!
	    </div>

	    <div id="duplicate" class="alert alert-danger alert-dismissible fade in hide">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
		<strong>Error:</strong> This file already exists!
	    </div>

	    <div id="size" class="alert alert-danger alert-dismissible fade in hide">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
		<strong>Error:</strong> File size is too large!
	    </div>

	    <div id="xml" class="alert alert-danger alert-dismissible fade in hide">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
		<strong>Error:</strong> Only XML files are allowed!
	    </div>

	    <div id="error" class="alert alert-danger alert-dismissible fade in hide">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
		<strong>Error:</strong> Your file could not be uploaded!
	    </div>

            <form method="post" enctype="multipart/form-data">
              <b>Select file to upload:</b>
                          <br><br>
              <input type="file" name="fileToUpload" id="fileToUpload">
              <br>
              <p align="right"><input type="submit" value="Upload File" name="submit" class="button"></p>
            </form>
    </div>
  </div>
</div>
<?php upload(); ?>  
</body>
</html>



