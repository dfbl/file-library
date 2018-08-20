<?php
/// Connect to password_compat
require_once("/vendor/autoload.php");
/// Connect to database
//include("/var/www/db_connect.php");

/// Login a user
   function login() {
	if(empty($_POST['l-uname'])) {	
		trigger_error("Username field is empty");
		return false;
	}
	if(empty($_POST['l-psw'])) {
		trigger_error("Password field is empty");
		return false;
	}
	$uname = test_input($_POST['l-uname']);
	$psw = test_input($_POST['l-psw']);
	include("/var/www/db_connect.php");
	if(checkLogin($uname, $psw) && !isset($_SESSION)) {
		session_start();
		$_SESSION['login_user'] = $uname;
		lastLogin($uname);
	} else {
		return false;
	}
	return true;
   }

/// Checks whether a user is logged in
   function checkLogin($uname, $psw) {
	$query = pg_query("SELECT * FROM library_user WHERE uname='$uname'");
	if(pg_num_rows($query) == 0) {
		?><script type='text/javascript'>
			loginError.classList.remove('hide');
			loginError.classList.add('show');
			username.classList.remove('hide'); 
			username.classList.add('show');
		</script><?php
		trigger_error("Invalid username!");
		return false;
	} else {
		$row = pg_fetch_row($query);
		if(password_verify($psw, $row[4])) {
			return true;
		} else {
			?><script type='text/javascript'>
				loginError.classList.remove('hide');
				loginError.classList.add('show');
				password.classList.remove('hide'); 
				password.classList.add('show');
			</script><?php
			trigger_error("Invalid password!");
			return false;
		}
	}
   }

/// Validate user login session
   function validateSession() {
	session_start();
	if(empty($_SESSION['login_user'])) {
		return false;
	}
	return true;
   }

/// Logout
   function logout() {
	session_start();
	session_unset();
	session_destroy();
   }

/// Update last login session in database
   function lastLogin($uname) {	
	include("/var/www/db_connect.php");
	$update = "UPDATE library_user SET last_login = current_timestamp WHERE uname = '$uname'";
	pg_query($dbconn, $update);
   }

/// Register a user
   function register() {
	$hold = array();
	$form = array();
	if(isset($_POST['register'])) {
		if(validate($hold)) {		
			registrationSubmission($form, $hold);
			if(saveToDB($form)) {
				redirect("https://traininglibrary.its.navy.mil/summerproject/f/thankyou.html");
			}
		} else {
			trigger_error("Invalid registration information!");
		}
	}
   }

/// Validate registration information
   function validate(&$hold) {
	$fname = $lname = $uname = $psw = $psw2 = $email = "";
	$check = array();
    // Validate $fname
   if(empty($_POST['fname'])) {
       	trigger_error("Enter first name");
   } else {
	$fname = test_input($_POST['fname']);
	if(!preg_match("/^[a-zA-Z- ]*$/", $fname)) {
		trigger_error("fname: Only letters, hyphens, & spaces allowed");
		?><script type='text/javascript'>
			firstname.classList.remove('hide'); 
			firstname.classList.add('show');
		</script><?php
		$check[0] = 0;
	} else {
		$fname = test_input($_POST['fname']);
		$fname = standardize($fname);
		$hold['fname'] = $fname;
		$check[0] = 1;
		//echo "<br><br>fname: " . $hold['fname'] . "<br>";
	}
   }
   // Validate $lname
   if(empty($_POST['lname'])) {
	trigger_error("Enter last name");
   } else {
	$lname = test_input($_POST['lname']);
	if(!preg_match("/^[a-zA-Z- ]*$/", $lname)) {
		trigger_error("lname: Only letters, hyphens, & spaces allowed");
		?><script type='text/javascript'>
			lastname.classList.remove('hide'); 
			lastname.classList.add('show');
		</script><?php
		$check[1] = 0;
	} else {
		$lname = test_input($_POST['lname']);
		$lname = standardize($lname);
		$hold['lname'] = $lname;
		$check[1] = 1;
		//echo "lname: " . $hold['lname'] . "<br>";
	}
    }
   // Validate $uname
   if(empty($_POST['uname'])) {
	trigger_error( "Enter username");
   } else {
	$uname = test_input($_POST['uname']);
	if(!ctype_alnum($uname) || strlen($uname) < 3 || strlen($uname) > 60 ) {
		trigger_error("uname: Only alphanumeric characters are allowed (3-60)");
		?><script type='text/javascript'>
			username.classList.remove('hide'); 
			username.classList.add('show');
		</script><?php
		$check[2] = 0;
	} else {
		$hold['uname'] = $uname;
		$check[2] = 1;
		//echo "uname: " . $hold['uname'] . "<br>";
	}
   }
   // Validate $psw
   if(empty($_POST['psw'])) {
	trigger_error("Enter password");
   } else {
	$psw = test_input($_POST['psw']);
	// Password constraints
	$ucase = preg_match("@[A-Z]@", $psw);
	$lcase = preg_match("@[a-z]@", $psw);
	$num = preg_match("@[0-9]@", $psw);
	$special = preg_match("@[^\w]@", $psw);
	if(!$ucase || !$lcase || !$num || !$special || strlen($psw) < 8 || strlen($psw) > 100) {
		trigger_error("psw: One lowercase, uppercase, special char, and number req");
		?><script type='text/javascript'>
			password.classList.remove('hide'); 
			password.classList.add('show');
		</script><?php
		$check[3] = 0;
	} else {
		$check[3] = 1;
		//echo "psw: " . $psw . "<br>";
	}
   }
   // Validate $psw2
   if(empty($_POST['psw2'])) {
	trigger_error("Enter password confirmation");
   } else {
	$psw2 = test_input($_POST['psw2']);
	if(!strcmp($psw, $psw2) == 0) {
		trigger_error("psw2: Passwords do not match");
		?><script type='text/javascript'>
			password2.classList.remove('hide'); 
			password2.classList.add('show');
		</script><?php
		$check[4] = 0;
	} else {
		$hold['psw'] = $psw;
		$check[4] = 1;
		//echo "psw2: " . $psw2 . "<br>";
	}
   }
   // Validate email
   if(empty($_POST['email'])) {
	trigger_error("Enter email");
   } else {
	$email = test_input($_POST['email']);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		trigger_error("email: Invalid email");
		?><script type='text/javascript'>
			email.classList.remove('hide'); 
			email.classList.add('show');
		</script><?php
		$check[5] = 0;
	} else {
		$email = $_POST['email'];
		$hold['email'] = $email;
		$check[5] = 1;
		//echo "email: " . $hold['email'] . "<br>";
	}
   }
   // Final validation
	$size = count($check);
	for($x = 0; $x <= $size; $x++) {
	   if($check[$x] == 0) {
		?><script type='text/javascript'>
			regError.classList.remove('hide'); 
			regError.classList.add('show');		
		</script><?php
  	   }
	}
	if($check[0]==1 && $check[1]==1 && $check[2]==1 && $check[3]==1 && $check[4]==1 && $check[5]==1)	  	  {
		return true;
	} else {
		return false;
	}
}

/// Standardize input
   function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
   }

/// Standardize names
   function standardize($data) {
	if(preg_match("@[ ]@", $data)) {
		// Has a space
		$pieces = explode(" ", $data);
		for($x = 0; $x < sizeof($pieces); $x++) {
			$pieces[$x] = strtolower($pieces[$x]);
			$pieces[$x] = ucfirst($pieces[$x]);
		}
		$data = implode(" ", $pieces);
		return $data;
	}
	else if(preg_match("@[-]@", $data)) {
		// Has a hyphen
		$pieces = explode("-", $data);
		for($x = 0; $x < sizeof($pieces); $x++) {
			$pieces[$x] = strtolower($pieces[$x]);
			$pieces[$x] = ucfirst($pieces[$x]);
		}
		$data = implode("-", $pieces);
		return $data;
	}
	else {
		// Otherwise
		$data = strtolower($data);
		$data = ucfirst($data);
		return $data;
	}
   }

/// Stores registration values in an array for use
   function registrationSubmission(&$form, &$hold) {
	$form['fname'] = $hold['fname'];
	$form['lname'] = $hold['lname'];
	$form['uname'] = $hold['uname'];
	$form['psw'] = $hold['psw'];
	$form['email'] = $hold['email'];
   }

/// Save registration information to database
   function saveToDB(&$form) {
	// Check if fields are unique to database already
	if(!checkField($form, 'email')) {
		trigger_error("This email is already registered with an account.");
		alert("This email is already registered with an account.");
		return false;
	}
	if(!checkField($form, 'uname')) {
		trigger_error("This username is already in use, try another.");
		alert("This username is already in use, try another.");
		return false;
	}
	if(!insertRegInfo($form)) {
		trigger_error("Error inserting into database.");
		return false;
	}
	return true;
   }

/// Checks whether an entry is already in the database
   function checkField($form, $field_name) {
	include("/var/www/db_connect.php");
	$field_val = $form[$field_name];
	$query = "SELECT uname FROM library_user WHERE $field_name='$field_val'";
	$result = pg_query($dbconn, $query);
	if($result && pg_num_rows($result) > 0) {
		return false;
	} else {
		return true;
	}
   }

/// Insert registration information into database
   function insertRegInfo(&$form) {
	include("/var/www/db_connect.php");
	$status = pg_connection_status($dbconn);
	if($status === PGSQL_CONNECTION_BAD) {
		trigger_error("Error connecting to database!");
	}
	$hashedpsw = hash_psw($form['psw']);
	$fname = $form['fname'];
	$lname = $form['lname'];
	$uname = $form['uname'];
	$email = $form['email'];
	$insert = "INSERT INTO library_user (fname, lname, uname, password, email, created_on)
      		   VALUES ('$fname', '$lname', '$uname', '$hashedpsw', '$email', current_timestamp)";
	if(!pg_query($dbconn, $insert)) {
		trigger_error("Error inserting data into table\nQuery: $insert");
		return false;
	}
	return true;
   }

/// Hash password
   function hash_psw($password) {
	$options = array("cost"=>4);
	$hash = password_hash($password, PASSWORD_BCRYPT, $options);
	return $hash;
   }

/// Handle error messages
   function handleError($err) {
	echo "<b>ERROR:</b> $err";
	error_log("<b>ERROR:</b> $err", 0);
	die();
   }
   set_error_handler("handleError", E_USER_WARNING);

/// Javascript pop-up alert
   function alert($msg) {
	echo "<script type='text/javascript'>alert('$msg')</script>";
   }

/// Redirect to new webpage
   function redirect($url) {
	header("Location: $url");
	exit;
   }

/// Retrieve name of user
   function getNames($user) {
	include("/var/www/db_connect.php");
	$query = pg_query($dbconn, "SELECT fname, lname FROM library_user WHERE uname = '$user'");
	$row = pg_fetch_row($query);
	echo "$row[0] $row[1]";
   }

/// Retrieve user id
   function getUserID($user) {
	include("/var/www/db_connect.php");
	$query = pg_query($dbconn, "SELECT user_id FROM library_user WHERE uname = '$user'");
	$row = pg_fetch_row($query);
	$user_id = $row[0];
	return $user_id;
   }

/// Uploading files
   function upload() {
	if(isset($_POST["submit"])) {
	    $target_dir = "/var/www/html/summerproject/uploads/";
	    $f_name = $_FILES["fileToUpload"]["name"];
	    $target_file = $target_dir . basename($f_name);
	    $f_tmp = $_FILES["fileToUpload"]["tmp_name"];
	    $uploadOk = 1;
	    
	    if(is_uploaded_file($f_tmp)) {
		    // Check if file already exists
     		    if (file_exists($target_file)) {
	    		trigger_error("Sorry, file already exists.");
			?><script type='text/javascript'>
				duplicate.classList.remove('hide'); 
				duplicate.classList.add('show');		
			</script><?php
		        $uploadOk = 0;
		    } 
	
		    // Check file size
		    if ($_FILES["fileToUpload"]["size"] > 500000000) {
		        trigger_error("Sorry, your file is too large.");
			?><script type='text/javascript'>
				size.classList.remove('hide'); 
				size.classList.add('show');		
			</script><?php
		    	$uploadOk = 0;
		    }
	
		    // Allow only XML files
		    $ext = explode(".", $f_name);
		    $ext = $ext[1];
		    if($ext != "xml") {
		    	trigger_error("Sorry, only XML files are allowed.");
			?><script type='text/javascript'>
				xml.classList.remove('hide'); 
				xml.classList.add('show');		
			</script><?php
		    	$uploadOk = 0;
		    }
	
		    // Check if $uploadOk is set to 0 by an error
		    if ($uploadOk == 0) {
		    	trigger_error("Sorry, your file was not uploaded.");
		    // if everything is ok, try to upload file
		    } else {
		    	if (move_uploaded_file($f_tmp, $target_file)) {
				if(insertFile($target_file, $f_name)) {
			        	trigger_error("The file ". basename($f_name . " has been uploaded."));
					?><script type='text/javascript'>
						success.classList.remove('hide'); 
						success.classList.add('show');		
					</script><?php
				} else {
					trigger_error("Sorry, there was an error uploading your file to the databse.");
					unlink($target_file);
					?><script type='text/javascript'>
						error.classList.remove('hide');
						error.classList.add('show');
					</script><?php
				}
		    	} else {
		        	trigger_error("Sorry, there was an error uploading your file.");
				unlink($target_file);
				?><script type='text/javascript'>
					error.classList.remove('hide'); 
					error.classList.add('show');		
				</script><?php
		        }
	    	}
	    }
	}	
   }

/// Parse through XML file for values
   function parseFile(&$parse, $file) {
	// Parse through XML file
	$xmlFile = "/var/www/html/summerproject/uploads/$file";
	$sxml = new SimpleXMLElement(file_get_contents($xmlFile));
	$namespaces = $sxml->getNamespaces(true);
	$attributes = iterator_to_array($sxml->attributes());

	// Trim timestamp
	$pieces = explode("T", $attributes["timestamp"]);
	$time = $pieces[0];		

	// Return version and time
	$parse[0] = $attributes["schema"];
	$parse[1] = $time;
   }

/// Inserting file into database
   function insertFile($target_file, $f_name) {
	include("/var/www/db_connect.php");
	$status = pg_connection_status($dbconn);
	if($status === PGSQL_CONNECTION_BAD) {
		trigger_error("Error connecting to database!");
        }
	
	$user = $_SESSION["login_user"];
	$user_id = getUserID($user);
	$file = $f_name;
	$parse = array();
	parseFile($parse, $file);
	$f_version = $parse[0];
	$create_date = $parse[1];
	$f_path = $target_file;

	$insert = "INSERT INTO library_file (up_user, f_name, f_version, create_date, up_date, f_path) VALUES ('$user_id', '$file', '$f_version', '$create_date', current_timestamp, '$f_path')";

	if(!pg_query($dbconn, $insert)) {
		trigger_error("Error inserting data into table\nQuery: $insert");
		return false;
	}
	return true;
   }

/// Retrieve file from database
   function getFile($page) {
	include("/var/www/db_connect.php");
	if($page == "download.php") {
		$select = "SELECT f_name, create_date, up_date, f_version, f_path, file_id FROM library_file";
		$query = pg_query($dbconn, $select);	
		while($row = pg_fetch_row($query)) {	
			$fileURL = getFileURL($row[4]);
			$up_date = trimTimestamp($row[2]);
			echo "<tr><td><a href='$fileURL' download onclick='logDownload($row[5])'>$row[0]</a></td>";
			echo "<td>$row[1]</td>";
			echo "<td>$up_date</td>";
			echo "<td>$row[3]</td></tr>";
		}
	} else if($page == "view.php uploads") {	
		$select = "SELECT f_name, create_date, up_date, f_version, email FROM library_file A, library_user B WHERE A.up_user = B.user_id";
		$query = pg_query($dbconn, $select);
		while($row = pg_fetch_row($query)) {	
			$up_date = trimTimestamp($row[2]);
			echo "<tr><td><a href='download.php'>$row[0]</a></td>";
			echo "<td>$row[1]</td>";
			echo "<td>$up_date</td>";
			echo "<td>$row[3]</td>";
			echo "<td><a href='mailto:$row[4]'>$row[4]</a></td></tr>";
		}
	} else if($page == "view.php downloads") {	
		$select = "SELECT f_name, create_date, d_date, f_version, email FROM library_file A, library_downloads B, library_user C WHERE B.user_id = C.user_id AND B.file_id = A.file_id";
		$query = pg_query($dbconn, $select);
		while($row = pg_fetch_row($query)) {	
			$d_date = trimTimestamp($row[2]);
			echo "<tr><td><a href='download.php'>$row[0]</a></td>";
			echo "<td>$row[1]</td>";
			echo "<td>$d_date</td>";
			echo "<td>$row[3]</td>";
			echo "<td><a href='mailto:$row[4]'>$row[4]</a></td></tr>";
		}
	}
   }

/// Get file download URL
   function getFileURL($f_path) {
	$pieces = explode("/", $f_path);
	$together = array($pieces[4], $pieces[5], $pieces[6]);
	$url = implode("/", $together);
	$url = "/" . $url;
	return $url;
   }

/// Logs downloads
   if(isset($_POST['action']) && !empty($_POST['action'])) {
       $action = $_POST['action'];
       switch($action) {
          case 'logDownloadFile':
   		$file_id = $_POST['file_id'];
	   	include("/var/www/db_connect.php");
		session_start();
		$user = $_SESSION["login_user"];
		$user_id = getUserID($user);
		$insert = "INSERT INTO library_downloads (file_id, user_id, d_date) VALUES ('$file_id', '$user_id', current_timestamp)";
		if(!pg_query($dbconn, $insert)) {
			trigger_error("Error inserting data into table\nQuery: $insert");
		}
		break;
	   }
   }

/// Trim timestamps
   function trimTimestamp($timestamp) {
	$pieces = explode(" ", $timestamp);
	return $pieces[0];
   }
?>
