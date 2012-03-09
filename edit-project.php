<?
	require_once("preheader.php");
	require_login();

	$userID = $_SESSION['user_id'];
	$userFirstName = $_SESSION['user_first_name'];
	$userLastName = $_SESSION['user_last_name'];

	$id = $_REQUEST['id'];

	//rudimentary security
	if (!$id) header("Location: /my-account.php");
	$projectID = $id;

	$projectOwnerID = q1("SELECT fkUserID FROM tblProject WHERE pkProjectID = $id");
	if ($projectOwnerID != $userID){
		$error_msg[] = "Unauthorized attempt to edit a project which is not your own. This incident has been recorded.";
	}


	$action = $_REQUEST['action'];
	if ($action == 'updateProject'){
		extract($_POST);

		$fldDesiredFundingAmount = stripNonNumeric($fldDesiredFundingAmount);

		$success = qr("UPDATE tblProject SET
						fldTitle 				= \"$fldTitle\",
						fldDescription 			= \"$fldDescription\",
						fldLocation 			= \"$fldLocation\",
						fldDesiredFundingAmount = $fldDesiredFundingAmount,
						fldVideoHTML 			= \"$fldVideoHTML\",
						fldTags 				= \"$fldTags\"
						WHERE pkProjectID = $projectID;");

		//$phoneNumSuccess = qr("UPDATE tblUser SET fldPhone = \"$fldPhoneNumber\" WHERE pkUserID = $userID");

		if ($success){
			$msg = "Your project was successfully updated";
			//$report_msg[] = $msg;
			header("Location: my_account.php?rep_msg=$msg");
		}
		else{
			$error_msg[] = "There was an error updating your project. Did you change any information?";
		}
	}

	$numProjects = q1("SELECT COUNT(pkProjectID) FROM tblProject WHERE pkProjectID = $id");

	include("header.php");

?>
		<div>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2><a href="/my_account.php">My Account</a></h2>
			</div>

			<div class="page-Leftcol col">
				<div class="left-col-inner">
                    <? echo_msg_box();?>

                    <h2>Edit Your Project</h2>

					<?

					if ($numProjects == 1){
						//$projInfo = qr("SELECT * FROM tblProject WHERE fkUserID = $userID");
						$projInfo = qr("SELECT * FROM tblProject WHERE pkProjectID = $id");
						extract($projInfo);

						if ($fldStatus == "review"){
							$fldStatus_text = "under review";
						}

						$editing = true;

						if ($fldStatus == 'approved'){
							echo "<div><p style='color: red;'>Your Project is live and cannot be edited.</p><p style='color: red;'>If a project creator needs to change their video or any other info you will need to send change requests to us by an email.</div>";
						}
						else{
							$submitPage = "edit-project.php";
							include("proj_submission_form.inc.php");
						}

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