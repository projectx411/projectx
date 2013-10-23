<?php
include 'header.php';

$error = $user = $pw =  $fName = $lName = "";

if(isset($_SESSION['user'])) destroy_session();

if(isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$pw = sanitizeString($_POST['password']);
	$fName = sanitizeString($_POST['fName']);
	$lName = sanitizeString($_POST['lName']);

	if($user == "" || $pw == "" || $fName == "" || $lName == "")
		$error = "Please make sure all fields are filled up<br /><br />";
	else
	{
		$result = queryMysql("SELECT * FROM users WHERE username='$user'");
		if($result->num_rows)
			$error = "Too late, that username was already taken! </br>";
		else
		{
			add_user($fName, $lName, $user, $pw);
			die("<h4>Account created </h4>please log in. <br /> <br />");
		}
	}

}

echo <<< _END
<form method='post' action='signup.php'> <span style="color:red">$error</span>
<span class='fieldname'>Username</span>
<input type="text" maxlength='16' name='user' value='$user' /> <br />
<span class='fieldname'>Password</span>
<input type="text" maxlength='16' name='password' value='$pw' /><br />
<span class='fieldname'>First Name</span>
<input type="text" maxlength='16' name='fName' value='$fName' /><br />
<span class='fieldname'>Last Name</span>
<input type="text" maxlength='16' name='lName' value='$lName' /><br />
<span class='fieldname'>&nbsp;</span> 
<input type='submit' value='Sign up' /> 
</form></div><br /></body></html>
_END;
