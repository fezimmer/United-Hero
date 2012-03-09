<?

	include("preheader.php");

	$error = "";

	$fldName    = $_POST['txtName'];
	$fldEmail   = $_POST['txtEmail'];
	$fldMessage = $_POST['txtMessage'];

	if ($fldName == "") {
		$error .= "No name.";
	}

	if ($fldEmail == "") {
		$error .= "No email.";
	}

	if ($fldMessage == "") {
		$error .= "No message.";
	}

	//create email string based on submitted fields
	$email_string = "";
	$email_string .= "Name: 	$fldName<br />\n";
	$email_string .= "Email: 	$fldEmail<br />\n";
	$email_string .= "Message:	$fldMessage<br />\n";

	if($error == ""){
		$to_email = $globals['contact_email'];
		//$to_email = "sdempsey@loudcanvas.com"; //uncomment for testing purposes
		$hostname = $_SERVER['HTTP_HOST'];

		$subject = "Message from United Hero Website [" . date("mdY") . "]";

		$email_template_params = array();
		$email_template_params['SITENAME'] 		= $hostname;
		$email_template_params['NOTIFICATION'] 	= $email_string;

		$IPAddress = $_SERVER['REMOTE_ADDR'];

		$success = qr("INSERT INTO tblMessage (fldName, fldEmail, fldMessage, fldIPAddress, fldDateSubmitted) VALUES (\"$fldName\", \"$fldEmail\", \"$fldMessage\", \"$IPAddress\", NOW())");

		if ($success){
			send_email_from_template("general.html",$to_email,"$subject",$email_template_params,"1",$fldEmail);
		}

		//mail($globals[contact_email], 'UH Contact Form', $contents, $headers);
	}

?>