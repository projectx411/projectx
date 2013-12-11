<?php
    session_start();
    require_once 'mysql/login.php';
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
	$email = $_GET['email'];
	$emailArray = mysqli_query($connection, "SELECT * FROM Student WHERE email='$email'");

	$name = '';
	$gender = '';
	$phoneNumber = '';
	$password = '';
	while ($row = mysqli_fetch_array($emailArray)) {
		$name = $row['name'];
		$gender = $row['gender'];
		$phoneNumber = $row['phoneNumber'];
		$password = $row['password'];
	}
	if (isset($_COOKIE["user"]))
            $loggedEmail = $_COOKIE["user"];
        else
            header ("Location: index.php");
	$profileFlag = 0;
	if($email == $loggedEmail)
		$profileFlag = 1;

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="shortcut icon" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<meta charset="utf-8">
<?php echo '<title>'.$name.'</title>'; ?>
</head>

<body>
<div class="container">
<?php echo '<h1>'.$name.'\'s Profile</h1>'; ?>
<div id="navbar"></div>
<h3>Information</h3>
<table class="table table-hover" id="attributes">
<thead>
<tr>
<th style="width: 200px;">Attribute</th><th>Current</th>
</tr>
<tr>
<td>Email</td>
<?php echo '<td>'.$email.'</td>'; ?>
</tr>
<tr>
<td>Name</td>
<?php echo '<td>'.$name.'</td>'; ?>
</tr>
<tr>
<td>Gender</td>
<td><?php echo $gender; ?></td>
</tr>
<tr>
<td>Phone Number</td>
<td><?php echo $phoneNumber; ?></td>
</tr>
</thead>
</table>
<h3>Activities</h3>
<ul>
	<?php
		$activityArray = mysqli_query($connection, "SELECT Activity.activityName FROM Activity, Does WHERE Activity.idActivity=Does.idActivity AND Does.email='$email'");
		$noRows = true;
		while($row = mysqli_fetch_array($activityArray)){
			echo '<li><a href="activity_info_page.php?activity='.$row['activityName'].'">'.$row['activityName'].'</a></li>';
			$noRows = false;
		}
		if ($noRows)
			echo 'None... yet.';
	?>
</ul>
<a href="profile.php">Return to Homepage</a>
</div><!-- /container -->

<script>
$(function() {
	$('#navbar').load('navbar.php', function(){
		$('#tabs li').each(function() {
			$(this).removeClass('active');
		});
		<?php

			if($profileFlag == 1)
				echo "$('#userProfileTab').addClass('active');";
			else
				echo "$('#peopleTab').addClass('active');";

		?>
		
	});
});
</script>
</body>
</html>