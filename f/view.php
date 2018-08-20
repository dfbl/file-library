<?php
require_once("config.php");
/// Checks to see if user is logged in for access control page
if(!validateSession()) {
	redirect("/unauthorized.html");
}
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
  <script>
	function sortTable(n, tableName) {
		var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
		table = document.getElementById(tableName);
		switching = true;
		dir = "asc";
		while(switching) {
			switching = false;
			rows = table.rows;
			for(i = 1; i < (rows.length - 1); i++) {
				shouldSwitch = false;
				x = rows[i].getElementsByTagName("td")[n];
				y = rows[i + 1].getElementsByTagName("td")[n];
				if(dir == "asc") {
					if(x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
						shouldSwitch = true;
						break;
					}
				} else if(dir == "desc") {
					if(x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
						shouldSwitch = true;
						break;
					}
				}
			}
			if(shouldSwitch) {
				rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
				switching = true;
				switchcount++;
			} else {
				if(switchcount == 0 && dir == "asc") {
					dir = "desc";
					switching = true;
				}
			}
		}
	}
  </script>
  <style>
	.highlight:hover {
		background-color: #ddd;
	}
  </style>
</head>
<body>

<div class="row">
  <div class="col-sm-12">
<div class="navi">
<ul>
  <li><a class="title"><img src=""> File Library</a>
  <li style="float:right"><a href="logout.php">Logout</a></li>
  <li style="float:right"><a class="active" href="#">View Log</a></li>
  <li style="float:right"><a href="download.php">Download</a></li>
  <li style="float:right"><a href="upload.php">Upload</a></li>
  <li style="float:right"><a href="login-home.php">Home</a></li>
</ul>
  </div>
</div>
</div> 

  <div class="col-sm-12">
    <div class="box">
    <h1>View Log</h1><br>
  <p>See the logged uploads and downloads of files to and from the site.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#up">Uploaded Files</a></li>
    <li><a data-toggle="tab" href="#down">Downloaded Files</a></li>
  </ul>

  <div class="tab-content">
    <div id="up" class="tab-pane fade in active">
      <div class="table-responsive">         
            <table class="table" id="uploads">
              <thead>
                <tr>
                  <th class="highlight" onclick="sortTable(0, 'uploads')">File</th>
                  <th class="highlight" onclick="sortTable(1, 'uploads')">Date Created</th>
                  <th class="highlight" onclick="sortTable(2, 'uploads')">Date Uploaded</th>
                  <th class="highlight" onclick="sortTable(3, 'uploads')">Version</th>
		  <th class="highlight" onclick="sortTable(4, 'uploads')">Uploaded By</th>
                </tr>
              </thead>
              <tbody>
                <?php getFile("view.php uploads"); ?>
              </tbody>
            </table>
            </div>
    </div>
    <div id="down" class="tab-pane fade">
      <div class="table-responsive">         
            <table class="table" id="downloads">
              <thead>
                <tr>
                  <th class="highlight" onclick="sortTable(0, 'downloads')">File</th>
                  <th class="highlight" onclick="sortTable(1, 'downloads')">Date Created</th>
                  <th class="highlight" onclick="sortTable(2, 'downloads')">Date Downloaded</th>
                  <th class="highlight" onclick="sortTable(3, 'downloads')">Version</th>
		  <th class="highlight" onclick="sortTable(4, 'downloads')">Downloaded By</th>
                </tr>
              </thead>
              <tbody>
		<?php getFile("view.php downloads"); ?>               
              </tbody>
            </table>
          </div>
   	</div>
      </div>
    </div>
  </div>
</div>
    
</body>
</html>


