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
        <title>Project X</title>
    </head>
    <body>
        <div class="container">
            <?php echo '<h1 style="color:#428bca">Welcome, '.$name.'!</h1>' ?>
            <div id="navbar"></div>
            <h3>About <?php echo $activity; ?></h3>

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
                                $names = mysqli_query($connection, "SELECT Student.name FROM Student inner join Does on Student.email = Does.email inner join Activity on Does.idActivity = Activity.idActivity where activityName = '$activity'") or die(mysql_error());
                                // store the record of the "tblstudent" table into $row
                                while($row = mysqli_fetch_array($names)){
                                    // Print out the contents of the entry
                                    echo '<tr class="tableRow">';
                                    echo '<td>'.$row[0].'</td></tr>';
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
                $('#peopleTab').addClass('active');
            });
        });
    </script>

</html>