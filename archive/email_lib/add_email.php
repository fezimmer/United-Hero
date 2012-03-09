<?php
ob_start();
include ("includes/config.php");

$lastname = $_GET['lastname'];
$location = $_GET['location'];
$alt_email = $_GET['alt_email'];

// Check that all fields contain a value, if not, return an error.
$emailcheck = $_GET["email"];

if (preg_match("/^[\$|\%|\!|\@|\#|\^|\&|\*|\(|\)|\[|\]|\{|\}|\?|\<|\>|\~|\'|\"|\;|\:|\/|\\|\+|\=|\`|\,]/i", $emailcheck)) {
  
    echo "<div class=style8>Please go back and remove the special characters from your name. <a href='index.php'>Back</a></div>";

} elseif (!$_GET['password'] | !$_GET['confirm'] | !$_GET['email']) {

    echo "<div class=style8>Please go back and fill in all of the required fields. <a href='index.php'>Back</a></div>";

} elseif ($_GET['password'] != $_GET['confirm']) {

    echo "<div class=style8>The passwords you entered do not match <a href='index.php'>Back</a></div>";

} else {

    function MakeSafe($string) {
        $user = strtolower($string);
        $user = stripslashes($string);
        $user = trim($string);
        $user = strip_tags($string);
        return $string;
    }

    $_GET['firstname'] = MakeSafe($_GET['firstname']);
    $_GET['lname'] = MakeSafe($_GET['lname']);
    $_GET['email'] = MakeSafe($_GET['email']);
    $_GET['password2'] = MakeSafe($_GET['password2']);
   
	 
	
    
    $domain_name = MakeSafe($domain_name);
    $cpanel = curl_init();
    $addr = "http://00.000.000.00:2082/frontend/$skin/mail/doaddpop.html";
    $string_values = "email=$_GET[email]&domain=$domain_name&password=$_GET[confirm]&quota=$email_quota";
    
    curl_setopt($cpanel, CURLOPT_URL, $addr);
    curl_setopt($cpanel, CURLOPT_TIMEOUT, 10);
    curl_setopt($cpanel, CURLOPT_POST, 1);
    curl_setopt($cpanel, CURLOPT_USERPWD, "$user:$pass");
    curl_setopt($cpanel, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($cpanel, CURLOPT_POSTFIELDS, $string_values);
    $cpanel_result = curl_exec($cpanel);
    curl_close($cpanel);
    
    $start_point = strpos($cpanel_result, 'there'); // Seek Result
    $end_point = strpos($cpanel_result, 'account.');
    $info = substr($cpanel_result, $start_point, $end_point + 8 - $start_point);
    
    if (preg_match('/problem/i', "$info")) {
    
        print "<br><b><div class=style8>Sorry that account already exists.</b><br><br><a href='index.php'>Back</a></div>";
    
    } elseif (preg_match('/You must specify/i', "$info")) {
    
        echo "
		<br>
		<b><div class=style8>You must specify a password.</b>
		<br />
		<br />
		<a href='javascript('window.location.reload(true);');'>Back</a></div>";
    
    } else {
	
			$hostname='localhost';
			$username='unitedh2_admin';
			$password='360360';
			$dbname='unitedh2_unitedhero';
			
			$their_email =  $_GET['email'].'@unitedhero.com';
			
			
			$connection = mysql_connect($hostname, $username,$password) or die ('Unable to connect!'); 
			mysql_select_db($dbname) or die ('Unable to select database!');
			
			$query = "INSERT INTO user_emailaccount_info (firstname, email_address, password, email_address_alt, lastname, location) VALUES ('".$_GET['firstname']."', '".$their_email."','".$_GET['password']."', '".$alt_email."','".$lastname."', '".$location."')";

$result=mysql_query($query); 

    
        echo "<br><br><div class=style8 style=\"font-size:16px;\">Thank you <strong>".$_GET['firstname']."</strong>, account Successfully Created";
        echo "<br><br>
		<div class=style8>Your email account<strong> $_GET[email]@$domain_name</strong> has now been created!
		<br><br>
		Click above to login to your account.</div>
		";
		
    
    }
}
?>
