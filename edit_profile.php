<?php
	session_start();
	require_once 'mysql/login.php';
	$connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
	$email = $_SESSION['email'];
	$emailArray = mysqli_query($connection, "SELECT * FROM Student WHERE email='$email'");
	
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
		<meta charset="utf-8">
		<title>Project X: Edit Profile</title>
	</head>
	
	<body>
		<div class="container">
			<h1>Edit Profile</h1>
			<table class="table table-hover" id="attributes">
				<thead>
					<tr>
						<th>Attribute</th><th>Edit</th>
					</tr>
					<?php
						echo '<tr><td>Email</td><td>'.$email.'</td><td><button style="width:150px" class="btn btn-primary data-toggle="modal" data-target="#myModal"">Edit Email</button></td></tr>';
						echo '<tr><td>Name</td><td>'.$name.'</td><td><button style="width:150px" type="button" class="btn btn-primary">Edit Name</button></tr>';
						echo '<tr><td>Gender</td><td>'.$gender.'</td><td><button style="width:150px" type="button" class="btn btn-primary">Edit Gender</button></tr>';
						echo '<tr><td>Phone Number</td><td>'.$phoneNumber.'</td><td><button style="width:150px" type="button" class="btn btn-primary">Edit Phone Number</button></tr>';
						echo '<tr><td>Password</td><td>'.$phoneNumber.'</td><td><button style="width:150px" type="button" class="btn btn-primary">Edit Password</button></tr>';
					?>
				</thead>
				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Modal title</h4>
							</div>
							<div class="modal-body">
								...
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</table>
		</div><!-- /container -->
		<script src="https://code.jquery.com/jquery.js"></script>
    		<script src="js/bootstrap.js"></script>
	</body>
</html>
