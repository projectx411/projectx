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
		<link rel="shortcut icon" href="images/favicon.ico">
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
						<th>Name</th><th>Email</th><th>Phone</th><th>Relative Match</th>
					</tr>
				</thead>

				<tbody>
					<?php
						$user_activities = mysqli_query($connection, "SELECT * FROM Student INNER JOIN Does
																	 ON Student.email=Does.email
																	 WHERE Student.email='$email'")
						or die(mysql_error());
						$similar_users = array();
						while ($row = mysqli_fetch_array($user_activities)) {
							$query = "SELECT Student.email FROM Student INNER JOIN Does
									  ON Student.email=Does.email
									  WHERE idActivity=".$row['idActivity']." AND Student.email != '$email'";
							$matched_users = mysqli_query($connection, $query)
							or die(mysql_error());
							while ($row1 = mysqli_fetch_array($matched_users)) {
								if ($similar_users[$row1['email']] == null) {
									$similar_users[$row1['email']] = 1;
								} else {
									$similar_users[$row1['email']] = $similar_users[$row1['email']] + 1;
								}
							}
							
							
						}
						arsort($similar_users);
						$most_activities_matched;
						$idx = 0;
						foreach ($similar_users as $key=>$value) {
							$user_data = mysqli_query($connection, "SELECT * FROM Student WHERE Student.email='$key'")
										 or die(mysql_error());
							while ($row = mysqli_fetch_array($user_data)) {
							
								if ($idx == 0) {
									$most_activities_matched = $value;
									$idx = 1;
								}
								$percent_match = $value / $most_activities_matched * 100;
							
								echo '<tr class="tableRow">';
								echo '<td>'.$row['name'].'</td>';
								echo '<td class="targetEmail">'.$row['email'].'</td>';
								echo '<td>'.$row['phoneNumber'].'</td>';
								
								echo '<td><div class="progress">';
								if ($percent_match > 70) {
									$bar = '<div class="progress-bar progress-bar-success"';
								} else if ($percent_match > 30) {
									$bar = '<div class="progress-bar progress-bar-warning"';
								} else {
									$bar = '<div class="progress-bar progress-bar-danger"';
								}
								echo $bar;
								echo 'role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent_match.'%;"><span style="color: #000000">';
								//echo $percent_match.'%';
								echo '</span></div></div></td><td>'.$percent_match.'%</td></tr>';
								//echo '<td>'.$percent_match.'</td></tr>';
							}
							
							
					
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
