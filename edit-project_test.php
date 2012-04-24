<?
	require_once("preheader.php");
	require_login();

	$userID = $_SESSION['user_id'];
	$userFirstName = $_SESSION['user_first_name'];
	$userLastName = $_SESSION['user_last_name'];

	$id = $_REQUEST['id'];

        //reward readonly bool
        $readOnlyKeyword = false;

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

        //check for rewards to be deleted
        foreach($rewardsDelete as $x){
            if($x != "" && $x != null){
                $finalCount++;
            }
        }
        //end reward info

	//rudimentary security
	if (!$id) header("Location: /my-account.php");
	$projectID = $id;

	$projectOwnerID = q1("SELECT fkUserID FROM tblProject WHERE pkProjectID = $id");
	if ($projectOwnerID != $userID){
		$error_msg[] = "Unauthorized attempt to edit a project which is not your own. This incident has been recorded.";
	}


	$action = $_REQUEST['action'];
	if ($action == 'updateProject'){
            if($reward_errors == null){
		extract($_POST);

		$fldDesiredFundingAmount = stripNonNumeric($fldDesiredFundingAmount);

		$success = qr("UPDATE tblProject SET
						fldTitle 				= \"$fldTitle\",
						fldDescription 			= \"$fldDescription\",
						fldLocation 			= \"$fldLocation\",
						fldDesiredFundingAmount = $fldDesiredFundingAmount,
						fldVideoHTML 			= \"$fldVideoHTML\",
						fldTags 				= \"$fldTags\"
						WHERE pkProjectID = \"$projectID\"");

		//$phoneNumSuccess = qr("UPDATE tblUser SET fldPhone = \"$fldPhoneNumber\" WHERE pkUserID = $userID");

                //upload reward images and add rewards to db
                $rewardSuccess = true;
                $allowed_ext = "jpg,jpeg,gif,png,bmp";
                for($i=1; $i<$finalCount; $i++){
                    if($rewardsDelete[$i] != null){
                        //reward needs to be deleted
                        $oldImage = q1("SELECT fldImage FROM tblRewards WHERE pkRewardID = \"$rewardsDelete[$i]\"");
                        if($oldImage){
                            $oldImage = "project_images/" . $oldImage;
                            $deletedImage = unlink($oldImage);
                        }
                        if($deletedImage){
                            $delSuccess = q1("DELETE FROM tblRewards WHERE pkRewardID = \"$rewardsDelete[$i]\"");
                            $report_msg[] = "Reward " . $i . " was successfully deleted";
                        }
                    }else{
                        //add reward information in some way
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
                                                $rewardSuccess = false;
                                        }else{
                                            //overwrite current image and delete old image
                                            //check if reward exists
                                            if($rewardsID[$i] != null){
                                                //delete old image
                                                $oldImage = q1("SELECT fldImage FROM tblRewards WHERE pkRewardID = \"$rewardsID[$i]\"");
                                                $deletedImage = true;
                                                if($oldImage){
                                                    $oldImage = "project_images/" . $oldImage;
                                                    $deletedImage = unlink($oldImage);
                                                }
                                                if($deletedImage){
                                                    // check number of available and sold rewards
                                                    $num = qr("SELECT fldNumAvailable, fldRewardsLeft FROM tblRewards WHERE pkRewardID = \"$rewardsID[$i]\"");
                                                    extract($num);
                                                    // let the user increase the number of rewards if it will not cause errors. (numAvail > numSold)
                                                    if($rewardsAvail[$i] >= $num['fldNumAvailable']){
                                                        $newNum = $num['fldNumAvailable'] + ($rewardsAvail[$i] - $num['fldNumAvailable']);
                                                        //update reward
                                                        $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\", fldImage = \"$newFilename2\",
                                                                fkProjectID = \"$projectID\", fldRewardsLeft = \"$newNum\" WHERE pkRewardID = \"$rewardsID[$i]\"");

                                                        $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully updated";
                                                    }else{
                                                        // user can reduce availability without affecting the reward
                                                        if($num['fldRewardsLeft'] >= ($num['fldNumAvailable'] - $rewardsAvail[$i])){
                                                            $newNum = $num['fldRewardsLeft'] - ($num['fldNumAvailable'] - $rewardsAvail[$i]);
                                                            //update reward
                                                            $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\", fldImage = \"$newFilename2\",
                                                                fkProjectID = \"$projectID\", fldRewardsLeft = \"$newNum\" WHERE pkRewardID = \"$rewardsID[$i]\"");

                                                        $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully updated";
                                                        }else{
                                                            $error_msg[] = "ERROR WITH REWARD: " . $rewardsTitle[$i] . ". Cannot change the number available to be less than ". ($num[0] - $rewardsAvail[$i]);
                                                        }
                                                    }
                                                }else{
                                                    $error_msg[] = "ERROR WITH REWARD: Filename " . $oldImage . " could not be overwritten.";
                                                }
                                            }else{
                                                //full insert
                                                $rSuccess = qr("INSERT INTO tblRewards (fldTitle, fldDescription, fldSupport, fldNumAvailable, fldRewardMonth, fldRewardYear, fldImage, fkProjectID, fldRewardsLeft) VALUES(
                                                                                       \"$rewardsTitle[$i]\", \"$rewardsDesc[$i]\", \"$rewardsSupport[$i]\", \"$rewardsAvail[$i]\", \"$rewardsMonth[$i]\", \"$rewardsYear[$i]\", \"$newFilename2\", \"$projectID\", \"0\")");

                                                $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully added";
                                            }
                                        }
                                    }else{
                                        $error_msg[] = "ERROR WITH REWARD: Filename " . $imgName2 . " already exists. Please rename and try again.";
                                        $rewardSuccess = false;
                                    }
                                }
                            }
                            if (!$match2) {
                                $error_msg[] = "ERROR WITH REWARD: " . $imgName2 . " is not of type jpg, jpeg, gif, png, or bmp.";
                                $rewardSuccess = false;
                            }
                        }else{
                            //don't overwrite image
                            //check if reward exists
                            if($rewardsID[$i] != null){
                                // check number of available and sold rewards
                                $num = qr("SELECT fldNumAvailable, fldRewardsLeft FROM tblRewards WHERE pkRewardID = \"$rewardsID[$i]\"");
                                extract($num);
                                if($rewardAvail[$i] != 0){
                                    // let the user increase the number of rewards if it will not cause errors. (numAvail > numSold)
                                    if($rewardsAvail[$i] >= $num['fldNumAvailable']){
                                        $newNum = $num['fldRewardsLeft'] + ($rewardsAvail[$i] - $num['fldNumAvailable']);
                                        //update reward
                                        $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\",
                                                fkProjectID = \"$projectID\", fldRewardsLeft = \"$newNum\" WHERE pkRewardID = \"$rewardsID[$i]\"");

                                        $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully updated";
                                    }else{
                                        // user can reduce availability without affecting the reward
                                        if($num['fldRewardsLeft'] >= ($num['fldNumAvailable'] - $rewardsAvail[$i])){
                                            $newNum = $num['fldRewardsLeft'] - ($num['fldNumAvailable'] - $rewardsAvail[$i]);
                                            //update reward
                                            $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\",
                                                fkProjectID = \"$projectID\", fldRewardsLeft = \"$newNum\" WHERE pkRewardID = \"$rewardsID[$i]\"");

                                            $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully updated";
                                        }else{
                                            $error_msg[] = "ERROR WITH REWARD: " . $rewardsTitle[$i] . ". Cannot change the number available to be less than ". ($num[0] - $rewardsAvail[$i]);
                                        }
                                    }
                                }else{
                                    //no limit set
                                    $rSuccess = qr("UPDATE tblRewards SET fldTitle = \"$rewardsTitle[$i]\", fldDescription = \"$rewardsDesc[$i]\", fldSupport = \"$rewardsSupport[$i]\",
                                                fldNumAvailable = \"$rewardsAvail[$i]\", fldRewardMonth = \"$rewardsMonth[$i]\", fldRewardYear = \"$rewardsYear[$i]\",
                                                fkProjectID = \"$projectID\", fldRewardsLeft = \"$rewardsAvai[$i]\" WHERE pkRewardID = \"$rewardsID[$i]\"");

                                        $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully updated";
                                }
                            }else{
                                //full insert
                                $rSuccess = qr("INSERT INTO tblRewards (fldTitle, fldDescription, fldSupport, fldNumAvailable, fldRewardMonth, fldRewardYear, fldImage, fkProjectID, fldRewardsLeft) VALUES(
                                                                       \"$rewardsTitle[$i]\", \"$rewardsDesc[$i]\", \"$rewardsSupport[$i]\", \"$rewardsAvail[$i]\", \"$rewardsMonth[$i]\", \"$rewardsYear[$i]\", \"$imgName2\", \"$projectID\", \"0\")");

                                $report_msg[] = "Reward: " . $rewardsTitle[$i]. " was successfully added";
                            }
                        }
                    }
                }
                if ($success && $rewardSuccess){
                    $msg = "Your project was successfully updated";
                    //$report_msg[] = $msg;
                    header("Location: my_account_test.php?rep_msg=$msg");
                }
		else{
			$error_msg[] = "No changes were made to YOUR PROJECT information";
		}
            }
	}

	$numProjects = q1("SELECT COUNT(pkProjectID) FROM tblProject WHERE pkProjectID = $id");

	include("header.php");

?>
		<div>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2><a href="/my_account_test.php">My Account</a></h2>
			</div>
                 <form id="submitForm" name="submitForm" method="post" action="<?=$submitPage?>" enctype="multipart/form-data">
			<div class="page-Leftcol col">
				<div class="left-col-inner">
                    <?  echo_msg_box();
                        unset($report_msg);
                        unset($error_msg);
                    ?>

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
							$submitPage = "edit-project_test.php";
							include("proj_submission_form_test.inc.php");
						}

					}
?>

				    </div>
					<!-- end inner div -->
				</div>

			<? include('rewards_sidebar.inc.php');?>
                 </form>
<?
	include("footer.php");
?>