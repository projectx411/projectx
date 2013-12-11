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

    if(isset($_POST['toadd']))
    {
        $message = '';
        $acti = $_POST['toadd'];

        // if the activity is not in the Activity database, add it with a categoryName="other"
        $checkIfActivityExists = mysqli_query($connection, "select idActivity from Activity where activityName='$acti' limit 1");
        $checkIfActivityExists = $checkIfActivityExists -> fetch_array();
        if ($checkIfActivityExists[0] == "")
        {
            $insert = mysqli_query($connection,"INSERT INTO Activity (activityName, categoryName) values ('$acti','other')");
            //$message .= $acti. " was successfully added to the list of activities under 'other'.</br>";
        }
        else
        {
            // do nothing
        }

        echo '</br>';

        // if the activity isn't assigned to $email, insert into Does
        $idActivity = mysqli_query($connection, "SELECT idActivity FROM Activity WHERE activityName='$acti'");
        $idActivity = $idActivity -> fetch_array();
        $checkIfActivityAssigned= mysqli_query($connection, "select idDoes from Does where email='$email' and idActivity='$idActivity[0]' limit 1");
        $checkIfActivityAssigned = $checkIfActivityAssigned -> fetch_array();
        if ($checkIfActivityAssigned[0] == "")
        {
            $insert = mysqli_query($connection,"INSERT INTO Does (email, idActivity) values ('$email','$idActivity[0]')");
            $cat = mysqli_query($connection, "SELECT categoryName FROM Activity WHERE idActivity='$idActivity[0]' limit 1");
            $cat = $cat -> fetch_array();
            $message .= '<div class="error" style="color:#15B318">'.$acti.' was successfully added to your profile under '.$cat[0].'.</div>';
        }
        else
        {
            $message .= '<div class="error" style="color:#B50000">'.$acti.' is already in your activities!</div>';
        }
    }

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
		<link rel="shortcut icon" href="images/favicon.ico">
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
            <form id="addActivityForm" method='post' action="activity_info_page.php?activity=<?php echo $_GET['activity']?>">
                    <div id="formDiv">
                        <div id="searchDiv">
                            <input id="searchBox" type="hidden" class="activity" name="toadd" value ="<?php echo $_GET['activity']?>"  />
                            <button id="searchButton" type="submit" class="btn btn-default">Add to your activities</button>
                        </div>
                    </div>
                </form>
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