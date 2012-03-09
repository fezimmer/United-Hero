<?
	if($_SERVER["HTTPS"] != "on") {
	   header("HTTP/1.1 301 Moved Permanently");
	   //header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
	   header("Location: https://unitedhero.com" . $_SERVER['REQUEST_URI']);
	   exit();
	}

	$thisURL = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	require_once("preheader.php");

	$projectID 	= $_REQUEST['id'];
	//if param not id, it may be pkProjectID
	if(!$projectID) $projectID = $_REQUEST['pkProjectID'];
	if (!$projectID) header("Location: index.php");

	if ($_REQUEST['support'] != "") $showPaymentForm = true;

	$projectInfo 		= qr("SELECT pkProjectID, fldTitle, fldDescription, fldImage, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID FROM tblProject WHERE pkProjectID = \"$projectID\"");
	if ($projectInfo){
		extract($projectInfo);
	}
	else{
		if (!$pkProjectID){
			header("Location: index.php?err_msg=This project does not exist.");
			exit();
		}
	}

	$pageTitle = $fldTitle;
	$metaDesc = $fldDescription;
	//$metaImage = "http://" . $globals['site_name'] . "/magick.php/$fldImage?resize(90)";

	$submittedByInfo 	= qr("SELECT fldFName, fldLName, fldUsername, fldEmail, fldPhone, fldZip, fldSignupDate, fldIPAddress, fldType, fldActive, fldLastLogin FROM tblUser WHERE pkUserID = $fkUserID");
	extract($submittedByInfo);

	$submittedBy = $fldFName . " " . $fldLName;
	$tagArray = split(",", $fldTags);

	$percentComplete = 0;
	if ($fldDesiredFundingAmount){
		$percentComplete = number_format(($fldActualFunding / $fldDesiredFundingAmount),2) * 100;
	}

	$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
	$fldActualFunding			= to_money($fldActualFunding);
	$timeRemaining = getTimeRemaining($pkProjectID);//an array of the time remaining on the project
	$daysRemaining = $timeRemaining['days'];		//num of days left

	if ($daysRemaining > 0){
		$daysText = "<strong>$daysRemaining</strong> <a style=\"color: red;\" href=\"#?w=780\" rel=\"daysLeftPopup\" class=\"poplight underline\" title=\"Information on Days Left\">DAYS LEFT</a>";
	}
	else{
		$projectExpired = TRUE;
		$daysText = "<strong style='color: red;'>PROJECT EXPIRED</strong>";
	}

	if ($fldStatus == 'review'){
		//per requirement on 10/3/2011 in email from Matt - when viewing a project in review, days remaining must be 0
		$daysRemaining = 0;
		$daysText = "<strong style='color: blue;'>PROJECT IN REVIEW</strong>";
	}
	if ($fldStatus == 'funded'){
		$daysText = "<strong style='color: greeg;'>PROJECT FULLY FUNDED</strong>";
	}

	$action = $_POST['action'];

	if ($action == "submitPayment"){
		include"lphp.php";
		$mylphp=new lphp;

		# constants
		$myorder["host"]       = "secure.linkpt.net";
		$myorder["port"]       = "1129";
		$myorder["keyfile"]    = "1001281559.pem"; # Change this to the name and location of your certificate file
		$myorder["configfile"] = "1001281559";        # Change this to your store number

		# form data
		$myorder["cardnumber"]    = $_POST["cardnumber"];
		$myorder["cardexpmonth"]  = $_POST["cardexpmonth"];
		$myorder["cardexpyear"]   = $_POST["cardexpyear"];
		$myorder["chargetotal"]   = $_POST["chargetotal"];
		$myorder["ordertype"]     = $_POST["ordertype"];

		if ($_POST["debugging"]){
			$myorder["debugging"]="true";
		}

	    # Send transaction. Use one of two possible methods  #
		//$result = $mylphp->process($myorder);     # use shared library model
		$result = $mylphp->curl_process($myorder);  # use curl methods

		if ($result["r_approved"] != "APPROVED"){
			# transaction failed, print the reason
			//print "Status:  $result[r_approved]<br>\n";
			//print "Error:  $result[r_error]<br><br>\n";
			$error_msg[] = "Your credit card did not process. Status: " . $result[r_approved] . " | Error Code: " . $result[r_error];
		}
		else{
			# success
			$code = $result[r_code];
			//print "Status: $result[r_approved]<br>\n";
			//print "Transaction Code: $result[r_code]<br><br>\n";

			$orderNum = $result[r_ordernum];
			$ref 	  = $result[r_ref];

			$name = $_POST['cardholdername'];
			$amount = $myorder["chargetotal"];
			$transactionID = $result[r_code];
			$fkProjectID = $projectID;

			$ip = $_SERVER['REMOTE_ADDR']; //get the ip address (can be used for geo-locating)
			$success = qr("INSERT INTO tblPayment (fldName, fldAmount, fldDatetime, fldTransactionID, fldOrderNum, fldRef, fldIPAddress, fkProjectID) VALUES (\"$name\", $amount, NOW(), \"$transactionID\", \"$orderNum\", \"$ref\", \"$ip\", $fkProjectID)");
			if ($success){

				$projectInfo = q1("SELECT fldStatus, fldActualFunding, fldDesiredFundingAmount, fkUserID FROM tblProject WHERE pkProjectID = $projectID");

				$currentFunding = $projectInfo['fldActualFunding'];
				$desiredFunding = $projectInfo['fldDesiredFundingAmount'];
				$projectStatus	= $projectInfo['fldStatus'];
				$fkUserID 		= $projectInfo['fkUserID'];

				$newFunding = ($currentFunding + $amount);
				$updateProjectSuccess = qr("UPDATE tblProject SET fldActualFunding = $newFunding WHERE pkProjectID = $projectID");

				$report_msg[] = "Your credit card was successfully processed for " . to_money($amount);
				$showPaymentForm = false;

				//if this is the donation that tips the project over the line...
				if ($projectStatus == 'approved' && ($newFunding > $desiredFunding)){
					$userInfo = qr("SELECT fldFName, fldLName, fldEmail FROM tblUser WHERE pkUserID = $fkUserID");
					$to_email = $userInfo['fldEmail'];
					$creatorName = $userInfo['fldFName'] . " " . $userInfo['fldLName'];

					//flag project as funded in the db
					$success = qr("UPDATE tblProject SET fldStatus = 'funded' WHERE pkProjectID = $projectID");

					if ($success){
						//send email
						$email_template_params = array();
						$email_template_params['FULLNAME']  = $creatorName;
						//$email_template_params['TOP-HEADER-TEXT']  = "This email is being sent from unitedhero.com. It requires your action.";
						$subject = "Congratulations! Your project was 100% funded!";
						$success = send_email_from_template("project-funded-letter.html",$to_email,$subject,$email_template_params, 1, $globals['emails_from']);
					}
				}//if project now 100%+ funded
			}

		}

	# if verbose output has been checked (i.e. testing mode - send a variable named 'verbose'),
	# print complete server response to a table
		if ($_POST["verbose"]){
			echo "<table border=1>";

			while (list($key, $value) = each($result)){
				# print the returned hash
				echo "<tr>";
				echo "<td>" . htmlspecialchars($key) . "</td>";
				echo "<td><b>" . htmlspecialchars($value) . "</b></td>";
				echo "</tr>";
			}

			echo "</TABLE><br>\n";
		}

	}//action = submitPayment


	include("header.php");
?>

<style>
	a.underline:hover{
		text-decoration: underline;
	}
</style>

			<div class="featuredBox details inner-col">
				<?
					displayVideo($fldVideoHTML, "project");
				?>

				<h3 class="title" style="float: left;"><?=$fldTitle?></h3>
				<div style="float: right;">
					<a name="fb_share"></a>
				</div>

				<div style="clear: both;" class="location"><?=$fldLocation?></div>
				<div class="submitted-by">
					<em>Submitted by:</em> <a href="#"><?=$submittedBy?></a>
				</div>
		        <!--<h3 class="title">Details</h3>-->
				<p>
					<?
						if (strlen($fldDescription) > 500){
							echo parseLinks(substr($fldDescription, 0, 500));
							echo "<span id='dots'>...";

							echo "&nbsp;&nbsp; <a href=\"#?w=780\" rel=\"projectDescriptionPopup\" title=\"$fldTitle by $submittedBy\" class=\"poplight\" >read more</a></span>\n";

?>
							<div class="popup_block" id="projectDescriptionPopup">
								<div style="margin: 5px;">
									<p><?=parseLinks(nl2br($fldDescription));?>
									</p>
									<p>&nbsp;</p>
									<button class="button button-gray btn-close">Close</button>
								</div>
							</div>

<?
						}
						else{
							echo parseLinks($fldDescription);
						}
					?>
				</p>
				<div class="tag-container">
					<?

						//last minute change by Matt (9/26); does not want tags to display anymore
						foreach($tagArray as $tag){
							//echo "<a href=\"#\">$tag</a>\n";
						}
					?>

					<div style="clear: both;"></div><br />
					<iframe src="//www.facebook.com/plugins/like.php?app_id=267526983268917&amp;href=<?=$thisURL?>&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>

					<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>

				</div>
			</div>
			<div class="project-details inner-col">

				<div id="paymentDiv" class="more-details" <? if (!$showPaymentForm) echo "style=\"display:none;\"";?>>
					<h3 class="title">Donation Processing</h3>
					<? echo_msg_box();?>
					<p>
						<img src="/images/Bank-America.jpg" width="200" \>
					</p>
					<p><form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="paymentForm">
						<input type="hidden" name="ordertype" value="SALE">
						<input type="hidden" name="action" value="submitPayment">
						<input type="hidden" name="id" value="<?=$projectID?>">
						<!--input type="hidden" name="verbose" value="true"-->

                         Financial Support&nbsp;&nbsp;<br/>
                        $<input name="chargetotal" type="text" size="8" />&nbsp;&nbsp; minimum $1

                        <br/><br/>
                        <select name="cardtype" >
							<option value="" SELECTED>--Credit Card Type--
							<option value="01">Visa
							<option value="02">masterCard
							<option value="03">Discover
							<option value="04">AMEX

						</select><br/><br/>
						Credit Card Number<br/>
						<input name="cardnumber" type="text" /><br/><br />
						Name on Credit Card<br/>
						 <input name="cardholdername" type="text" /> <br/><br />
						 Security Code<br/>
						 <input name="secuirty" type="text" size="2" maxlength="3" />&nbsp;&nbsp; 3 digit number on back of credit card

						 <br/><br/>

						 <select name="cardexpmonth" >
							<option value="" SELECTED>--Exipration Month--
							<option value="01">January (01)
							<option value="02">February (02)
							<option value="03">March (03)
							<option value="04">APRIL (04)
							<option value="05">MAY (05)
							<option value="06">JUNE (06)
							<option value="07">JULY (07)
							<option value="08">AUGUST (08)
							<option value="09">SEPTEMBER (09)
							<option value="10">OCTOBER (10)
							<option value="11">NOVEMBER (11)
							<option value="12">DECEMBER (12)
						</select> /
						<select name="cardexpyear">
							<option value="" SELECTED>--Expiration Year--
							<option value="10">2010
							<option value="11">2011
							<option value="12">2012
							<option value="13">2013
							<option value="14">2014
							<option value="15">2015
							<option value="16">2016
							<option value="17">2017
							<option value="18">2018
						</select>

					    <br/><br/>
						<div class="action-buttons">
							<button class="button button-gray btn-close" onClick="document.getElementById('paymentForm').submit();">Submit payment</button>
						</div>

                        </form>
                        <div style="clear: both"></div>
				</div>

			<h3 class="title">Goal <?=$fldDesiredFundingAmount?></h3>
				<div class="progress-info">
				  <div class="info-block first"> <strong><?=$percentComplete?>%</strong> FUNDED </div>
				  <div class="info-block"> <strong><?=$fldActualFunding?></strong> SO FAR </div>
				  <div class="info-block"> <?=$daysText?> <span style="font-size:9px;" align="top" valign="top">(<a style="color: red;" href="#?w=780" rel="daysLeftPopup" class="poplight underline" title="Information on Days Left">?</a>)</span></div>
				</div>
				 <div class="slider-container">
					<div class="progress-wrap">
					  <div class="progress-bar"style="width: <?=$percentComplete?>%"></div>
					</div>
				 </div>

				<? 	if (!$projectExpired && $fldStatus == 'approved'){?>
				<div class="project-cta">
					<a href="#" class="support" onClick="$('#paymentDiv').slideDown();$('#socialWidgets').hide();">Support this Project</a>
				</div>
				<?	}?>
				<div class="more-details" id="socialWidgets">
					<h3 class="title"><img src="/images/fb.png" width="33" align="absmiddle">&nbsp;Social Commentary</h3>
					<div id="fb-root"></div>

					<div class="fb-comments" data-href="<?=$thisURL?>" data-num-posts="2" data-width="340" style="overflow: auto;"></div>
				</div>

			</div>
		  <div>
			 <div style="clear: both;"></div>
			<div class="similar">Similar Projects
				<a href="/browse_projects.php?pageNum=1" class="browse-more">Browse other Projects</a>
			</div>
			 <div class="boxLine_first_new">
				<div class="blogTitle_box blogTitle_box_new" id="postsDiv">
				  <ul>

<?
					include('projectlist.inc.php');
?>
				  </ul>
				</div>
			 </div>

			 <? include ('sidebar-featuredproject.inc.php'); ?>

			 <div style="clear: both;"></div>

		  </div>
		</div>
		<!--script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script-->
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>


<!-- hidden content for days left modal popup -->
<div class="popup_block" id="daysLeftPopup">
	<div style="margin: 5px;">
		<p>If you do not get the funding goal amount in the time limit, the money goes back to
		the supporters as a refund.</p>
		<p>Please view our <a href="#?w=780" rel="guidelinesPopup" title="United Hero Dream/Project Guidelines" class="poplight">guidelines</a></p>
		<br />
		<p>&nbsp;</p>
		<button class="button button-gray btn-close">Close</button>
	</div>
</div>

<?
	include("footer.php");
?>