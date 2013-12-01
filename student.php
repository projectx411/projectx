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
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<meta charset="utf-8">
<?php echo '<title>Project X: '.$name.'</title>'; ?>
</head>

<body>
<div class="container">
<?php echo '<h1>'.$name.'\'s Profile</h1>'; ?>
<div id="navbar"></div>
<table class="table table-hover" id="attributes">
<thead>
<tr>
<th>Attribute</th><th>Current</th>
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
<table class="table table-hover">
<thead>
<tr><th>Activites</th></tr>
<?php
	$activityArray = mysqli_query($connection, "SELECT Activity.activityName FROM Activity, Does WHERE Activity.idActivity=Does.idActivity AND Does.email='$email'");
	while($row = mysqli_fetch_array($activityArray)){
		echo '<tr>';
		echo '<td>'.$row['activityName'].'</td>';
		echo '</tr>';
	}
?>
</thead>
</table>
<a href="profile.php">Return to Homepage</a>
</div><!-- /container -->

<script>
$(function() {
	$('#navbar').load('navbar.php', function(){
		$('#tabs li').each(function() {
			$(this).removeClass('active');
		});
		$('#peopleTab').addClass('active');
	});
});
</script>
</body>
</html>