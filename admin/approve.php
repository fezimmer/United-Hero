<?
	require_once('../preheader.php');

	$projectID 		= $_REQUEST['pkProjectID'];
	$projectInfo 	= qr("SELECT fldTitle, fldDateCreated, fkUserID FROM tblProject WHERE pkProjectID = $projectID");
	$projectTitle 	= $projectInfo['fldTitle'];
	$projectDateCreated = $projectInfo['fldDateCreated'];

	$userID			= $projectInfo['fkUserID'];
	$creatorInfo	= qr("SELECT fldFName, fldLName, fldEmail FROM tblUser WHERE pkUserID = $userID");
	$creatorName	= $creatorInfo['fldFName'];
	$creatorEmail	= $creatorInfo['fldEmail'];
	$to_email 		= $creatorEmail; //where emails go

	$action = $_REQUEST['action'];

	if ($action == 'unapproveProject'){
		extract($_GET);
		$updateSuccess = qr("UPDATE tblProject SET fldStatus = \"unapproved\", fldProjectApprover = \"$fldProjectApprover\", fldAdminComments = \"$fldAdminComments\", fldApprovedDate = NOW() WHERE pkProjectID = $projectID");

		if ($updateSuccess){

			//send email
			$email_template_params = array();
			$email_template_params['FULLNAME']  = $creatorName;
			$subject = "Sorry, your project was not approved.";

			$success = send_email_from_template("project-unapproved-letter.html",$to_email,$subject,$email_template_params, 1, $globals['emails_from']);

			if ($success){
				header("Location: projects.php?show=new");
			}
			else{
				$error_msg[] = "There was an error sending the congratulations email to the project creator!";
			}
		}
		else{
			$error_msg[] = "There was an error updating the database!";
		}
	}

	if ($action == 'approveProject'){
		extract($_GET);
		$numDays = $days;
		$newDateTimestamp = strtotime("+$numDays day");
		$newDate = date("Y-m-d H:i:s", $newDateTimestamp);
		$updateSuccess = qr("UPDATE tblProject SET fldStatus = \"approved\", fldEndDate = \"$newDate\", fldProjectApprover = \"$fldProjectApprover\", fldAdminComments = \"$fldAdminComments\", fldApprovedDate = NOW() WHERE pkProjectID = $projectID");
		if ($updateSuccess){

			//send email
			$email_template_params = array();
			$email_template_params['FULLNAME']  = $creatorName;
			$email_template_params['SITELINK']  = "http://" . $globals[site_name] . "/project.php?id=$projectID";
			//$email_template_params['TOP-HEADER-TEXT']  = "This email is being sent from unitedhero.com. It requires your action.";
			$subject = "Congratulations from United Hero. Your project was approved!";

			$success = send_email_from_template("project-approved-letter.html",$to_email,$subject,$email_template_params, 1, $globals['emails_from']);

			if ($success){
				header("Location: projects.php?show=live&pkProjectID=$projectID");
			}
			else{
				$error_msg[] = "There was an error sending the congratulations email to the project creator!";
			}
		}
		else{
			$error_msg[] = "There was an error updating the database!";
		}
	}

	include("header.php");
	echo_msg_box();
?>

<style>
	.note{
		font-size: 9px;
		font-weight: normal;
	}
</style>

	<form action="<?=$_SERVER['PHP_SELF'];?>" method="GET">

<? if ($action != "unapprove"){?>

		<fieldset>
			<br />
			<legend>Approve Project <?=$projectTitle?></legend>
			<label>Project will be active for <input type="text" value="32" name="days" size="2" maxlength="3" /> days.</label>
			<label>Your Name: <input type="text" name="fldProjectApprover" value="<?=$_SESSION['user_name']?>" size="30" /> <i class="note">(you're the approver)</i>	</label>
			<label>Admin Comments: <i class="note">(optional)</i></label>
			<textarea name="fldAdminComments" style="width: 500px; height: 100px;"></textarea>
		</fieldset>
		<br />
		<input type="hidden" name="action" value="approveProject" />
		<input type="submit" name="submit" value="Approve Project" />
<?	}
	else{
?>
		<fieldset>
			<br />
			<legend><b><u>Unapprove</u></b> Project <?=$projectTitle?></legend>
			<label>Your Name: <input type="text" name="fldProjectApprover" value="<?=$_SESSION['user_name']?>" size="30" /> <i class="note">(you're the dis-approver)</i></label>
			<label>Admin Comments: <i class="note">(optional)</i></label>
			<textarea name="fldAdminComments" style="width: 500px; height: 100px;"></textarea>
		</fieldset>
		<br />
		<input type="hidden" name="action" value="unapproveProject" />
		<input type="submit" name="submit" value="Unapprove Project" />
<?
	}
?>
		<input type="hidden" name="pkProjectID" value="<?=$projectID?>" />
	</form>
	<p>Project originally submitted on <?=makeSimpleDate($projectDateCreated);?></p>

<?
    include("footer.php");
?>