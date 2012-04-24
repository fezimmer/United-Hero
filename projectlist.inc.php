<?
	$fullTagArray = array(); //array for tags for ALL projects

	$projects = q("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID, fldImage FROM tblProject WHERE (fldStatus = 'approved' OR fldStatus = 'funded') AND fldEndDate > NOW() ORDER BY fldDateCreated LIMIT 9");

	$count = 0;
	foreach ($projects as $project){
		extract($project);

		$percentComplete = 0;
		if ($fldDesiredFundingAmount){
			$percentComplete = number_format(($fldActualFunding / $fldDesiredFundingAmount),2) * 100;
		}

		$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
		$fldActualFunding			= to_money($fldActualFunding);
		$timeRemaining = getTimeRemaining($pkProjectID);//an array of the time remaining on the project
		$daysRemaining = $timeRemaining['days'];		//num of days left

		$tagArray = split(",", $fldTags);

		$class = "";
		if ($count % 3 == 0){
			$class = "class=\"first\"";
		}

		if (!$fldImage) $fldImage = "no_image_uploaded.jpg";

		echo "
		<li $class>
			<a href=\"/project.php?id=$pkProjectID\">
				<div class=\"img-box\">
					<div class=\"hoverlay\"> </div>
					<img src=\"/magick.php/$fldImage?resize(208x128)\" alt=\"$fldTitle\" border=\"0\" width=\"208\" height=\"128\" >
				</div>
				<h4>$fldTitle</h4>

				 <div style='clear: both;'></div>
				 <div class=\"slider-container\">
					<div class=\"progress-wrap\">
					  <div class=\"progress-bar\" style=\"width: {$percentComplete}%\"></div>
					</div>
				 </div>
				 <div style='clear: both;'></div>
				 <p><b class='blue'>$fldActualFunding</b> SO FAR. <b class='blue'>$daysRemaining</b> <u style='color: red;'>DAYS LEFT</u>.</p>

				<!--p><strong>";

				/*
				foreach($tagArray as $tag){
					if ($tag){
						$fullTagArray[] = $tag;
						//echo "$tag ";
					}
				}
				*/

		echo "</p></strong-->
			</a>
		</li>\n";

		$count++;
	}//foreach project
?>
