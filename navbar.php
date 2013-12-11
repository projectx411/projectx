	<!-- Static navbar -->
    <?php
    if (isset($_COOKIE["user"]))
            $email = $_COOKIE["user"];
        else
            header ("Location: index.php");
?>

      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="navbar-brand" href="#">ProjectX</span>
        </div>
        <div class="navbar-collapse collapse">
          <ul id="tabs" class="nav navbar-nav">
            <li id="userProfileTab"><a href="student.php?email=<?php echo $email?>">Profile</a></li>
            <li id="peopleTab"><a href="profile.php">Find People</a></li>
            <li id="profileTab"><a href="edit_profile.php">Edit Profile</a></li>
            <li id="activityTab"><a href="addactivity_v2.php">Activities</a></li>
            <li id="eventTab"><a href="events.php">Events</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>