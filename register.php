<?php
	require_once("preheader.php");

	$page_title = "Register an account with " . $globals['site_name'];
	$error_creating_account_msg = "There has been an error creating your account. Please contact the webmaster at <a href='mailto:$globals[contact_email]'>$globals[contact_email]</a>.";

	if ($_POST){

		// Define post fields into simple variables
		$fldFName       = $_POST['first_name'];
		$fldLName       = $_POST['last_name'];
		$fldFullName    = $fldFName . " " . $fldLName;

		$fldEmail       = $_POST['email_address'];
		$fldPhone       = $_POST['phone'];
		$fldZip         = $_POST['zipcode'];

		$fldUsername    = $_POST['user_name'];
		$fldPassword    = $_POST['password'];

		$IP_Address		= $_SERVER['REMOTE_ADDR'];

		/* Lets strip some slashes in case the user entered any escaped characters. */
		$fldFName       = stripslashes($fldFName);
		$fldLName       = stripslashes($fldLName);
		$fldEmail       = stripslashes($fldEmail);

		$terms			= $_POST['terms'];

		if (!isValidEmail($fldEmail)){
			$error_msg[] = "Your email address is not valid. Please press the signup button again and confirm you typed your email correctly.";
		}

		if (!$fldEmail || !$fldFName || !$fldLName){
			$error_msg[] = "Required fields were omitted.";
		}

		if ($terms != "1"){
			$error_msg[] = "In order to register, you must check the box titled \"I agree to the Privacy Policy and Terms & Conditions\"";
		}

		$existingUsername = q1("SELECT fldUsername FROM tblUser WHERE fldUsername = \"$fldUsername\"");
		$existingEmail = q1("SELECT fldEmail FROM tblUser WHERE fldEmail = \"$fldEmail\"");

		if ($existingUsername != ""){
			//$error_msg[] = "This username is already in the system.";
		}

		if ($existingEmail != ""){
			$error_msg[] = "This email is already used in the system so you must already have an account. Use the 'Lost Your Password?' feature.";
		}


		/* Everything has passed both error checks that we have done.*/
		if(count($error_msg) == 0){

			$fldActive = 1;
			$type = "user"; //only type for right now
			// Insert info into tblUser table.
			$success = qr("INSERT INTO tblUser (fldFName, fldLName, fldUsername, fldPassword, fldEmail, fldPhone, fldZip, fldSignupDate, fldIPAddress, fldType, fldActive) VALUES(\"$fldFName\", \"$fldLName\", \"$fldUsername\", \"$fldPassword\", \"$fldEmail\", \"$fldPhone\", \"$fldZip\", NOW(), \"$IP_Address\", \"$type\", $fldActive)");

			if(!$success){
				$error_msg[] = $error_creating_account_msg;
			}
			else {
				$the_user_id = mysql_insert_id();

				//first time creation of the account
				if ($fldActive == 0){
					if ($success){
						/* commenting out activation email and process
						//make activation code for user and insert into table
						$activation_code = $the_user_id . "-" . get_rand_char(12);
						qr("INSERT INTO tblUser_Activation (fkUserID, fldCode) VALUES($the_user_id, '$activation_code')");

						if(isset($_SESSION['return_to_page']) && $_SESSION['return_to_page'] != ''){
							$return_url = $_SESSION["return_to_page"];
							$_SESSION['return_to_page'] = '';
							$email_template_params['RETURN_URL']  = $return_url;
						}

						//send activation email
						$email_template_params = array();
						$email_template_params['USERNAME']  = $fldEmail;
						$email_template_params['PASSWORD']  = $fldPassword;
						$email_template_params['CODE']      = $activation_code;
						$email_template_params['FULLNAME']  = $fldFullName;
						$email_template_params['SITENAME']  = $globals[site_name];
						$email_template_params['TOP-HEADER-TEXT']  = "This email is being sent from unitedhero.com. It requires your action.";

						send_email_from_template("register_user_activation.html",$fldEmail,"Welcome to " . $globals[site_name],$email_template_params, 1, $globals['emails_from']);

						//show congrats and how to activate on login page
						$hide_form = true;
						$report_msg[] = "<h1>Thank You for Registering!</h1><p>Your account is <b><u>not</u></b> yet active. To <b>Activate Your Account</b>, check your email and click on the activation link inside.</p><p>If you need help email us at <strong> <a href=\"mailto:" . $globals[contact_email] . "\">" . $globals[contact_email] . "</a>.</strong>\n";
						*/

						//send activation email
						$email_template_params = array();
						$email_template_params['USERNAME']  = $fldEmail;
						$email_template_params['PASSWORD']  = $fldPassword;
						$email_template_params['FULLNAME']  = $fldFullName;
						$email_template_params['SITENAME']  = $globals[site_name];
						$email_template_params['TOP-HEADER-TEXT']  = "This email is being sent from unitedhero.com. It requires your action.";

						send_email_from_template("user_signup.html",$fldEmail,"Thank you so much for signing up for Harvard's Market at Unitedhero.com",$email_template_params, 1, $globals['emails_from']);

						login_as($fldEmail, "fldEmail");
						$full_name = $fldFName . " " . $fldLName;
						header("Location: my_account.php?rep_msg=" . $full_name . ", you are now logged in.");
					}
					else{
						$hide_form = false;
					}
				}
			}//else was a success


		}//if count error messages = 0


	}//if POST()

	include("header2.php");

	echo "<h2>Register An Account</h2><br /><p>\n";
	echo_msg_box();

	include("footer2.php");
?>
