<?php
		session_start();

		require_once 'mysql/login.php';
		//echo isset($_COOKIE["user"]);

		if (isset($_COOKIE["user"]))
            $email = $_COOKIE["user"];
        else
            header ("Location: index.php");

		$connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
		$emailArray = mysqli_query($connection, "SELECT name FROM Student WHERE email='$email'");
		$name = "";

		while ($row = mysqli_fetch_array($emailArray)) {
			$name = $row['name'];
		}

		mysqli_query($connection, "DELETE FROM Does WHERE email='$email'");
		mysqli_query($connection, "DELETE FROM Student WHERE email='$email'");
		mysqli_query($connection, "DELETE FROM Event WHERE creator='$email'");
		setcookie("user", "", time()-3600);
		header ("Location: index.php");
?>
