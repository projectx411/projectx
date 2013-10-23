<? 
session_start();
require_once 'functions.php';
echo "<!DOCTYPE html><html> <head>";
$loggedin = false;
$user = "Guest";
if(isset($_SESSION['user']))
{
	global $user, $loggedin;
	$user = $_SESSION['user'];
	$loggedin = true;
}


echo "<title>Welcome $user </title></head><body>";
if($loggedin)
	echo "you are logged in";
else
	echo "You are not yet logged in";


?>