<?php
ob_start();
include ("includes/config.php");

// Check that all fields contain a value, if not, return an error.
$emailcheck = $_POST["email"];

if (preg_match("/^[\$|\%|\!|\@|\#|\^|\&|\*|\(|\)|\[|\]|\{|\}|\?|\<|\>|\~|\'|\"|\;|\:|\/|\\|\+|\=|\`|\,]/i", $emailcheck)) {
  
    echo "<div class=style8>Please go back and remove the special characters from your name.</div>";

} elseif (!$_POST['password'] | !$_POST['confirm'] | !$_POST['email']) {

    echo "<div class=style8>Please go back and fill in all of the required fields.</div>";

} elseif ($_POST['password'] != $_POST['confirm']) {

    echo "<div class=style8>The passwords you entered do not match</div>";

} else {

    function MakeSafe($string) {
        $user = strtolower($string);
        $user = stripslashes($string);
        $user = trim($string);
        $user = strip_tags($string);
        return $string;
    }

    $_POST['fname'] = MakeSafe($_POST['fname']);
    $_POST['lname'] = MakeSafe($_POST['lname']);
    $_POST['email'] = MakeSafe($_POST['email']);
    $_POST['password2'] = MakeSafe($_POST['password2']);
    $_POST['altmail'] = MakeSafe($_POST['altmail']);
    
    $domain_name = MakeSafe($domain_name);
    $cpanel = curl_init();
    $addr = "http://00.000.000.00:2082/frontend/$skin/mail/doaddpop.html";
    $string_values = "email=$_POST[email]&domain=$domain_name&password=$_POST[confirm]&quota=$email_quota";
    
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
    
        print "<br><b><div class=style8>Sorry that account already exists.</b><br><br><a href=javascript:history.go(-1)>Back</a></div>";
    
    } elseif (preg_match('/You must specify/i', "$info")) {
    
        echo "
		<br>
		<b><div class=style8>You must specify a password.</b>
		<br />
		<br />
		<a href=javascript:history.go(-1)>Back</a></div>";
    
    } else {
    
        echo "<div class=style8>Account Successfully Created</div>";
        echo "<br>
		<div class=style8>The email account $_POST[email]@$domain_name has now been created, with a quota of <b>$email_quota mb</b>.
		<br />
		<br /><b>POP3 Mail Server:</b> mail.$domain_name<br /><b>Username:</b> $_POST[email]@$domain_name <br/>
		<b>Password:</b> $_POST[confirm]<br /><br />
		You can also use the following web based applications:<br/> <a href=http://00.000.000.00:2095/login>Webmail login</a><br />";
    
    }
}
?>
