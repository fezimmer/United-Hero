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
        $rewardNum      = $_REQUEST['reward'];
        (int)$donationAmt    = $_REQUEST['amount'];

	//if param not id, it may be pkProjectID
	if(!$projectID) $projectID = $_REQUEST['pkProjectID'];
	if (!$projectID) header("Location: index.php");

	if ($_REQUEST['support'] != "") $showPaymentForm = false;

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
		$projectExpired = false;
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

                #shipping Data
                $shippingstreet    = $_POST["shippingstreet"];
                $shippingcity      = $_POST["shippingcity"];
                $shippingstate     = $_POST["shippingstate"];
                $shippingzip       = $_POST["shippingzip"];
                $shippingemail      = $_POST["shippingemail"];

                    #flags
                    $streetNeeded = true;
                    $cityNeeded = true;
                    $stateNeeded = true;
                    $zipNeeded = true;
                    $emailNeeded = true;

                #rewards Data
                $rewardorder["reward1"]      = $_POST["r1"];
                $rewardorder["reward2"]      = $_POST["r2"];
                $rewardorder["reward3"]      = $_POST["r3"];
                $rewardorder["reward4"]      = $_POST["r4"];
                $rewardorder["reward5"]      = $_POST["r5"];
                $rewardorder["reward6"]      = $_POST["r6"];
                $rewardorder["reward7"]      = $_POST["r7"];

		if ($_POST["debugging"]){
			$myorder["debugging"]="false";
		}

                #before attempting credit-card info, shipping data must be validated
                $shippingDataOkay = false;

                #no need to check shipping info if reward(s) are not being selected
                if(rewardIsSelected($rewardorder)){
                    if($shippingstreet != ""){
                        $streetNeeded = false;
                    }
                    if($shippingcity != ""){
                        $cityNeeded = false;
                    }
                    if($shippingstate != ""){
                        $stateNeeded = false;
                    }
                    if($shippingzip != ""){
                        $zipNeeded = false;
                    }
                    if($shippingemail != ""){
                        $emailNeeded = false;
                    }
                    if($streetNeeded == false && $cityNeeded == false && $stateNeeded == false && $zipNeeded == false && $emailNeeded == false){
                        $shippingDataOkay = true;
                    }
                }else{
                    $shippingDataOkay = true;
                }

                if($shippingDataOkay){
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

        // needs this header due some special formatting
	include("header_proj.php");
?>

<!-- script to show and hide reward box -->
<script language="javascript" type="text/javascript">
    var amount;
    var temp;
    window.onload = function(){
       temp = document.getElementById("chargetotal");
       amount = temp.value;
    }
    
    function changeBox(id, rewardId, value){
        if(document.getElementById(id).style.display == 'block'){
            document.getElementById(id).style.display = 'none';
            document.getElementById(rewardId).value='';
            amount = eval(parseInt(document.getElementById("chargetotal").value)-parseInt(value));
            document.getElementById("chargetotal").value = eval(parseInt(document.getElementById("chargetotal").value)-parseInt(value));
        } else {
            document.getElementById(id).style.display = 'block';
            document.getElementById(rewardId).value='Claimed';
            amount = eval(parseInt(document.getElementById("chargetotal").value)+parseInt(value));
            document.getElementById("chargetotal").value = eval(parseInt(document.getElementById("chargetotal").value)+parseInt(value));
        }
    }
    //oForm.elements["text_element_name"];
    function confSubmit(form){
        if(form.r1.value == "" && form.r2.value == "" && form.r3.value == "" && form.r4.value == "" && form.r5.value == "" && form.r6.value == "" && form.r7.value == ""){
            if(confirm("You have not selected any rewards. Do you wish to continue your submission?")){
                form.action = ("rewards.php?amount=" + amount);
                form.submit();
            }
        }else{
            if(document.getElementById("chargetotal").value < 1){
                alert("I'm sorry, the Financial Support value must be a minimum of $1. Please check your support amount and try again.");
            }// if the amount required for the rewards is under the amount supported ...good
            else if(amount <= document.getElementById("chargetotal").value){
                form.action = ("rewards.php?amount=" + amount);
                form.submit();
            }// ...not good
            else {
                alert("I'm sorry, the Financial Support value does not match the amount required for the selected reward(s). Please check your selection and try again.");
                document.getElementById("chargetotal").value = amount;
            }
        }
    }
</script>

<style>
	a.underline:hover{
		text-decoration: underline;
	}
</style>

                <!--Start Main Container-->
		<div class="mainContainer" style="width:800px; float:left;">
                        <div class="rewardsPage" style="width:770px; float:left;">
                            <h3 class="title" style="text-align:center;font-size:22px;text-transform:none;"><?=$fldTitle?></h3>
                        </div>
                        <div class="rewardsPage">
                            <!-- begin reward -->
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_aspen.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $1
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box1" onclick="changeBox('reward1', 'r1', '1')"
                                            <?if($rewardNum == '1' || $rewardorder["reward1"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward1" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '1' || $rewardorder["reward1"] != ''){echo "block";}else{ echo "none";}?>;">1. Reward </div>
                                    <span class="description">Exclusive updates on the film sent directly to you!</span><br/>
                                    <span class="delivery"><em>Estimated Delivery: Jan 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->
                            
                            <!-- begin reward -->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_FON.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $10
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box2" onclick="changeBox('reward2', 'r2', '10')"
                                            <?if($rewardNum == '2' || $rewardorder["reward2"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward2" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '2' || $rewardorder["reward2"] != ''){echo "block";}else{ echo "none";}?>;">2. Reward </div>
                                    <span class="description">Special thanks on our website and in the film’s end credits, exclusive updates, plus a digital poster e-mailed to you, and a sneak peek at a scene from the film!</span><br/><br/>
                                    <span class="delivery"><em>Estimated Delivery: Mar 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->

                            <!-- begin reward -->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_biracy.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $25
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box3" onclick="changeBox('reward3', 'r3', '25')"
                                            <?if($rewardNum == '3' || $rewardorder["reward3"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward3" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '3' || $rewardorder["reward3"] != ''){echo "block";}else{ echo "none";}?>;">3. Reward </div>
                                    <span class="description">Digital download of the finished film, Sex After Kids postcard autographed by a member of the cast, a ring tone by the film&rsquo;s composer, plus everything above!</span><br/><br/>
                                    <span class="delivery"><em>Estimated Delivery: Mar 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->

                            <!-- begin reward -->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_chloe.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $50
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box4" onclick="changeBox('reward4', 'r4', '50')"
                                            <?if($rewardNum == '4' || $rewardorder["reward4"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward4" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '4' || $rewardorder["reward4"] != ''){echo "block";}else{ echo "none";}?>;">4. Reward </div>
                                    <span class="description">Exclusive updates on the film sent directly to you!</span><br/><br/>
                                    <span class="delivery"><em>Estimated Delivery: Mar 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->

                            <!-- begin reward -->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_cove.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $100
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box5" onclick="changeBox('reward5', 'r5', '100')"
                                            <?if($rewardNum == '5' || $rewardorder["reward5"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward5" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '5' || $rewardorder["reward5"] != ''){echo "block";}else{ echo "none";}?>;">5. Reward </div>
                                    <span class="description">Special thanks on our website and in the film’s end credits, exclusive updates, plus a digital poster e-mailed to you, and a sneak peek at a scene from the film!</span><br/><br/>
                                    <span class="delivery"><em>Estimated Delivery: Mar 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->

                            <!-- begin reward -->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_covelinksgolf.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $500
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box6" onclick="changeBox('reward6', 'r6', '500')"
                                            <?if($rewardNum == '6' || $rewardorder["reward6"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward6" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '6' || $rewardorder["reward6"] != ''){echo "block";}else{ echo "none";}?>;">6. Reward </div>
                                    <span class="description">Digital download of the finished film, Sex After Kids postcard autographed by a member of the cast, a ring tone by the film&rsquo;s composer, plus everything above!</span><br/><br/>
                                    <span class="delivery"><em>Estimated Delivery: Mar 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->

                            <!-- begin reward -->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <img src="/images/sm_design_georgies.png" width="180"/>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title">Support $1000
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box7" onclick="changeBox('reward7', 'r7', '1000')"
                                            <?if($rewardNum == '7' || $rewardorder["reward7"] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward7" class="boxNum" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == '7' || $rewardorder["reward7"] != ''){echo "block";}else{ echo "none";}?>;">7. Reward </div>
                                    <span class="description">Special thanks on our website and in the film’s end credits, exclusive updates, plus a digital poster e-mailed to you, and a sneak peek at a scene from the film!</span><br/><br/>
                                    <span class="delivery"><em>Estimated Delivery: Mar 2013</em></span>
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->

                        <div class="rewardsPage" style="width:760px; float:left;">
                            <a href="/project_test.php?id=<?=$projectID?>" style="padding-left:24px;">Return to Project page...</a>
                        </div>
                           
                </div>
                <!--End Main Container-->

                <div class="project-details2" style="float:right !important;width:342px;margin-left:0px;">
                        <h3 class="title">Goal <?=$fldDesiredFundingAmount?></h3>
				<div class="progress-info">
				  <div class="info-block first"> <strong><?=$percentComplete?>%</strong> FUNDED </div>
				  <div class="info-block"> <strong><?=$fldActualFunding?></strong> SO FAR </div>
				  <div class="info-block"> <?=$daysText?> <span style="font-size:9px;" align="top" valign="top">(<a style="color: red;" href="#?w=780" rel="daysLeftPopup" class="poplight underline" title="Information on Days Left">?</a>)</span></div>
				</div>
                </div>
                <div class="project-details2" style="float:right !important;width:342px;margin-left:0px;">
				<div id="paymentDiv" class="more-details">
					<? echo_msg_box();?>
					<p>
						<img src="/images/Bank-America.jpg" width="200" \>
					</p>
					<p><form name="paymentForm" action="" method="POST" id="paymentForm">
						<input type="hidden" name="ordertype" value="SALE">
						<input type="hidden" name="action" value="submitPayment">
						<input type="hidden" name="id" value="<?=$projectID?>">

                                                <input id="r1" type="hidden" name="r1" value="<?if($rewardNum == '1' || $rewardorder["reward1"] != '') echo "Claimed";?>"/>
                                                <input id="r2" type="hidden" name="r2" value="<?if($rewardNum == '2' || $rewardorder["reward2"] != '') echo "Claimed";?>"/>
                                                <input id="r3" type="hidden" name="r3" value="<?if($rewardNum == '3' || $rewardorder["reward3"] != '') echo "Claimed";?>"/>
                                                <input id="r4" type="hidden" name="r4" value="<?if($rewardNum == '4' || $rewardorder["reward4"] != '') echo "Claimed";?>"/>
                                                <input id="r5" type="hidden" name="r5" value="<?if($rewardNum == '5' || $rewardorder["reward5"] != '') echo "Claimed";?>"/>
                                                <input id="r6" type="hidden" name="r6" value="<?if($rewardNum == '6' || $rewardorder["reward6"] != '') echo "Claimed";?>"/>
                                                <input id="r7" type="hidden" name="r7" value="<?if($rewardNum == '7' || $rewardorder["reward7"] != '') echo "Claimed";?>"/>
						<!--input type="hidden" name="verbose" value="false"-->

                         Financial Support&nbsp;&nbsp;<br/>
                        $<input id="chargetotal" name="chargetotal" type=INT size="8" value="<?=$donationAmt?>"/>&nbsp;&nbsp; minimum $1

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
							<option value="" SELECTED>--Expiration Month--
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

					    <br/><br/><br/><br/>
                                            <strong>Shipping Address</strong>
                                            <br/><br/>
                                            <?
                                                if($action == "submitPayment"){
                                                    if(!$shippingDataOkay){
                                                        echo "<span style='color:red;font-weight:bold;'>-- Required fields marked by a * --</span><br/><br/>";
                                                    }
                                                }
                                            ?>

                                                Street Address <?if(!$shippingDataOkay && $action == "submitPayment" && $streetNeeded == true) echo "<span style='color:red;font-weight:bold;'>*</span>";?><br/>
                                                <input name="shippingstreet" type="text" size="25" value="<?=$shippingstreet?>"/> <br/><br />
                                                City <?if(!$shippingDataOkay && $action == "submitPayment" && $cityNeeded == true) echo "<span style='color:red;font-weight:bold;'>*</span>";?><br/>
                                                <input name="shippingcity" type="text" size="15" value="<?=$shippingcity?>"/> <br/><br />
                                                State <?if(!$shippingDataOkay && $action == "submitPayment" && $stateNeeded == true) echo "<span style='color:red;font-weight:bold;'>*</span>";?><br/>
                                                <input name="shippingstate" type="text" size="2" maxlength="2" value="<?=$shippingstate?>"/> <br/><br />
                                                Zip Code <?if(!$shippingDataOkay && $action == "submitPayment" && $zipNeeded == true) echo "<span style='color:red;font-weight:bold;'>*</span>";?><br/>
                                                <input name="shippingzip" type="text" size="8" maxlength="10" value="<?=$shippingzip?>"/> <br/><br />
                                                Email&nbsp;<em>(for confirmation)</em><?if(!$shippingDataOkay && $action == "submitPayment" && $emailNeeded == true) echo "<span style='color:red;font-weight:bold;'>*</span>";?><br/>
                                                <input name="shippingemail" type="text" size="25" value="<?=$shippingemail?>"/> <br/><br />
                                            * Claim your reward(s) by checking the reward box
						<div class="reward">
                                                    <div class="num" style="float:right;cursor:pointer;" onClick="confSubmit(document.getElementById('paymentForm'))">Submit Payment</div>
						</div>
                        </form>

				</div>
			</div>

<?
	include("footer.php");
?>