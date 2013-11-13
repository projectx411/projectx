<?php
		session_start();
		require_once 'mysql/login.php';

		if (isset($_COOKIE["user"]))
            $email = $_COOKIE["user"];
        else
            header ("Location: index.php");

        #$email = $_SESSION['email'];//$email='jamuell2@illinois.edu';
		$connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
		$emailArray = mysqli_query($connection, "SELECT name FROM Student WHERE email='$email'");
		$name = "";

		//$_SESSION['email'] = $email;
		while ($row = mysqli_fetch_array($emailArray)) {
			$name = $row['name'];
		}
    
?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<meta charset="utf-8">
		<title>Project X</title>
	</head>

	<body>
		<div class="container">
			<?php echo '<h1 style="color:#428bca">Welcome '.$name.'</h1>' ?>
			<!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">ProjectX</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Link</a></li>
            <li><a href="#">Add Event</a></li>
            <li><a href="edit_profile.php">Edit Profile</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>

			<h3>People Like You</h3>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th><th>Email</th><th>Phone</th>
					</tr>
				</thead>

				<tbody>
					<?php
						$similar_people = mysqli_query($connection, "SELECT * FROM Student")
						or die(mysql_error());
						// store the record of the "tblstudent" table into $row

						while($row = mysqli_fetch_array($similar_people)){
							// Print out the contents of the entry
							echo '<tr>';
							echo '<td>'.$row['name'].'</td>';
							echo '<td>'.$row['email'].'</td>';
							echo '<td>'.$row['phoneNumber'].'</td></tr>';
						}

					?>
					</tr>
				<tbody>
			</table>

			<h3>Your Activities</h3>
			<ul class="undefined">
				<?php
					$acts = mysqli_query($connection, "SELECT activityName FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='jamuell2@illinois.edu';") or die(mysql_error());
					while($row = mysqli_fetch_array($acts)){
						echo '<li>'.$row['activityName'].'</li>';
					}
				?>
			</ul>
			<button type="button" class="btn btn-primary" style="margin-bottom:20px;">Add Activity</button>

		</div><!-- /container -->
	</body>
</html>
