<?
	$featuredProjectInfo = qr("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fldImage, fkUserID FROM tblProject WHERE fldFeatured = 1 LIMIT 1");
	if ($featuredProjectInfo){
		extract($featuredProjectInfo);

		$percentComplete = number_format(($fldActualFunding / $fldDesiredFundingAmount),2) * 100;
		$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
		$fldActualFunding			= to_money($fldActualFunding);

		$timeRemaining = getTimeRemaining($pkProjectID);
		$daysRemaining = $timeRemaining['days'];

		$submittedByInfo 	= qr("SELECT fldFName, fldLName, fldUsername, fldEmail, fldPhone, fldZip, fldSignupDate, fldIPAddress, fldType, fldActive, fldLastLogin FROM tblUser WHERE pkUserID = $fkUserID");
		extract($submittedByInfo);

		if (strlen($fldDescription) > 170){
			$fldDescription = substr($fldDescription, 0, 170) . "...";
		}

?>
	<div class="page-Rightcol  blogTitle_box col">
		<div class="boxLine_featured blogTitle_box">
			<h2>Featured Product</h2>
			<ul style="margin-left:7px;">
				<li class="first"> <a href="/project.php?id=<?=$pkProjectID?>">
					<div class="img-box">
					  <div class="hoverlay"> </div>
					  <img src="/magick.php/<?=$fldImage?>?part(208x128)" alt="" border="0" height="128" width="208"> </div>
					<h4><?=$fldTitle?></h4>
					<!--p><strong><?=$fldTags?></strong></p-->
					<p>
					  <?=$percentComplete?>% FUNDED SO FAR |
					  <!--<?to_money($fldActualFunding);?> SO FAR |-->
					  <?=$daysRemaining?> DAYS REMAIN
					</p>

					</a>
					<div class="desc">
						<?=$fldDescription?>.
					</div>
				</li>
				<div class="project-cta">
					<a href="/project.php?id=<?=$pkProjectID?>&support=yes" class="support" style="margin-left: 10px; width:200px;">Support this Product</a>
				</div>

			</ul>

		</div>
	</div>
<?	}?>