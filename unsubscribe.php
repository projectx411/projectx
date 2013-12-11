
<?php

	session_start();
	require_once 'mysql/login.php';

	if (isset($_COOKIE["user"]))
		$email = $_COOKIE["user"];

	$connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());

	$email = $_SESSION['email'];
	$activityId = $_SESSION['activityId'];
	mysqli_query($connection, "DELETE FROM Does WHERE idActivity='$activityId' and email='$email'");
?>