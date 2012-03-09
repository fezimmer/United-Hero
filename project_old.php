<?
	$thisURL = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	require_once("preheader.php");

	$projectID 	= $_REQUEST['id'];

	if($projectID == ""){
		$projectID 	= $_REQUEST['pkProjectID'];
	}

	if ($projectID == ""){
		header("Location: index.php");
	}

	$projectInfo 		= qr("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID FROM tblProject WHERE pkProjectID = $projectID");
	extract($projectInfo);

	$submittedByInfo 	= qr("SELECT fldFName, fldLName, fldUsername, fldEmail, fldPhone, fldZip, fldSignupDate, fldIPAddress, fldType, fldActive, fldLastLogin FROM tblUser WHERE pkUserID = $fkUserID");
	extract($submittedByInfo);

	$submittedBy = $fldFName . " " . $fldLName;

	$tagArray = split(",", $fldTags);

	$percentComplete = number_format(($fldActualFunding / $fldDesiredFundingAmount),2) * 100;

	$fldDesiredFundingAmount 	= to_money($fldDesiredFundingAmount);
	$fldActualFunding			= to_money($fldActualFunding);



	$timeRemaining = getTimeRemaining($pkProjectID);
	$daysRemaining = $timeRemaining['days'];


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
		//$result = $mylphp->process($myorder);       # use shared library model
		$result = $mylphp->curl_process($myorder);  # use curl methods

		if ($result["r_approved"] != "APPROVED")    // transaction failed, print the reason
		{
			//print "Status:  $result[r_approved]<br>\n";
			//print "Error:  $result[r_error]<br><br>\n";
			$error_msg[] = "Your credit card did not process. Status: " . $result[r_approved] . " | Error Code: " . $result[r_error];
		}
		else	// success
		{
			$code = $result[r_code];
			//print "Status: $result[r_approved]<br>\n";
			//print "Transaction Code: $result[r_code]<br><br>\n";

			$orderNum = $result[ordernum];
			$ref 	  = $result[ref];

			$name = $_POST['cardholdername'];
			$amount = $myorder["chargetotal"];
			$transactionID = $result[r_code];
			$fkProjectID = $projectID;
			$success = qr("INSERT INTO tblPayment (fldName, fldAmount, fldDatetime, fldTransactionID, fldOrderNum, fldRef, fkProjectID) VALUES (\"$name\", $amount, NOW(), \"$transactionID\", \"$orderNum\", \"$ref\", $fkProjectID)");
			if ($success){
				$currentFunding = q1("SELECT fldActualFunding FROM tblProject WHERE pkProjectID = $projectID");
				$newFunding = ($currentFunding + $amount);
				$updateProjectSuccess = qr("UPDATE tblProject SET fldActualFunding = $newFunding WHERE pkProjectID = $projectID");

				$report_msg[] = "Your credit card was successfully processed for " . to_money($amount);
				$showPaymentForm = false;
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
			<div class="featuredBox details inner-col">
				<?
					$vimeoVideoID = str_replace("http://vimeo.com/", "", $fldVideoHTML);
					if ($vimeoVideoID != ""){
						echo "<iframe class=\"video\" src=\"http://player.vimeo.com/video/{$vimeoVideoID}\" width=\"542\" height=\"357\"></iframe>\n";
					}

				?>

				<h3 class="title"><?=$fldTitle?></h3>
				<div class="location">Seattle, WA</div>
				<div class="submitted-by">
					<em>Submitted by:</em> <a href="#"><?=$submittedBy?></a>
				</div>
		        <!--<h3 class="title">Details</h3>-->
				<p>
					<?=$fldDescription?>
				</p>
				<div class="tag-container">
					<?
						foreach($tagArray as $tag){
							echo "<a href=\"#\">$tag</a>\n";
						}
					?>
				</div>
			</div>
			<div class="project-details inner-col">

				<div id="paymentDiv" class="more-details" <? if (!$showPaymentForm) echo "style=\"display:none;\"";?>>
					<h3 class="title">Donation Processing</h3>
					<? echo_msg_box();?>
					<p>

					</p>
					<p><form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="paymentForm">
						<input type="hidden" name="ordertype" value="SALE">
						<input type="hidden" name="action" value="submitPayment">
						<input type="hidden" name="id" value="<?=$projectID?>">
						<!--input type="hidden" name="verbose" value="true"-->

                         Financial Support&nbsp;&nbsp;<br/>
                        $<input name="chargetotal" type="text" />&nbsp;&nbsp; minimum $1

                        <br/><br/>
                        <select name="cardtype" >
							<option value="" SELECTED>--credit card type--
							<option value="01">Visa
							<option value="02">masterCard
							<option value="03">Discover
							<option value="04">AMEX

						</select><br/><br/>
						credit card number<br/>
						<input name="cardnumber" type="text" /><br/>
						name on credit card<br/>
						 <input name="cardholdername" type="text" /> <br/>
						 security code<br/>
						 <input name="secuirty" type="text" />   <br/>      <br/>

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
				  <div class="info-block"> <strong><?=$daysRemaining?></strong> DAYS LEFT </div>
				</div>
				 <div class="slider-container">
					<div class="progress-wrap">
					  <div class="progress-bar"></div>
					</div>
				 </div>
				<div class="project-cta">
					<a href="#" class="support" onClick="$('#paymentDiv').slideDown();$('#socialWidgets').hide();">Support this Project</a>
				</div>
				<div class="more-details" id="socialWidgets">
					<h3 class="title">Social Commentary</h3>
					<div>
					<script>(function(d){
					  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
					  js = d.createElement('script'); js.id = id; js.async = true;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  d.getElementsByTagName('head')[0].appendChild(js);
					}(document));</script>
					<div class="fb-comments" data-href="<?=$thisURL?>" data-num-posts="2" data-width="350"></div>
					</p>

					<p>
					<script>(function(d){
					  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
					  js = d.createElement('script'); js.id = id; js.async = true;
					  js.src = "//connect.facebook.net/en_US/all.js#appId=243349305701029&xfbml=1";
					  d.getElementsByTagName('head')[0].appendChild(js);
					}(document));</script>
					<div class="fb-like" data-href="<?=$thisURL?>" data-send="true" data-width="350" data-show-faces="false" data-action="like"></div>

					<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>



				</div>

			</div>
		  <div>
			 <div style="clear: both;"></div>
			<div class="similar">Similar Projects
				<a href="/browse_projects.php" class="browse-more">Browse other Projects</a>
			</div>
			 <div class="boxLine_first_new">
				<div class="blogTitle_box blogTitle_box_new" id="postsDiv">
				  <ul>

<?
	include('projectlist.inc.php');
?>
					 <!--li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/01.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/02.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/03.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/04.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/05.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/06.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/07.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
						  <h4>Project Title</h4>
						  <p><strong>project tags, tag </strong></p>
						</div>
						</a> </li>
						<li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
						  <h4>Project Title</h4>
						  <p><strong>project tags, tag </strong></p>
						</div>
						</a> </li-->
				  </ul>
				</div>
			 </div>

			 <? include ('sidebar-featuredproject.inc.php'); ?>

			 <div style="clear: both;"></div>

		  </div>
		</div>
       <?
	include("footer.php");
?>