<?php

	require_once('preheader.php');
	$globals['site_root_dir'] = "/home/united/public_html/";

	//all projects which are "live" but their time has expired
	$unfundedProjects = q("SELECT pkProjectID, fkUserID FROM tblProject WHERE (fldActualFunding < fldDesiredFundingAmount) AND fldStatus = \"approved\" AND (fldEndDate < NOW())");

	if (count($unfundedProjects) > 0){
		foreach ($unfundedProjects as $unfundedProject){
			$pkProjectID 	= $unfundedProject['pkProjectID'];
			$fkUserID 		= $unfundedProject['fkUserID'];

			//get user info
			$userInfo = qr("SELECT fldFName, fldLName, fldEmail FROM tblUser WHERE pkUserID = $fkUserID");
			$to_email = $userInfo['fldEmail'];
			$creatorName = $userInfo['fldFName'] . " " . $userInfo['fldLName'];

			$success = qr("UPDATE tblProject SET fldStatus = 'unfunded' WHERE pkProjectID = $pkProjectID");

			if ($success){
				//send email
				$email_template_params = array();
				$email_template_params['FULLNAME']  = $creatorName;
				//$email_template_params['TOP-HEADER-TEXT']  = "This email is being sent from unitedhero.com. It requires your action.";
				$subject = "Your project did not get funded on time";
				$success = send_email_from_template("project-unfunded-letter.html",$to_email,$subject,$email_template_params, 1, $globals['emails_from']);
				if ($success){
					echo "project unfunded letter sent to $creatorName";
				}
			}
		}//foreach
	}//if unfunded projects exist
?>