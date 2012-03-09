<?
	require_once("preheader.php");

	if($_POST){
		extract($_POST);

		if ($_REQUEST['spamfilter'] != ""){
			$error_msg[] = "Your email was rejected by our spam filter; " . $_REQUEST['spamfilter'];
			exit();
		}

		$checkHuman = $_REQUEST['checkHuman']; //"say hello"
		if ($checkHuman == "hello" || $checkHuman == "hi" || $checkHuman == "\"hello\""){}
		else{
			$error_msg[] = "Your email was rejected by our 'checkHuman' spam filter.";
		}

		if(!isValidEmail($fldEmail)){
			$error_msg[] = "Please enter a valid Email Address so we can get back to you.";
		}

		//create email string based on submitted fields
		$email_string = "";
		$email_string .= "Name: 	$fldName<br />\n";
		$email_string .= "Email: 	$fldEmail<br />\n";
		$email_string .= "Message:	$fldMessage<br />\n";

		if(count($error_msg) == 0){
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
				$email_sent = true;
				$report_msg[] = "Thank you for your inquiry. Your email has been received and a United Hero employee will get back to you prompty.";
			}
			else{
				$error_msg[] = "There was an error adding your inquiry to our database. Please contact technical support.";
			}

		}//no errors
	}


	include("header.php");
?>
	     <div>
			 <div style="clear: both;"></div>

				<div class="page-Leftcol col">
					<div class="left-col-inner">
						<h2>Contact United Hero</h2>

<?
	echo_msg_box();
?>

						<p>
							Welcome to a secret part of unitedhero.com where you may ask us questions and share your personal thoughts.  Also, please share any technical problems you are having so we can get them resolved quickly.
						</p>

<?
	if (!$email_sent){
?>

						<!--h5>Subheader text?</h5-->
						<form method="post" id="contactUsForm" name="contactUsForm" action="<?=$_SERVER['PHP_SELF']?>">

						<table width="90%" align="left">
						<tr><th></th></tr>

						<tr>
							<th align="left">Your Name:</th>
						<td>
						  <input type="text" name="fldName"  size="30" value="<?=$fldName?>" />
						</td>
						</tr>

						<tr>
							<th align="left">Your Email:</th>
							<td>
							  <input type="text" name="fldEmail"  size="30" value="<?=$fldEmail?>" />
							</td>
						</tr>
						<tr valign="top">
							<th align="left">Your Message:</th>
							<td><textarea onfocus ="this.value='';" name="fldMessage" rows="6" cols="30"><? if ($message != "") echo $fldMessage; else echo "Type your message here!";?></textarea></td>
						</tr>
						<tr>
							<th align="left">Type: <i>hello</i></th>
							<td>
							  <input type="text" name="checkHuman"  size="5" maxlength="5" /> <i>(this is to make sure you're human)</i>
							</td>
						</tr>
						<tr>
							<td></td>
							<td align="left">
								<button id="emailButton" class="button button-blue" style="font-size: 14px" onClick="document.getElementById('contactUsForm').submit();">Submit</button>
								<!--input type="submit" value="Submit Form" /-->
							</td>
						</tr>

						</table>

						<fieldset style="display: none;">
							<label class='formRow required'>
								<span>Spam Filter</span>
								<input type="text" name="spamfilter" id="spamfilter" value="">
							</label>

						</fieldset>

						</form>
<?
	}
?>

					</div> <!-- end inner div -->
				</div>

				<? include('sidebar.inc.php');?>
				<div style="clear:both;"> </div>

				<? include('bottom_cta.inc.php');?>


		</div>

	<?
	include("footer.php");
?>