<?php
    session_start();
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
            $error .= "Please make sure no empty fields remain.<br /><br />";
            if ($pw1 != $pw2)
            {
                $error .= "Passwords do not match<br /><br />";
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
<html lang="en-US">
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/signin.css">
<style>
	#gender input {margin-left: 30px; margin-right: 4px;}
</style>

<title>Project X - Register</title>
</head>
<body>

<div class="container">

<form class="form-signin" method="post" action="create.php">
<a href="index.php">Return to main page</a>
<h2 class="form-signin-heading" style="margin-top: 10px;">Register</h2>
<input class="form-control" value="<?php echo htmlspecialchars($fname);?>" type="text" name="fname" placeholder="First Name">
<input class="form-control" value="<?php echo htmlspecialchars($lname);?>" type="text" name="lname" placeholder="Last Name">
<div id="gender" style="padding: 10px;">
<span style="font-size: 16px; margin-right: 10px;">Gender</span>
<input type="radio" name="gender" value="male">Male
<input type="radio" name="gender" value="female">Female
</div>
<input class="form-control" value="<?php echo htmlspecialchars($phone);?>" type="text" name="phone" placeholder="Phone Number">
<input class="form-control" value="<?php echo htmlspecialchars($email);?>" type="text" name="email" placeholder="Email Address">
<input type="password" class="form-control" placeholder="Password" name="pw1" style="margin-bottom: -1px;">
<input type="password" class="form-control" placeholder="Confirm Password" name="pw2">
<input class="btn btn-lg btn-primary btn-block" type="submit" value="Create Account">
<?php
    echo '<span style="color:red">'.$error.'</span>';
?>
</form>

</div> <!-- /container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

</script>

</body>
</html>