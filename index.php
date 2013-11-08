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
            $error = "Missing username or password!<br>";
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
            $error = 'incorrect username/password!<br>';
        }
        }
    }
?>



<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<style>

</style>

<title>Project X</title>
</head>
<body>



<div class="container">

<form method="post" action="index.php">

<h2 class="form-signin-heading">welcome!</h2>
<input type="text" class="form-control" placeholder="Email address" autofocus name="email" value="<?php echo htmlspecialchars($email); ?>" >
<input type="password" class="form-control" placeholder="Password" name="pw">

<input class="btn btn-lg btn-primary btn-block"  type="submit" value="sign in">
</form>
<?php
    echo '<br>';
    echo '<span style="color:red">'.$error.'</span>';
    
    ?>
<form method="post" action="create.php">
<input class="btn btn-lg btn-primary btn-block"  type="submit" value="create username">
</form>

</div> <!-- /container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

</script>

</body>
</html>