<?php
    session_start();
    require_once 'mysql/login.php';
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
    
    $email = $error = $pw = "";
    
    
    if (isset($_POST['email']))
    {
        $email = $_POST['email'];
        $pw = $_POST['pw'];
        
        if ($email == "" || $pw == "")
        {
            $error = "Please fill out both fields. <br>";
        }
        else
        {
            
            $sql = "SELECT email FROM Student WHERE email='$email' AND password = '$pw' LIMIT 1";
            $result = mysqli_query($connection,$sql);
            if ($result->num_rows == 1)
            {
                $_SESSION['email'] = $email;
                header ("Location: profile.php");
            }
            else
            {
                $error = 'Incorrect username/password <br>';
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

</style>

<title>Project X</title>
</head>
<body>

<div class="container">

<form class="form-signin" method="post" action="index.php">

<h2 class="form-signin-heading">Welcome to Project X!</h2>
<input type="text" class="form-control" placeholder="Email address" autofocus name="email" value="<?php echo htmlspecialchars($email); ?>" >
<input type="password" class="form-control" placeholder="Password" name="pw">
<input class="btn btn-lg btn-primary btn-block" type="submit" value="Login" style="margin-bottom: 8px;">
<?php
    echo '<span style="color:red">'.$error.'</span>';
    ?>
<a href="create.php">Register</a>
</form>

</div> <!-- /container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

</script>

</body>
</html>