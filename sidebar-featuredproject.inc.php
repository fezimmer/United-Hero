<?
	$featuredProjectInfo = qr("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fldImage, fkUserID FROM tblProject WHERE fldFeatured = 1 LIMIT 1");
	extract($featuredProjectInfo);
	$submittedByInfo 	= qr("SELECT fldFName, fldLName, fldUsername, fldEmail, fldPhone, fldZip, fldSignupDate, fldIPAddress, fldType, fldActive, fldLastLogin FROM tblUser WHERE pkUserID = $fkUserID");
	extract($submittedByInfo);

	$percentComplete = number_format(($fldActualFunding / $fldDesiredFundingAmount),2) * 100;
	$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
	$fldActualFunding			= to_money($fldActualFunding);

	$timeRemaining = getTimeRemaining($pkProjectID);
	$daysRemaining = $timeRemaining['days'];

	/*
	if (strlen($fldDescription) > 170){
		$fldDescription = substr($fldDescription, 0, 170) . "...";
	}
	*/
?>
	<div class="boxLine_featured blogTitle_box">
		 <ul>
			 <li class="first"> <a href="/project.php?id=<?=$pkProjectID?>">
				<div class="img-box">
				  <div class="hoverlay"> </div>
				  <img src="/magick.php/<?=$fldImage?>?part(208x128)" alt="" border="0" height="128" width="208"> </div>
				<h4><?=$fldTitle?></h4>
				<!--p><strong><?=$fldTags;?></strong></p-->
				<p>
				  <?=$percentComplete?>% FUNDED SO FAR |
				  <!--<?to_money($fldActualFunding);?> SO FAR |-->
				  <?=$daysRemaining?> DAYS REMAIN
				</p>

				</a>
					<div class="desc" style="width:210px;overflow: auto">
						<?
							if (strlen($fldDescription) > 400){
								echo substr($fldDescription, 0, 400) . "...";
							}
							else echo $fldDescription;
						?>
					</div>

					<div class="project-cta">
						<a href="/project.php?id=<?=$pkProjectID?>&support=yes" class="support" style="margin-left: 10px; font-size: 19px; width:185px;">Support this Product</a>
					</div>
					<!--div class="desc">
						Ut enim ad minim veniam, quis nostrud exercitation ullamco
						laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.
					</div-->
				</li>
			</ul>
	</div>
