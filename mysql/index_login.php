
<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<style>

</style>

<title>Project X</title>
</head>
<body>

<?php
    
    if (isset($_POST['email']))
    {
        $url = 'index.php';
        header( "Location: $url" );
    }
    
    echo '<form action="index_login.php" method="post">email: <input type="text" name="email">password: <input type="text" name="pass"> <input type="submit"></form>';
    ?>
<div class="container">

<form class="form-signin">
<h2 class="form-signin-heading">Please sign in</h2>
<input type="text" class="form-control" placeholder="Email address" autofocus>
<input type="password" class="form-control" placeholder="Password">
<label class="checkbox">
<input type="checkbox" value="remember-me"> Remember me
</label>
<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

</div> <!-- /container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

</script>

</body>
</html>

