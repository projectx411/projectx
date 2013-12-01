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
		<style>
			.tableRow { cursor: pointer; cursor: hand; }
		</style>
	</head>

	<body>
		<div class="container">
		<?php echo '<h1 style="color:#428bca">Welcome, '.$name.'!</h1>' ?>
		<div id="navbar"></div>

			<h3>People Like You</h3>
			<table class="table table-striped table-hover">
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
							echo '<tr class="tableRow">';
							echo '<td>'.$row['name'].'</td>';
							echo '<td class="targetEmail">'.$row['email'].'</td>';
							echo '<td>'.$row['phoneNumber'].'</td></tr>';
						}

					?>
				<tbody>
			</table>
		</div><!-- /container -->
	</body>


	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery.js"></script>
	<script>
		$(function() {
			$('#navbar').load('navbar.php', function(){
				$('#tabs li').each(function() {
					$(this).removeClass('active');
				});
				$('#peopleTab').addClass('active');
			});

			$('.tableRow').each(function() {
				$(this).on('click', function() {
					var e = $(this).children('.targetEmail').text();
					window.location = 'student.php?email='+e;
				});
			});
		});
	</script>
</html>
