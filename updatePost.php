<?php
	session_start();
	require_once 'mysql/login.php';
	$phone = $pass = $name = $gender = "";
	$email = $_SESSION['email'];    
	$connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
	if (isset($_POST['number'])) {
		$phone = strip_tags($_POST['number']);
		mysqli_query($connection, "UPDATE Student SET phoneNumber='$phone' WHERE email='$email'");
	}
	if (isset($_POST['pass'])) {
		$pass = strip_tags($_POST['pass']);
		mysqli_query($connection, "UPDATE Student SET password='$pass' WHERE email='$email'");
	}
	if (isset($_POST['name'])) {
		$name = strip_tags($_POST['name']);
		mysqli_query($connection, "UPDATE Student SET name='$name' WHERE email='$email'");
	}
	if (isset($_POST['gender'])) {
		$gender = $_POST['gender'];
		mysqli_query($connection, "UPDATE Student SET gender='$gender' WHERE email='$email'");
	}
?>
