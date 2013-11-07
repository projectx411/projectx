<?php
    
    require_once 'login.php';
    /* connect to the db */
    $connection = mysql_connect($db_hostname,$db_username,$db_password);
    mysql_select_db($db_database,$connection);
    
    $error = $email = $activity = "";
    
    if (isset($_POST['email']))
    {
        $email = $_POST['email'];
        $activity = $_POST['activity'];
        
        if ($email == "" || $activity == "")
        {
            $error = "Please make sure all fields are filled up<br /><br />";
        }
        else
        {
            $sql="INSERT INTO Does (email, idActivity) select '$_POST[email]', idActivity from Activity where activityName = '$_POST[activity]'";
            if (mysql_query($sql,$connection))
            {
                $error = "1 record added <br>";
            }
            else
            {
                echo mysql_errno($connection) . ": " . mysql_error($connection) . "\n";
                $error = "could not insert<br>";
            }
        }
    }
    echo '<span style="color:red">'.$error.'</span>';
    echo 'Inserting an activity for a student (into Does Table)!<br><br>';
    
    
    echo '<form action="insert_students_activity.php" method="post">email: <input type="text" name="email">activity: <input type="text" name="activity"><input type="submit"></form>';
    
    
    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';
    
    
    
    
    ?>