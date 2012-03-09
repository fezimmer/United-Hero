<?
	require_once("preheader.php");
	require_login();
	$userID 		= $_SESSION['user_id'];
	$userFirstName	= $_SESSION['user_first_name'];
	$userLastName	= $_SESSION['user_last_name'];

	$creatorInfo	= qr("SELECT fldFName, fldLName, fldEmail, fldAddress, fldCity, fldState FROM tblUser WHERE pkUserID = $userID");
	$creatorName	= $creatorInfo['fldFName'];
	$creatorEmail	= $creatorInfo['fldEmail'];
	$creatorAddress	= $creatorInfo['fldAddress'];
	$creatorState 	= $creatorInfo['fldState'];

	$action = $_REQUEST['action'];
	//&& ($_POST['fldTerms'] == 1)
	if ($action == 'submitProject'){
		$imgName = basename($_FILES['fldImage']['name']);
		$imgSize = $_FILES['fldImage']['size'];
		$allowed_ext = "jpg,jpeg,gif,png,bmp";
		$match = "0";
		if($imgSize > 0) {
			$img_ext = preg_split("/\./", $imgName);
			$allowed_exts = preg_split("/\,/", $allowed_ext);
			foreach ($allowed_exts as $ext) {
				if ($ext == strtolower(end($img_ext))) {
					$match = "1"; // File is allowed
					$tmp_file = $_FILES['fldImage']['tmp_name'];
					$newFilename = make_filename_safe($imgName);
					$img_path = "project_images/" . $newFilename;
					if (!file_exists($img_path)) {
						if (move_uploaded_file($tmp_file, $img_path) == false) {
							$error_msg[] = "Error uploading image. Please check filesize.";
						}
						else {
							extract($_POST);

							$fldDesiredFundingAmount = stripNonNumeric($fldDesiredFundingAmount);
							$success = qr("INSERT INTO tblProject (fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldActualFunding, fldStatus, fldDateCreated, fkUserID, fldImage) VALUES(
															\"$fldTitle\", \"$fldDescription\", \"$fldLocation\", \"$fldDesiredFundingAmount\", \"$fldVideoHTML\", \"$fldTags\", 0, \"review\", NOW(), $userID, \"$newFilename\")");

							if ($success){
								//send email
								$email_template_params = array();
								$email_template_params['FULLNAME']  = $creatorName;

								$to_email = $creatorEmail;
								$subject = "Thank you for your project submission";

								$success = send_email_from_template("project-review-letter.html",$to_email,$subject,$email_template_params, 1, $globals['emails_from']);

								if ($success){
									$report_msg[] = "Your project was successfully added.";
								}
								else{
									$error_msg[] = "Your project was added to our database, but we could not email you a confirmation for some reason.";
								}
							}
							else {
								$error_msg[] = "There was an error adding your project.";
							}
						}
					}
					else {
						$error_msg[] = "ERROR: IMAGE ALREADY EXISTS - Please rename the file and try again";
					}
				}
			}
			if (!$match) {
				$error_msg[] = "ERROR: File type isn't allowed: $imgName";
			}
		}
		else {
			$error_msg[] = "ERROR: No project image selected.";
		}
	}//submitProject


	$numProjects = q1("SELECT COUNT(pkProjectID) FROM tblProject WHERE fkUserID = $userID");
	//most recently submitted project only
	$projInfo = qr("SELECT * FROM tblProject WHERE fkUserID = $userID ORDER BY fldDateCreated DESC LIMIT 1");

	if ($numProjects > 0){
		extract($projInfo);
		$projectID = $projInfo['pkProjectID'];

		if ($fldStatus == "review"){
			$fldStatus_text = "<b><i>UNDER REVIEW</i></b> (once approved - no editing allowed)";
			$showSubmissionReceived = true;
			if (!$creatorAddress || !$creatorState){
				$error_msg[] = "We note that you have not yet added address information to your account. Please do this via the 'edit profile' link above. This is the address to which we send a check when your project is funded.";
				$editProfileStyle = "style='color: red;'";
			}
		}
		else if ($fldStatus == "approved"){
			$fldStatus_text = "<b style='color: blue;'>APPROVED</b>";
			$showTimeRemaining = true;
		}
		else if ($fldStatus == "unapproved"){
			$fldStatus_text = "<b style='color: red;'>UNAPPROVED</b>";
		}

		$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
		$fldActualFunding			= to_money($fldActualFunding);

		$timeRemaining = getTimeRemaining($pkProjectID);
		$daysRemaining = $timeRemaining['days'];
	}


	include("header.php");

?>
		<div>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2>My Account <p style="float:right"><a href="edit-profile.php" <?=$editProfileStyle?>>edit my profile</a></h2>
			</div>

			<div class="page-Leftcol col">
				<div class="left-col-inner">
                    <?
                    	echo_msg_box();
                    	unset($report_msg);
                    	unset($error_msg);
                    	unset($_REQUEST['rep_msg']);
                    	unset($_REQUEST['$err_msg']);
                    ?>

                    <h2>Welcome, <?=$_SESSION['user_name']?>, to your United Hero Account.</h2>

					<?

					if ($numProjects > 0){

						//echo_msg_box(); //another box for not updating address profile message (if necessary)
?>

						<h3>You may review your project status below</h3>
						<p>Project Image: <br/><br/><img src="/magick.php/<?=$fldImage?>?resize(400)" width="400" alt="PROJECT IMAGE"/></p>

<?
						if ($showSubmissionReceived){
							echo "<p>Project Submission Received: <b style='color: green;'>YES</b></p>\n";
						}

						echo "<p>Project Submission Status: $fldStatus_text</p>\n";

						if ($fldAdminComments){
							echo "<p><b>Our Comments:</b> $fldAdminComments</p>";
						}

						if (!$showSubmissionReceived){
							echo "<p>Amount Funded $fldActualFunding / $fldDesiredFundingAmount</p>\n";
						}

						if ($showTimeRemaining){
							echo" <p>time remaining: $daysRemaining days</p>\n";
						}
?>
                    	<p>
                    		<?
                    			if ($fldStatus == 'review'){
                    				echo "<a href=\"edit-project.php?id=$projectID\">edit your project</a> <i style='font-size: 11px;'>(You can edit your project until its APPROVED)</i><br />\n";
                    			}
                    		?>
                    		<a href="/project.php?id=<?=$projectID?>">view your project</a><br />
                    		<?
                    			if ($fldStatus == 'unapproved'){
                    				echo "<a href=\"submit-project.php\">submit a new project</a><br />\n";
                    			}
                    		?>


                    	</p>
<?					}//else
					else{
						$submitPage = "my_account.php";
						include("proj_submission_form.inc.php");
					}
?>

				    </div>
					<!-- end inner div -->
				</div>

			<? include('sidebar.inc.php');?>
			<div style="clear:both;"> </div>

<?
	include("footer.php");
?>