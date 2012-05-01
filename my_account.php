<?
	require_once("preheader.php");
	require_login();
	$userID 		= $_SESSION['user_id'];
	$userFirstName	= $_SESSION['user_first_name'];
	$userLastName	= $_SESSION['user_last_name'];

        $projInfo = qr("SELECT * FROM tblProject WHERE fkUserID = $userID ORDER BY fldDateCreated DESC LIMIT 1");
        extract($projInfo);
        $id = $projInfo['pkProjectID'];

	//reward readonly bool
	$readOnlyKeyword = true;

	$creatorInfo	= qr("SELECT fldFName, fldLName, fldEmail, fldAddress, fldCity, fldState FROM tblUser WHERE pkUserID = $userID");
	$creatorName	= $creatorInfo['fldFName'];
	$creatorEmail	= $creatorInfo['fldEmail'];
	$creatorAddress	= $creatorInfo['fldAddress'];
	$creatorState 	= $creatorInfo['fldState'];

	//reward info
	$rewardsTitle[1]    = $_POST["rewardTitle1"];
	$rewardsTitle[2]    = $_POST["rewardTitle2"];
	$rewardsTitle[3]    = $_POST["rewardTitle3"];
	$rewardsTitle[4]    = $_POST["rewardTitle4"];
	$rewardsTitle[5]    = $_POST["rewardTitle5"];
	$rewardsTitle[6]    = $_POST["rewardTitle6"];
	$rewardsTitle[7]    = $_POST["rewardTitle7"];

	$rewardsDesc[1]    = $_POST["rewardDesc1"];
	$rewardsDesc[2]    = $_POST["rewardDesc2"];
	$rewardsDesc[3]    = $_POST["rewardDesc3"];
	$rewardsDesc[4]    = $_POST["rewardDesc4"];
	$rewardsDesc[5]    = $_POST["rewardDesc5"];
	$rewardsDesc[6]    = $_POST["rewardDesc6"];
	$rewardsDesc[7]    = $_POST["rewardDesc7"];

	$rewardsSupport[1]    = $_POST["rewardSupport1"];
	$rewardsSupport[2]    = $_POST["rewardSupport2"];
	$rewardsSupport[3]    = $_POST["rewardSupport3"];
	$rewardsSupport[4]    = $_POST["rewardSupport4"];
	$rewardsSupport[5]    = $_POST["rewardSupport5"];
	$rewardsSupport[6]    = $_POST["rewardSupport6"];
	$rewardsSupport[7]    = $_POST["rewardSupport7"];

	$rewardsMonth[1]    = $_POST["rewardMonth1"];
	$rewardsMonth[2]    = $_POST["rewardMonth2"];
	$rewardsMonth[3]    = $_POST["rewardMonth3"];
	$rewardsMonth[4]    = $_POST["rewardMonth4"];
	$rewardsMonth[5]    = $_POST["rewardMonth5"];
	$rewardsMonth[6]    = $_POST["rewardMonth6"];
	$rewardsMonth[7]    = $_POST["rewardMonth7"];

	$rewardsYear[1]    = $_POST["rewardYear1"];
	$rewardsYear[2]    = $_POST["rewardYear2"];
	$rewardsYear[3]    = $_POST["rewardYear3"];
	$rewardsYear[4]    = $_POST["rewardYear4"];
	$rewardsYear[5]    = $_POST["rewardYear5"];
	$rewardsYear[6]    = $_POST["rewardYear6"];
	$rewardsYear[7]    = $_POST["rewardYear7"];

	$rewardsAvail[1]    = $_POST["numAvailable1"];
	$rewardsAvail[2]    = $_POST["numAvailable2"];
	$rewardsAvail[3]    = $_POST["numAvailable3"];
	$rewardsAvail[4]    = $_POST["numAvailable4"];
	$rewardsAvail[5]    = $_POST["numAvailable5"];
	$rewardsAvail[6]    = $_POST["numAvailable6"];
	$rewardsAvail[7]    = $_POST["numAvailable7"];

	$rewardsImage[1]    = $_POST["rewardImage1"];
	$rewardsImage[2]    = $_POST["rewardImage2"];
	$rewardsImage[3]    = $_POST["rewardImage3"];
	$rewardsImage[4]    = $_POST["rewardImage4"];
	$rewardsImage[5]    = $_POST["rewardImage5"];
	$rewardsImage[6]    = $_POST["rewardImage6"];
	$rewardsImage[7]    = $_POST["rewardImage7"];

	$rewardsID[1]    = $_POST["rewardpkID1"];
	$rewardsID[2]    = $_POST["rewardpkID2"];
	$rewardsID[3]    = $_POST["rewardpkID3"];
	$rewardsID[4]    = $_POST["rewardpkID4"];
	$rewardsID[5]    = $_POST["rewardpkID5"];
	$rewardsID[6]    = $_POST["rewardpkID6"];
	$rewardsID[7]    = $_POST["rewardpkID7"];

	$rewardsDelete[1]    = $_POST["deleteReward1"];
	$rewardsDelete[2]    = $_POST["deleteReward2"];
	$rewardsDelete[3]    = $_POST["deleteReward3"];
	$rewardsDelete[4]    = $_POST["deleteReward4"];
	$rewardsDelete[5]    = $_POST["deleteReward5"];
	$rewardsDelete[6]    = $_POST["deleteReward6"];
	$rewardsDelete[7]    = $_POST["deleteReward7"];

	//count Number of rewards
	$finalCount = rewardCount($rewardsTitle, $rewardsDesc, $rewardsSupport, $rewardsImage, $rewardsDelete);
        
	//fill array of reward errors
	$reward_errors = checkRewards($finalCount, $rewardsTitle, $rewardsDesc, $rewardsAvail, $rewardsSupport, $rewardsMonth, $rewardsYear);
	//end reward info

        $rewardSuccess = true;
	$action = $_REQUEST['action'];
	//&& ($_POST['fldTerms'] == 1)
	if ($action == 'submitProject'){
                if($reward_errors == null){
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

                                                            //upload reward images and add rewards to db
                                                            $projectID = q1("SELECT pkProjectID FROM tblProject WHERE fldTitle = \"$fldTitle\"");
                                                            for($i=1; $i<$finalCount; $i++){
                                                                $rSuccess = false;
                                                                $rAction = "INSERT";
                                                                $imgName2 = basename($_FILES['rewardImage'. $i]['name']);
                                                                $imgSize2 = $_FILES['rewardImage'. $i]['size'];
                                                                $match2 = "0";
                                                                if($imgSize2 > 0) {
                                                                    $img_ext2 = preg_split("/\./", $imgName2);
                                                                    $allowed_ext2s = preg_split("/\,/", $allowed_ext);
                                                                    foreach ($allowed_ext2s as $ext) {
                                                                        if ($ext == strtolower(end($img_ext2))) {
                                                                            $match2 = "1"; // File is allowed
                                                                            $tmp_file2 = $_FILES['rewardImage'. $i]['tmp_name'];
                                                                            $newFilename2 = make_filename_safe($imgName2);
                                                                            $img_path2 = "project_images/" . $newFilename2;
                                                                            if (!file_exists($img_path2)) {
                                                                                if (move_uploaded_file($tmp_file2, $img_path2) == false) {
                                                                                        $error_msg[] = "ERROR WITH REWARD: Please check image: " . $imgName2 ." filesize.";
                                                                                }else{
                                                                                    //check if reward exists
                                                                                    if($rewardsID[$i] != null){
                                                                                        //update reward
                                                                                        $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\", fldImage = \"$newFilename2\",
                                                                                                fkProjectID = \"$id\" WHERE pkRewardID = \"$rewardsID[$i]\"");
                                                                                    }else{
                                                                                        //full insert
                                                                                        $rSuccess = qr("INSERT INTO tblRewards (fldTitle, fldDescription, fldSupport, fldNumAvailable, fldRewardMonth, fldRewardYear, fldImage, fkProjectID, fldRewardsLeft) VALUES(
                                                                                                                               \"$rewardsTitle[$i]\", \"$rewardsDesc[$i]\", \"$rewardsSupport[$i]\", \"$rewardsAvail[$i]\", \"$rewardsMonth[$i]\", \"$rewardsYear[$i]\", \"$newFilename2\", \"$id\", \"$rewardsAvail[$i]\")");
                                                                                    }
                                                                                    if($rSuccess){
                                                                                        $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully added";
                                                                                    }
                                                                                }
                                                                            }else{
                                                                                $error_msg[] = "ERROR WITH REWARD: Filename " . $imgName2 . " already exists. Please rename and try again.";
                                                                            }
                                                                        }
                                                                    }
                                                                    if (!$match2) {
                                                                        $error_msg[] = "ERROR WITH REWARD: " . $imgName2 . " is not of type jpg, jpeg, gif, png, or bmp.";
                                                                    }
                                                                }else{
                                                                    //don't overwrite image
                                                                    //check if reward exists
                                                                    if($rewardsID[$i] != null){
                                                                        //update reward
                                                                        $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\",
                                                                                fkProjectID = \"$id\" WHERE pkRewardID = \"$rewardsID[$i]\"");
                                                                    }else{
                                                                        //full insert
                                                                        $rSuccess = qr("INSERT INTO tblRewards (fldTitle, fldDescription, fldSupport, fldNumAvailable, fldRewardMonth, fldRewardYear, fkProjectID, fldRewardsLeft) VALUES(
                                                                                                               \"$rewardsTitle[$i]\", \"$rewardsDesc[$i]\", \"$rewardsSupport[$i]\", \"$rewardsAvail[$i]\", \"$rewardsMonth[$i]\", \"$rewardsYear[$i]\", \"$id\", \"$rewardsAvail[$i]\")");
                                                                    }
                                                                    if($rSuccess){
                                                                        $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully added";
                                                                    }
                                                                }
                                                                if(!$rSuccess){
                                                                    $rewardSuccess = false;
                                                                }
                                                            }
                                                            if ($success && $rewardSuccess){
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
                                                    $error_msg[] = "ERROR: IMAGE ". $img_path ." ALREADY EXISTS - Please rename the file and try again";
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
<style>
    .bigLinks {
        font-size:20px;
    }
    .bigLinks:hover{
        text-decoration: underline;
    }
</style>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2>My Account <p style="float:right"><a href="edit-profile.php" <?=$editProfileStyle?>>edit my profile</a></h2>
			</div>
                <form id="submitForm" name="submitForm" method="post" action="<?=$submitPage?>" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="15000000" />
			<div class="page-Leftcol col">
				<div class="left-col-inner">
                    <?
                    	echo_msg_box();
                    	unset($report_msg);
                    	unset($error_msg);
                    	unset($_REQUEST['rep_msg']);
                    	unset($_REQUEST['$err_msg']);
                    ?>

                    <h2>Welcome <?=$_SESSION['user_name']?>, to your United Hero Account.</h2>

					<?

					if ($numProjects > 0){

						//echo_msg_box(); //another box for not updating address profile message (if necessary)
?>

						<h3>You may review your project status below</h3>
						<p>Project Image: <br/><br/><img src="/magick.php/<?=$fldImage?>?resize(400)" width="400" alt="PROJECT IMAGE"/></p>

<?
						if ($showSubmissionReceived){
							echo "<p>Product Submission Received: <b style='color: green;'>YES</b></p>\n";
						}

						echo "<p>Product Submission Status: $fldStatus_text</p>\n";

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
                    				echo "<a class='bigLinks' href=\"edit-project.php?id=$projectID\">edit your product</a> <i style='font-size: 11px;'>(You can edit your product until its APPROVED)</i><br />\n";
                    			}
                    		?>
                    		<a class="bigLinks" href="/project.php?id=<?=$projectID?>">view your product</a><br />
                    		<?
                    			if ($fldStatus == 'unapproved'){
                    				echo "<a href=\"submit-project.php\">submit a new project</a><br />\n";
                    			}
                    		?>
                                <a class="bigLinks" href="/reward-sales.php?id=<?=$projectID?>">view sold rewards</a><br/>

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
			<?
                        include('rewards_sidebar.inc.php');?>
                </form>


<?
	include("footer.php");
?>