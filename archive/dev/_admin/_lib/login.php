<?php

$username = $_POST['username'];
$password = $_POST['password'];

if (!isset($username) || !isset($password)) {
header( "Location: index.php" );

} else {
	
	include('db_config.php');
	
	$user = addslashes($_POST['username']);
	$pass = md5($_POST['password']);
	
	$result = mysql_query("SELECT username, password FROM admin WHERE username='$user' AND password='$pass'", $connection);
	
	$rowCheck = mysql_num_rows($result);
	
	if($rowCheck > 0){
	
	$url = $_POST['from'];
	
	if ($url == '') {
		$url = '../main.php';
	}

	session_start();
	 // ini_set( "session.bug_compat_warn", "off" );
	$_SESSION['admin_is_logged_in'] = true;
	$_SESSION['admin_name'] = $username;
	header('Location: '.$url);
	exit();

} else {
	
	echo 'Incorrect login name or password. Please try again.';
  
}
}
?>
