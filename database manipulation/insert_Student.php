<?php
    
    require_once 'login.php';
    /* connect to the db */
    $connection = mysql_connect($db_hostname,$db_username,$db_password);
    mysql_select_db($db_database,$connection);
    
    $error = $email = $pass = $name = $gender = $phone = "";
    
    if (isset($_POST['name']))
    {
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        
        if ($email == "" || $pass == "" || $name == "" || $gender == "" || $phone == "")
        {
            $error = "Please make sure all fields are filled up<br /><br />";
        }
        else
        {
            $sql="INSERT INTO Student (email, password, name, gender, phoneNumber) VALUES('$_POST[email]','$_POST[pass]','$_POST[name]','$_POST[gender]','$_POST[phone]')";
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
    echo 'Inserting into Student Table!<br><br>';
    
    
    echo '<form action="insert_Student.php" method="post">email: <input type="text" name="email">password: <input type="text" name="pass">name: <input type="text" name="name">gender: <input type="text" name="gender">phone number: <input type="text" name="phone"> <input type="submit"></form>';
    
    
    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';
    
    
    
    
    ?>