<?
	require_once("preheader.php");

	$submitButtonHTML = "<a href=\"#?w=780\" rel=\"signupForm\" class=\"cta start-project poplight\" title=\"Submit Your Product\">Submit Your Product</a>\n";
	$submitIconHTML = "href=\"#?w=780\" rel=\"signupForm\" class=\"poplight\"";
	if(is_logged_in()){
		$submitButtonHTML = "<a href=\"my_account.php\" class=\"cta start-project\" title=\"Submit Your Product\">Submit Your Product</a>\n";
		$submitIconHTML = "href=\"my_account.php\"";
	}

	$featuredProjectInfo = qr("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID FROM tblProject WHERE fldFeatured = 1 LIMIT 1");
	if (is_array($featuredProjectInfo)){
		extract($featuredProjectInfo);
	}
	else{
		header("Location: no-featured.php");
	}

	//if the title of the project is longer than can fit in the box, shorten the length of the 'fund this project' text link

	$fundThisProjectText = "FUND THIS PROJECT";
	$fundProjectTextSize = "16";
	if (strlen($fldTitle) > 28){
		//$fundThisProjectText = "FUND";
		$fundProjectTextSize = "11";
	}
	if (strlen($fldTitle) > 35){
		$fundThisProjectText = "FUND";
		$fundProjectTextSize = "16";
	}
	$submittedByInfo 	= qr("SELECT fldFName, fldLName, fldUsername, fldEmail, fldPhone, fldZip, fldSignupDate, fldIPAddress, fldType, fldActive, fldLastLogin FROM tblUser WHERE pkUserID = $fkUserID");
	extract($submittedByInfo);

	$tagArray = split(",", $fldTags);
	$percentComplete = number_format(($fldActualFunding / $fldDesiredFundingAmount),2) * 100;
	$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
	$fldActualFunding			= to_money($fldActualFunding);

	$timeRemaining = getTimeRemaining($pkProjectID);
	$daysRemaining = $timeRemaining['days'];

	if (strlen($fldDescription) > 150){
		$fldDescription = substr($fldDescription, 0, 150) . "...";
	}
	include("header.php");
	echo_msg_box();

?>

<style>
	#fundProject{
		color: #D6BD25;
		/* border-bottom: 1px dotted #ba0000;*/
	}
	#fundProject:hover{
		 color: #EFD43B;
		 /* border-bottom: 1px solid #ba0000;*/
	}
	#projectTitle{
		font-weight: bold;
	}
	#projectTitle:hover{
		color: #76D5FC;
	}
</style>

		  <div class="featuredBox">
<?
			displayVideo($fldVideoHTML, "index");
?>
			 <!--iframe class="video" src="http://player.vimeo.com/video/4121765" width="442" height="257"></iframe-->

			 <h3><a id="projectTitle" href="/project.php?id=<?=$pkProjectID?>"><?=$fldTitle?></a> - <a style='font-size: <?=$fundProjectTextSize?>px' id="fundProject" href="/project.php?id=<?=$pkProjectID?>&support=1"><b><?=$fundThisProjectText?></b></a></h3></a>
			 <p><?=$fldDescription?></p>
			</a>
			<div class="location"><?=$fldLocation?></div>
			 <div class="slider-container">
				<div class="progress-wrap">
				  <div class="progress-bar"style="width: <?=$percentComplete?>%"></div>
				</div>
				<div class="progress-info">
				  <div class="info-block first"> <strong><?=$percentComplete?>%</strong> FUNDED </div>
				  <div class="info-block"> <strong><?=to_money($fldActualFunding);?></strong> SO FAR </div>
				  <div class="info-block"> <strong><?=$daysRemaining?></strong> DAYS LEFT </div>
				</div>
			 </div>
		  </div>
			<div class="subFeatured">
				<div class="sub-featuredBox">
					<a <?=$submitIconHTML?> title="Submit Your Product"><img src="images/start-icon.png" class="icon" border="0" /></a>
					<div class="action-intro">
						<h2>Sell Your Products Today</h2>
						<p><? include('start_project_today.inc.php');?></p>
						<?=$submitButtonHTML?>
						<div class="no-account">
							Don't have an account?<br/>
							<a href="#?w=780" rel="signupForm" class="signup poplight" title="United Hero Account Creation">Signup</a> it's easy and free!
						</div>
					</div>
				</div>
				<div class="sub-featuredBox">
					<a href="/browse_projects.php"><img src="images/browse-icon.png" class="icon" border="0" /></a>
					<div class="action-intro">
						<h2>Browse Current Products</h2>
						<p><? include('browse_projects.inc.php');?></p>
						<a href="/browse_projects.php?pageNum=1" class="cta browse-projects" title="Browse Current Products">Browse Products</a>
					</div>

				 </div>
			</div>
		  <div>
			 <div style="clear: both;"></div>
			 <div class="boxLine_first_new">
				<div class="blogTitle_box blogTitle_box_new" id="postsDiv">
				  <ul>
<?
					//limit the project list to the 9 "hollywood squares" design
					$limitStatement = "LIMIT 9";
					include('projectlist.inc.php');
?>
				  </ul>
				</div>
			 </div>

			 <? include ('sidebar-featuredproject.inc.php'); ?>
			 <div style="clear: both;"></div>
		  </div>

<?
	include("footer.php");
?>