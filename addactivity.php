<?php
        session_start();
        require_once 'mysql/login.php';

        if (isset($_COOKIE["user"]))
            $email = $_COOKIE["user"];
        else
            header ("Location: index.php");

        $message = "";
        #$email = $_SESSION['email'];//$email='jamuell2@illinois.edu';
        $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
        $emailArray = mysqli_query($connection, "SELECT name FROM Student WHERE email='$email'");

        if(isset($_POST['toadd']))
        {

            //echo "Its here, Its here!!!";
            $acti = $_POST['toadd'];
            $index = mysqli_query($connection, "SELECT idActivity FROM Activity WHERE activityName='$acti' ");
            $finfo = $index -> fetch_array();
            $result= mysqli_query($connection, "INSERT INTO  `projectx411_main`.`Does` (
                                            `idDoes` ,
                                            `email` ,
                                            `idActivity`
                                            )
                                            VALUES (
                                            NULL ,  '$email',  '$finfo[0]'");

            $message = "success!";
      }

      //var_dump($_POST);

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <meta charset="utf-8">
        <title>Project X: Add Activity</title>
        <style>
        	#searchButton { margin-top: 3px; padding: 3px 6px 3px 6px; }
        </style>
    </head>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/typeahead.js"></script>

    <body>
        <div class="container">
		<h1>Activities</h1>
		<div id="navbar"></div>

            <h3>Search</h3>
            <form method='post' action='addactivity.php'>
            <div id="formDiv">
            <input type="text" class="activity" name="toadd" placeholder="Start typing an activity" />
			<button id="searchButton" type="submit" class="btn btn-default">Add</button>
            <?php echo $message;?>
            </div>
            </form>
            <table class="table table-striped">
             <thead>
                    <tr>
                        <th>Activity</th><th>Category</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $activityList= array();
                        $similar_people = mysqli_query($connection, "SELECT * FROM Activity")
                        or die(mysql_error());
                        // store the record of the "tblstudent" table into $row

                        while($row = mysqli_fetch_array($similar_people)){
                            // Print out the contents of the entry
                          //   $activityList, $row.['activityName']
                            $activityList[] = $row['activityName'];
                            echo '<tr>';
                            echo '<td>'.$row['activityName'].'</td>';
                           // echo '<td>'.$activityList[0].'</td>';
                            echo '<td>'.$row['categoryName'].'</td>';
                        }

                    ?>
                    </tr>
                <tbody>
            </table>

            <h3>Your Activities</h3>
            <ul class="undefined">
                <?php
                    $acts = mysqli_query($connection, "SELECT activityName FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='$email';") or die(mysql_error());
                    while($row = mysqli_fetch_array($acts)){
                        echo '<li>'.$row['activityName'].'</li>';
                    }
                ?>
            </ul>

        </div><!-- /container -->

        <script type="text/javascript">

    $(document).ready(function() {

	$('#navbar').load('navbar.php', function(){
		$('#tabs li').each(function() {
			$(this).removeClass('active');
		});
		$('#activityTab').addClass('active');
	});

    $('input.activity').typeahead({
    name: 'accounts',
    local:<?php echo json_encode($activityList)?>

    });
    })
        </script>


    </body>
</html>
