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
    $activity = $_SESSION['activity'];

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
  </head>

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
            <li class="active"><a href="#">Link</a></li>
            <li><a href="#">Add Event</a></li>
            <li><a href="edit_profile.php">Edit Profile</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>

      <h3>Activities</h3>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Category</th><th>Activity</th><th>People</th>
          </tr>
        </thead>

        <tbody>
          <?php
            $categories = mysqli_query($connection,'Select distinct categoryName From Activity')
            or die(mysql_error());
            // store the record of the "tblstudent" table into $row

            while($row = mysqli_fetch_array($categories)){
              // Print out the contents of the entry
              echo '<tr>';
              echo '<td>'.$row[0].'</td><td>';
              $activities = mysqli_query($connection,"Select distinct activityName From Activity where categoryName='$row[0]'");
              echo '<table>';
              while($row2 = mysqli_fetch_array($activities))
              {
                echo '<tr>';
              echo '<td>'.$row2[0].'</td>';
              echo '</tr>';
              }
              echo '</table></td></tr>';
            }
          ?>
          </tr>
        <tbody>
      </table>
    </div><!-- /container -->
  </body>
</html>