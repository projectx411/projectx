<?php
    session_start();
    require_once 'mysql/login.php';
    require_once 'swift_lib/swift_required.php';
    ini_set( "display_errors", 0);
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);


    if (isset($_POST['formsubmitted'])) {

        $error;
        $fname = $lname = $gender = $phone = $email = $pw1 = $pw2 = "";

        if (empty($_POST['fname'])) {//if no first name has been supplied
            $error .= 'Please enter your first name<br>';//add to array "error"
        } else {
            $fname = $_POST['fname'];//else assign it a variable
        }

        if (empty($_POST['lname'])) {//if no last name has been supplied
            $error .= 'Please enter your last name<br>';//add to array "error"
        } else {
            $lname = $_POST['lname'];//else assign it a variable
        }

        if (empty($_POST['gender'])) {//if no gender has been supplied
            $error .= 'Please select your gender<br>';//add to array "error"
        } else {
            $gender = $_POST['gender'];//else assign it a variable
        }

        if (empty($_POST['phone'])) {//if no phone number has been supplied
            $error .= 'Please enter your phone number<br>';//add to array "error"
        } else if (strlen($_POST['phone']) != 10) {
            $error .= 'Please provide a 10-digit phone number<br>';//add to array "error"
        }

        if (empty($_POST['email'])) {
            $error .= 'Please enter your email<br>';
        } else {

            if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {
                //regular expression for email validation
                $email = $_POST['email'];
            } else {
                $error .= 'Your e-mail Address is invalid<br>';
            }
            if (array_pop(explode('@', $email)) == "illinois.edu")
                $email = $_POST['email'];
            else
                $error .= 'Only @illinois.edu e-mail accounts are allowed<br>';


        }

        if (empty($_POST['pw1']) or empty($_POST['pw2'])) {//if no password has been supplied
            $error .= 'Please enter and confirm your password<br>';//add to array "error"
        } else if ($_POST['pw1'] != $_POST['pw2']) {
            $error .= 'Passwords do not match<br>';
        }
        else {
            $pw1 = $_POST['pw1'];//else assign it a variable
            $pw2 = $_POST['pw2'];//else assign it a variable
        }


        $sql = "SELECT email FROM Student WHERE email='$email' LIMIT 1";
        $result = mysqli_query($connection,$sql);
        if ($result->num_rows == 1)
        {
            $error .= "This e-mail is already registered. Please choose another e-mail.<br>";
        }

        if (empty($error )) {


                $name .= $fname.' '.$lname;

                // generate a unique activation key
                $activation = md5(uniqid(rand(), true));

                // insert user's info into database
                $sql="INSERT INTO Student (email, password, name, gender, phoneNumber, activation) VALUES('$email', '$pw1', '$name', '$gender', '$phone', '$activation')";
                $result = mysqli_query($connection,$sql);

                if ( false==$result ) {
                    printf("error: %s\n", mysqli_error($connection));
                }
                else
                {
                    // if successfully inserted, send the activation e-mail from cs411projectx@gmail.com
                    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com',465, 'ssl')
                    ->setUsername('cs411projectx')
                    ->setPassword('dsg82bvj')
                    ;

                    $mailer = Swift_Mailer::newInstance($transport);
                    $body = " To activate your account, please click on this link:\n\n";
                    $body .= 'http://web.engr.illinois.edu/~projectx411/activate.php?email=' . urlencode($email) . "&key=$activation";
                    $message = Swift_Message::newInstance('Registration Confirmation')
                    ->setFrom(array('cs411projectx@gmail.com' => 'Project X'))
                    ->setTo(array($email => $name))
                    ->setBody($body)
                    ;

                    // Send the message
                    $result = $mailer->send($message);

                    echo '<div style="color:red" class="success">Thank you for registering! A confirmation email has been sent to '.$email.' Please click on the Activation Link to Activate your account </div>';
                }

        }
    }

    ?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="shortcut icon" href="images/favicon.ico">
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
    <h2 class="form-signin-heading" style="margin-top: 10px;">Register</h2>
    <input class="form-control" value="<?php echo htmlspecialchars($fname);?>" type="text" name="fname" placeholder="First Name" autofocus>
    <input class="form-control" value="<?php echo htmlspecialchars($lname);?>" type="text" name="lname" placeholder="Last Name">
    <div id="gender" style="padding: 10px;">
        <span style="font-size: 16px; margin-right: 10px;">Gender</span>
        <input type="radio" name="gender" value="male">Male
        <input type="radio" name="gender" value="female">Female
    </div>
    <input id="phoneNumber" class="form-control" value="<?php echo htmlspecialchars($phone);?>" type="text" name="phone" placeholder="Phone Number (Digits only)">
    <input class="form-control" value="<?php echo htmlspecialchars($email);?>" type="text" name="email" placeholder="Email Address">
    <input type="password" class="form-control" placeholder="Password" name="pw1" style="margin-bottom: -1px;">
    <input type="password" class="form-control" placeholder="Confirm Password" name="pw2">
    <input class="btn btn-lg btn-primary btn-block" type="submit" name="formsubmitted" value="Create Account">
    <div style="margin-top: 10px;"><a href="index.php">Return to main page</a></div>
<?php
echo '<span style="color:red">'.$error.'</span>';
?>

</form>

</div> <!-- /container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

    $(document).ready(function() {

    	var numDigits = 0;

		// Only allow numeric input into phone field.
		$('#phoneNumber').keydown(function(event) {

			var len = $(this).val().length;

			// Allow exceptions.
			if	(event.keyCode == 46 || event.keyCode == 9 ||
				(event.keyCode == 65 && event.ctrlKey) ||
				(event.keyCode == 67 && event.ctrlKey) ||
				(event.keyCode == 88 && event.ctrlKey) ||
				(event.keyCode == 86 && event.ctrlKey) ||
				(event.keyCode >= 35 && event.keyCode <= 45) ||
				(event.keyCode == 13) || (event.keyCode == 8)) {
					return;
			}else if (event.keyCode >= 48 && event.keyCode <= 57 && !(event.shiftKey)) {
				if (len == 10) event.preventDefault();
			}else{
				// For everything else, if it is not a number, block its functionality.
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
					event.preventDefault();
				}
			}
		});
	});

</script>

</body>
</html>
