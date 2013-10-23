<?php

require_once 'loginDB.php';

$db_server = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($db_server->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db_server->connect_errno . ") " . $db_server->connect_error;
}
//echo $db_server->host_info . "\n";

function createTable($name, $query)
{
	queryMysql("CREATE TABLE IF NOT EXISTS $name ($query)");
	echo "Table '$name' created or already exists.<br />";
}

function queryMysql($query)
{
	global $db_server;
	$result = $db_server->query($query) or die ($db_server->error);
	return $result;
}

function destroySession()
{
	$_SESSION=array();
	if(session_id() != "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '',time()-2592000,'/');
	session_destroy();

}

function sanitizeString($var)
{
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}

function add_user($fn, $sn, $un, $pw)
{
	global $db_server;
    $salt1 = "qm&h";
    $salt2 = "pg!@";
    $token = md5("$salt1$pw$salt2");
	$query = "INSERT INTO users VALUES('$fn', '$sn', '$un', '$token')"; 
	$result = mysqli_query($db_server, $query);
	if (!$result) die ("Database access failed: " . mysql_error());
}

?>
