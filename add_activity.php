<?php

    session_start();
    require_once 'mysql/login.php';
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
    $email = $_SESSION['email'];

    if (isset($_COOKIE["user"]))
    {
        $email = $_COOKIE["user"];
        $sql = "SELECT name FROM Student WHERE email='$email'";
        $result = mysqli_query($connection,$sql);
        $result = mysqli_fetch_row($result);
        echo '<p>welcome <b>'.$result[0].'</b>!</p>';
    }
    else
    {
        header ("Location: index.php");
    }

?>


<html>
<title>Add Activities</title>
<body>

<select name="select2" size="5" multiple="multiple" tabindex="1" style="width:20%;">


<?php

    $result = mysqli_query($connection,'Select distinct categoryName From Activity');
    
    while($row = mysqli_fetch_row($result))
    {
        echo '<option disabled value='.$row[0].'>'.$row[0].'</option>';
        
        $result2 = mysqli_query($connection,"Select activityName From Activity where categoryName = '$row[0]'");
        while($row2 = mysqli_fetch_row($result2))
        {
            echo '<option value='.$row2[0].'>'.$row2[0].'</option>';
        }
    }
    echo '</select>';
?>
<input type="submit" name="Submit" value="Submit" tabindex="2" />
</body>
</html>