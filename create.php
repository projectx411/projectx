<?php
    require_once 'mysql/login.php';
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
    
    $error = $fname = $lname = $gender = $phone = $email = $pw1 = $pw2 = "";
    
    
    if (isset($_POST['fname']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $pw1 = $_POST['pw1'];
        $pw2 = $_POST['pw2'];
        
        #echo $fname.'.'.$lname.'.'.$gender.'.'.$phone.'.'.$pw1.'.'.$pw2.'.';
        
        if ($fname == "" || $lname == "" || $gender == "" || $phone ==  "" || $email == "" || $pw1 == "" || $pw2 == "")
        {
            $error .= "Please make sure all fields are filled up!<br /><br />";
            if ($pw1 != $pw2)
            {
                $error .= "Passwords do not match!<br /><br />";
            }
        }
        else
        {
            $sql = "SELECT email FROM Student WHERE email='$email' LIMIT 1";
            $result = mysqli_query($connection,$sql);
            if ($result->num_rows == 1)
            {
                $error = "This e-mail is already registered. Please choose another e-mail.<br>";
                $email = "";
            }
            else
            {
            $name .= $fname.' '.$lname;
            #echo 'inserting<br>email: '.$email.'<br>password: '.$pw1.'<br>name: '.$name.'<br>gender: '.$gender.'<br>phoneNumber: '.$phone.'<br>';
            
            
            $sql="INSERT INTO Student (email, password, name, gender, phoneNumber) VALUES('$email', '$pw1', '$name', '$gender', '$phone')";
            $result = mysqli_query($connection,$sql);
            if ( false==$result ) {
                printf("error: %s\n", mysqli_error($connection));
            }
            else
            {
                $_SESSION['email'] = $email;
                header ("Location: profile.php");
                exit();
            }
            }
        }
    }
    
    ?>


<!DOCTYPE html>
<html>

<head>
<style type="text/css">
table
input[type="text"] {width: 95%;} /* removing this would make input not to go over cells border, but they would be too short, I want them to fit cells size */

</style>
<head>

<title>Registration</title>
<body>
<form action="index.php">
<button>Return to the main page</button><br><br>
</form>


<form method="post" action="create.php">
<br>
<table style="background:#F9F5F5">
<tr>
<td align="left"><b>name</b></td>
</tr>
<tr>
<td><input value="<?php echo htmlspecialchars($fname);?>" type="text" name="fname" placeholder="First"></td>
<td><input value="<?php echo htmlspecialchars($lname);?>"  type="text" name="lname" placeholder="Last"></td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr>
<td align="left"><b>gender</b></td>
</tr>
<tr>
<td colspan="2">
<input type="radio" name="gender" value="male">Male<br>
<input type="radio" name="gender" value="female">Female
</td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<td align="left"><b>phone number</b></td>
</tr>
<tr>
<td colspan="2"><input value="<?php echo htmlspecialchars($phone);?> "  placeholder="(xxx)-xxx-xxxx" type="text" name="phone"></td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<td align="left"><b>e-mail:</b></td>
</tr>
<tr>
<td colspan="2"><input value="<?php echo htmlspecialchars($email);?> "    type="text" name="email"></td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<td align="left"><b>choose your password</b></td>
</tr>
<tr>
<td colspan="2"><input type="password" name="pw1"></td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<td align="left"><b>confirm your password</b></td>
</tr>
<tr>
<td colspan="2"><input type="password" name="pw2"></td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr>
<br>
</table>
<input type="submit" value="Create">
</form>


<?php
    echo '<br>';
    echo '<span style="color:red">'.$error.'</span>';
    
    ?>

</body>
</html>
