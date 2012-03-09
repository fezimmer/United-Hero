<?
	require_once('../preheader.php');

    //param setting
    $action = $_REQUEST['action'];

    if ($_POST){
        $fldUsername = $_REQUEST['fldUsername'];
        $fldPassword = $_REQUEST['fldPassword'];

        //do test again (for hacking purposes)
        if ( validateLogin($fldUsername, $fldPassword, "tblUser", "fldEmail") ){
            //we passed the test!
            $logged_in = true;

            $userInfo = qr("SELECT pkUserID, fldFName, fldLName, fldType FROM tblUser WHERE fldEmail = \"$fldUsername\"");
            extract($userInfo);
            $_SESSION['user_name'] 	= $fldFName;
            $_SESSION['user_id'] 	= $pkUserID;
            $_SESSION['user_type'] 	= $fldType;

            $goto = "home.php";
            if ($_SESSION["return_to_page"] != ""){
            	$goto = $_SESSION["return_to_page"];
            }
			unset($_SESSION["return_to_page"]);

            header("Location: $goto");
        }
        else{
            unset($report_msg);
            $error_msg[] = "Your Username/Password combination do not match our records.";
        }
    }

    if ($action == 'logout'){
        $report_msg[] = $_SESSION['user_name'] . ", you have been logged out.";
        unset($_SESSION['user_name']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_type']);
        unset($_SESSION["return_to_page"]);
    }


    //default message when coming to page
    if (count($report_msg) == 0 && count($error_msg) == 0 && $_REQUEST['err_msg'] == ""){
    	$report_msg[] = "Enter your username/password to Login.";
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Login</title>
	<meta name="keywords" content="Login" />
	<meta name="description" content="Site Login" />
	<meta name="robots" content="all" />
	<meta name="author" content="Sean Dempsey" />
	<meta name="Copyright" content="Copyright (c) <?=date("Y")?> LCM" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="Content-Language" content="en-us" />
	<link href="css/style.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body class="login" onLoad="document.login.fldUsername.focus();">

<div class="container">
	<div id="dialog">

        <h3><a style="text-decoration: none;" href="/index.php"><?=$globals[site_name]?></a></h3>

        <div id="alert_messsge" name="alert_message"><? echo_msg_box();?></div>

        <form method="post" name="login" id="login" action="<?=$_SERVER['PHP_SELF']?>">
			<dl>
			<dt>Email:</dt>
            <dd><input type="text" name="fldUsername" id="fldUsername" /></dd>

			<dt>Password:</dt>
            <dd><input type="password" size="20" name="fldPassword" id="fldPassword" /> <span>(<a href="mailto:<?=$globals[webmaster_email]?>">I forgot my password</a>)</span></dd>

			<dd><input type='submit' value='Login'/></dd></dl>
		</form>

	</div>

</div>

</body>

</html>
<?

function validateLogin($input_username, $input_password, $table = "tblUser", $userField = "fldUsername", $type = ""){
	if ($type){
		$whereType = " AND $type = 'admin'";
	}
    $q = "SELECT COUNT(*) FROM $table WHERE $userField = \"$input_username\" AND fldPassword = \"$input_password\" $whereType";
    $r = q1($q);
    if($r > 0)
        return true;

    return false;
}
?>