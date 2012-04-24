<?
$page_title = "Login to " . $globals[site_name];
require("preheader.php");

$action = $_REQUEST['action'];

if($_POST['login_email'] != ''){

    //$fldUsername = $_POST['login_user_name'];
    $fldEmail 		= $_POST['login_email'];
    $fldPassword 	= $_POST['login_password'];

    if ( check_login_password($fldEmail, $fldPassword, "tblUser", "fldEmail") ){
        //do whatever here
        $logged_in = true;

        $row = qr("SELECT pkUserID, fldFName, fldLName, fldActive FROM tblUser WHERE fldEmail = \"$fldEmail\"");
        $full_name = $row[fldFName] . " " . $row[fldLName];
        $active = $row[fldActive];


        if($active == 0){
        	//user is trying to login without having completing registration yet
            $activation_code = q1("SELECT fldCode FROM tblUser_Activation WHERE fkUserID = " . $row[pkUserID]);

            $email_address = $row[fldEmail];
			//send activation email (again)
			$email_template_params = array();
			//$email_template_params['USERNAME']  = $fldUsername;
			$email_template_params['USERNAME']  = $fldEmail;

			$email_template_params['PASSWORD']  = $fldPassword;
			$email_template_params['CODE']      = $activation_code;
			$email_template_params['FULLNAME']  = $fldFullName;
			$email_template_params['SITENAME']  = $globals[site_name];
			$email_template_params['TOP-HEADER-TEXT']  = "This email is being sent from unitedhero.com. It requires your action.";
			send_email_from_template("register_user_activation.html",$email_address,"Welcome to " . $globals[site_name],$email_template_params, 1, $globals['emails_from']);
            $error_msg[] = "You must Activate Your Account. Check your email for the Activation Email. Another Activation Email has just been sent.<br><br>If you need help, please email us at $globals[contact_email].";
        }
        else{
            login_as($fldEmail, "fldEmail");
            //loginasUserType();
            header("Location: my_account.php?rep_msg=" . $full_name . ", you are now logged in.");
			$report_msg[] = "You are now logged in.";

        }//else user IS active

    }//passes login check
    else{
        $error_msg[] = "Sorry. The password you typed was incorrect.";
    }
}//if email was entered


if ($_GET['action'] == 'activate'){
	$code = $_GET['code'];
	if (!isset($code)){
		$error_msg[] = "Your code was not provided. Please click the link in the email or copy/paste the url from the email exactly.";
	}
	else{
		$userID = q1("SELECT fkUserID FROM tblUser_Activation WHERE fldCode = \"$code\"");

		if ($userID){
			$activateUser = qr("UPDATE tblUser SET fldActive = 1 WHERE pkUserID = $userID");
			if ($activateUser){
				$success = qr("DELETE FROM tblUser_Activation WHERE fkUserID = $userID");
			}
			if ($success){
				//$hide_form = true;
				$report_msg[] = "<h1>Your account has been activated and you are now logged in.</p>\n";
				login_as($userID, "pkUserID");
			}
		}
		else{
			$error_msg[] = "There is no user assoicated with this activation code. Please check the code and try the link again.";
		}

	}
}

if($action == 'logout'){
	$report_msg[] = $_SESSION[user_name] . ", you are now logged out.";
	logout();
}

//in case user forgot their password
if($_POST[fldEmail] != ''){
	$email = trim($_POST['fldEmail']);
	$userID = q1("SELECT pkUserID FROM tblUser WHERE fldEmail = \"$email\"");

	//if user exists with this email address
	if($userID != ""){
		$rand_pass = strtolower(get_rand_char(8));

		//reset pass and email it
		$success = qr("UPDATE tblUser SET fldPassword = '$rand_pass' WHERE pkUserID = $userID");

		if ($success){
			$email_template_params = array();
			$email_template_params['PASSWORD'] = $rand_pass;
			$email_template_params['SITENAME'] = $globals['domain_name'].$globals['domain_ext'];
			$subject = 'Reset Password Request - Your Account';
			$success = send_email_from_template("reset_password.html",$email,$subject,$email_template_params, 1, $globals['emails_from']);

			if(!$success){
				$error_msg[] = "Sending Email Failed. Please Contact Us at $globals[contact_email].";
			}
			else{
				$report_msg[] = "Your password has been successfully reset and sent to your email address.";
			}
		}//if success with the update
	}
	else{
		$error_msg[] = "There is no one registered with that Email Address.";
	}
}

	include("header2.php");

	echo_msg_box();

	if(!is_logged_in()){
?>

<!--body onLoad="document.login.fldUsername.focus();"-->

        <form name="login" method="post" action="login.php">
          <h1>Log In</h1>
          <table width="100%" class='form' border="0" cellspacing="0" cellpadding="4" style="font-size: 13px;">
            <tr>
              <td width="17%">Email</td>
              <td width="83%" ><input name="login_email" type="text" id="login_email"/></td>
            </tr>
            <tr>
              <td>Password</td>
              <td><input name="login_password" type="password" id="login_password"/></td>
            </tr>
            <tr >
              <td>&nbsp;</td>
              <td><input type="submit" class="submit" type="submit" value="Login"/></td>
            </tr>
          </table>
		 </form>

          <br />
          <h4><a href="#?w=780" rel="signupForm" class="poplight">No Account Yet? Register Here.</a></h4>
          <!--h4><a href="/home.php">< Go Home</a></h4-->
          <hr><br />

          <h1 id="forgotPassword">I Forgot My Password!</h1>
          <p>Enter your email address below to receive a new password. Once you log in you can change your password.</p>
          <form name="form2" method="post" action="login.php" >
            <table border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td width="27%" class="form_var">Email Address </td>
                <td width="73%"><input name="fldEmail" type="text" id="fldEmail" size="40">&nbsp;
                <input type="submit" class="submit" name="Submit" value="Submit"accesskey="2"/></td>
              </tr>
            </table>
          </form>

<?
	}//is not logged in
	else{
?>
	<!--h1>You're Logged-In</h1-->
	<h2>What Do You Want to Do?</h2>

	<ul>
		<li><a href="?action=logout">Logout</a></li>
	</ul>

<?
	}//else is logged in!

include("footer2.php");


?>
