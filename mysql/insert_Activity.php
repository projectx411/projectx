<?php
    
    require_once 'login.php';
    /* connect to the db */
    $connection = mysql_connect($db_hostname,$db_username,$db_password);
    mysql_select_db($db_database,$connection);
    
    $error = $activityName = $categoryName = "";
    
    if (isset($_POST['activityName']))
    {
        $activityName = $_POST['activityName'];
        $categoryName = $_POST['categoryName'];
        
        if ($activityName == "" || $categoryName == "")
        {
            $error = "Please make sure all fields are filled up<br /><br />";
        }
        else
        {
            $sql="INSERT INTO Activity (activityName, categoryName) VALUES('$_POST[activityName]','$_POST[categoryName]')";
            if (mysql_query($sql,$connection))
            {
                $error = "1 record added <br>";
            }
            else
            {
                $error = "could not insert<br>";
            }
        }
    }
    echo '<span style="color:red">'.$error.'</span>';
    echo 'Inserting into Activity Table!<br><br>';
    
    
    echo '<form action="insert_Activity.php" method="post">activityName: <input type="text" name="activityName"> categoryName: <input type="text" name="categoryName"><input type="submit"></form>';
    

    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';

    


?>