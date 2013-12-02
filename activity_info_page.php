<?php
    session_start();
    require_once 'mysql/login.php';

    if (isset($_COOKIE["user"]))
        $email = $_COOKIE["user"];
    else
        header ("Location: index.php");

    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
    $emailArray = mysqli_query($connection, "SELECT name FROM Student WHERE email='$email'");
    $name = "";

    //$_SESSION['email'] = $email;
    while ($row = mysqli_fetch_array($emailArray)) {
        $name = $row['name'];
    }

    $activity = $_GET['activity'];

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <meta charset="utf-8">
        <style>
			.tableRow { cursor: pointer; cursor: hand; }
        </style>
        <?php echo '<title>'.$activity.'</title>'; ?>
    </head>
    <body>
        <div class="container">
            <?php echo '<h1>About '.$activity.'</h1>' ?>
            <div id="navbar"></div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>People interested in <?php echo $activity; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $names = mysqli_query($connection, "SELECT Student.* FROM Student inner join Does on Student.email = Does.email inner join Activity on Does.idActivity = Activity.idActivity where activityName = '$activity'") or die(mysql_error());
                                // store the record of the "tblstudent" table into $row
                                while($row = mysqli_fetch_array($names)){
                                    // Print out the contents of the entry
                                    echo '<tr class="tableRow">';
                                    echo '<td>'.$row['name'].'</td>';
									echo '<td class="targetEmail" hidden>'.$row['email'].'</td></tr>';
                                }

                            ?>
                        <tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?php echo $activity; ?> events</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                    </table>
                </div>
            </div>
    		<input class="btn" type="submit" value="Create an event (soon to come! :D)">
        </div>
    </body>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script>
        $(function() {
            $('#navbar').load('navbar.php', function(){
                $('#tabs li').each(function() {
                    $(this).removeClass('active');
                });
                $('#activityTab').addClass('active');
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