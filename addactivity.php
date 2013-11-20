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
        $name = "";

        //$_SESSION['email'] = $email;
        while ($row = mysqli_fetch_array($emailArray)) {
            $name = $row['name'];
        }

        if(isset($_POST['toadd']))
        {

            echo "Its here, Its here!!!";
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

      var_dump($_POST);
    
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <meta charset="utf-8">
        <title>Project X</title>
    </head>

<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/bootstrap.js"></script> 
<script type="text/javascript" src="js/typeahead.js"></script> 

    <body>
        <div class="container">
            <?php echo '<h1 style="color:#428bca">Welcome '.$name.'</h1>' ?>
            <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">ProjectX</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="#">Link</a></li>
            <li><a href="#">Add Event</a></li>
            <li><a href="edit_profile.php">Edit Profile</a></li>
            <li class="active"><a href="#">Add Activity</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>

            <h3>Activities You May Like</h3>
            <table class="table table-striped">
            <form method='post' action='addactivity.php'> 
            <input type="text" class = "activity" name="toadd" placeholder="Stary typing an activity" >
            <input type='submit' value='Submit' /> 
            <?php echo $message;?>

            </form>
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
                    $acts = mysqli_query($connection, "SELECT activityName FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='jamuell2@illinois.edu';") or die(mysql_error());
                    while($row = mysqli_fetch_array($acts)){
                        echo '<li>'.$row['activityName'].'</li>';
                    }
                ?>
            </ul>
            <button type="button" class="btn btn-primary" style="margin-bottom:20px;">Add Activity</button>

        </div><!-- /container -->

        <script type="text/javascript">

    $(document).ready(function() {
    $('input.activity').typeahead({
    name: 'accounts',
    local:<?php echo json_encode($activityList)?>

    });
    })
        </script>


    </body>
</html>
