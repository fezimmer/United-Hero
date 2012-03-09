<?
	$fullTagArray = array(); //array for tags for ALL projects

	$projects = q("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID, fldImage FROM tblProject WHERE (fldStatus = 'approved' OR fldStatus = 'funded') AND fldEndDate > NOW() ORDER BY fldDateCreated LIMIT 9");

	$count = 0;
	foreach ($projects as $project){
		extract($project);

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
				<!--p><strong>";
				foreach($tagArray as $tag){
					if ($tag){
						$fullTagArray[] = $tag;
						//echo "$tag ";
					}
				}

		echo "</p></strong-->
			</a>
		</li>\n";

		$count++;
	}//foreach project
?>
