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
        $donationAmt    = q1("SELECT fldSupport FROM tblRewards WHERE pkRewardID = \"$rewardNum\"");


	//if param not id, it may be pkProjectID
	if(!$projectID) $projectID = $_REQUEST['pkProjectID'];
	if (!$projectID) header("Location: index.php");

	if ($_REQUEST['support'] != "") $showPaymentForm = false;

	$projectInfo 		= qr("SELECT pkProjectID, fldTitle, fldDescription, fldImage, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID FROM tblProject WHERE pkProjectID = \"$projectID\"");
	if ($projectInfo){
		extract($projectInfo);
                $ProjectTitle = $fldTitle;
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
		$daysText = "<strong style='color: blue;'>PROJECT UNDER REVIEW</strong>";
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

                //fill donation amount from post
                if($donationAmt == ""){
                    foreach ($rewardorder as $reward){
                        if($reward != null){
                            $donationAmt += q1("SELECT fldSupport FROM tblRewards WHERE pkRewardID = \"$reward\"");
                        }
                    }
                }
                
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
                            $pkPaymentId = q1("SELECT pkPaymentID FROM tblPayment WHERE fldTransactionID = \"$transactionID\"");

                            //reward code to process and update parent reward and insert newly purchased child reward
                            $rewardSuccess = true;
                            $rewardsLeftResult = true;
                            $rSuccess = true;
                            foreach ($rewardorder as $reward){
                                if($reward != null){
                                    //get parent rewards information
                                    $pReward = qr("SELECT * FROM tblRewards WHERE pkRewardID = \"$reward\"");
                                    extract($pReward);
                                    //parent reward info to be entered to child reward
                                    $rTitle         = $pReward['fldTitle'];
                                    $rDescription   = $pReward['fldDescription'];
                                    $rSupport       = $pReward['fldSupport'];
                                    $rMonth         = $pReward['fldRewardMonth'];
                                    $rYear          = $pReward['fldRewardYear'];

                                    $parent = qr("SELECT * FROM tblRewards WHERE pkRewardID = \"$reward\"");
                                    extract($parent);
                                    if($parent['fldNumAvailable'] > 0){
                                        //number of rewards is limited
                                        if($parent['fldRewardsLeft'] > 0){
                                            $num = intval($parent['fldRewardsLeft']) - 1;
                                            //update number of rewards left for parent reward
                                            $rewardsLeftResult = qr("UPDATE tblRewards SET fldRewardsLeft = \"$num\" WHERE pkRewardID = \"$reward\"");
                                            //create child reward entry
                                            $rSuccess = qr("INSERT INTO tblRewards (fldTitle, fldDescription, fldSupport, fldRewardMonth, fldRewardYear, fkPaymentID, fkProjectID, fldStreetAddress, fldCity, fldState, fldZipCode, fldConfEmail, fldName)
                                                VALUES (\"$rTitle\", \"$rDescription\", \"$rSupport\", \"$rMonth\", \"$rYear\", \"$transactionID\", \"$fkProjectID\", \"$shippingstreet\", \"$shippingcity\", \"$shippingstate\", \"$shippingzip\", \"$shippingemail\", \"$name\")");
                                        }else{
                                            $error_msg[] = "The selected reward has reached its limit. Please choose a different reward.";
                                            $rewardSuccess = false;
                                        }
                                    }else{
                                        //no limit on reward
                                        //create child reward entry
                                        $rSuccess = qr("INSERT INTO tblRewards (fldTitle, fldDescription, fldSupport, fldRewardMonth, fldRewardYear, fkPaymentID, fkProjectID, fldStreetAddress, fldCity, fldState, fldZipCode, fldConfEmail, fldName)
                                                VALUES (\"$rTitle\", \"$rDescription\", \"$rSupport\", \"$rMonth\", \"$rYear\", \"$transactionID\", \"$fkProjectID\", \"$shippingstreet\", \"$shippingcity\", \"$shippingstate\", \"$shippingzip\", \"$shippingemail\", \"$name\")");
                                    }
                                }
                                if(!$rSuccess || !$rewardsLeftResult)
                                    $rewardSuccess = false;
                            }
                            //end reward code

                            if ($success && $rewardSuccess){

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
    
    function changeBox(id, rewardId, value, pkID){
        if(document.getElementById(id).style.display == 'block'){
            document.getElementById(id).style.display = 'none';
            document.getElementById(rewardId).value='';
            amount = eval(parseInt(document.getElementById("chargetotal").value)-parseInt(value));
            document.getElementById("chargetotal").value = eval(parseInt(document.getElementById("chargetotal").value)-parseInt(value));
        } else {
            document.getElementById(id).style.display = 'block';
            document.getElementById(rewardId).value=pkID;
            amount = eval(parseInt(document.getElementById("chargetotal").value)+parseInt(value));
            document.getElementById("chargetotal").value = eval(parseInt(document.getElementById("chargetotal").value)+parseInt(value));
        }
    }

    function confSubmit(form){
        if(form.r1.value == "" && form.r2.value == "" && form.r3.value == "" && form.r4.value == "" && form.r5.value == "" && form.r6.value == "" && form.r7.value == ""){
            if(confirm("You have not selected any rewards. Do you wish to continue your submission?")){
                form.action = ("rewards.php?id=<?=$projectID?>");
                form.submit();
            }
        }else{
            if(financialFormIsGood()){
                if(document.getElementById("chargetotal").value < 1){
                    alert("I'm sorry, the Financial Support value must be a minimum of $1. Please check your support amount and try again.");
                }// if the amount required for the rewards is less than the amount supported ...good
                else if(amount <= document.getElementById("chargetotal").value){
                    form.action = ("rewards.php?id=<?=$projectID?>");
                    form.submit();
                }// ...not good
                else {
                    alert("I'm sorry, the Financial Support value does not match the amount required for the selected reward(s). Please check your selection and try again.");
                    document.getElementById("chargetotal").value = amount;
                }
            }
        }
    }

    function financialFormIsGood(){
        formIsGood = true;
        if(document.getElementById("chargetotal").value == ""){
            formIsGood = false;
            document.getElementById("chargetotalError").style.display = "block";
        }else{
            document.getElementById("chargetotalError").style.display = "none";
        }

        if(document.getElementById("cardtype").value == ""){
            formIsGood = false;
            document.getElementById("cardtypeError").style.display = "block";
        }else{
            document.getElementById("cardtypeError").style.display = "none";
        }

        if(document.getElementById("cardnumber").value == ""){
            formIsGood = false;
            document.getElementById("cardnumberError").style.display = "block";
        }else{
            document.getElementById("cardnumberError").style.display = "none";
        }

        if(document.getElementById("cardholdername").value == ""){
            formIsGood = false;
            document.getElementById("cardholdernameError").style.display = "block";
        }else{
            document.getElementById("cardholdernameError").style.display = "none";
        }

        if(document.getElementById("security").value == ""){
            formIsGood = false;
            document.getElementById("securityError").style.display = "block";
        }else{
            document.getElementById("securityError").style.display = "none";
        }

        if(document.getElementById("cardexpyear").value == "" || document.getElementById("cardexpmonth").value == ""){
            formIsGood = false;
            document.getElementById("expError").style.display = "block";
        }else{
            document.getElementById("expError").style.display = "none";
        }

        if(document.getElementById("shippingstreet").value == ""){
            formIsGood = false;
            document.getElementById("streetError").style.display = "block";
        }else{
            document.getElementById("streetError").style.display = "none";
        }

        if(document.getElementById("shippingcity").value == ""){
            formIsGood = false;
            document.getElementById("cityError").style.display = "block";
        }else{
            document.getElementById("cityError").style.display = "none";
        }

        if(document.getElementById("shippingstate").value == ""){
            formIsGood = false;
            document.getElementById("stateError").style.display = "block";
        }else{
            document.getElementById("stateError").style.display = "none";
        }

        if(document.getElementById("shippingzip").value == ""){
            formIsGood = false;
            document.getElementById("zipError").style.display = "block";
        }else{
            document.getElementById("zipError").style.display = "none";
        }

        if(document.getElementById("shippingemail").value == ""){
            formIsGood = false;
            document.getElementById("emailError").style.display = "block";
        }else{
            document.getElementById("emailError").style.display = "none";
        }

        return formIsGood;
    }
</script>

<style>
	.formError { font-weight: bold; color:red; margin-left: 5px; display:none;}
        a.underline:hover{
		text-decoration: underline;
	}
</style>

                <!--Start Main Container-->
		<div class="mainContainer" style="width:800px; float:left;">
                        <div class="rewardsPage" style="width:770px; float:left;">
                            <h3 class="title" style="text-align:center;font-size:22px;text-transform:none;"><?=$ProjectTitle?></h3>
                        </div>
                    <?
                        $rewards = q("SELECT * FROM tblRewards WHERE fkProjectID = \"$projectID\" && fkPaymentID <= \"0\" ORDER BY pkRewardID");
                        $count = 1;
                        foreach ($rewards as $reward){
                            if($reward['fldRewardsLeft'] > 0 || $reward['fldNumAvailable'] == 0){
                    ?>
                         <!-- begin reward <?=$count?>-->
                        <div class="rewardsPage">
                            <div class="reward">
                                <div style="width:180px;float:left;padding-right:15px;">
                                    <?if($reward['fldImage'] != ""){?>
                                    <img src="/magick.php/<?=$reward['fldImage']?>?resize(180)" alt="REWARD IMAGE <?=$count?>"/>
                                    <?}else{?>
                                    <div style="width:180px;">&nbsp;</div>
                                    <?}?>
                                </div>
                                <div style="width:560px; float:left;">
                                    <h3 class="title"><?=$reward['fldTitle']?>&nbsp;-&nbsp;$<?=$reward['fldSupport']?>
                                        <span style="float:right;">I would like this reward!
                                        <input type="checkbox" name="box<?=$count?>" onclick="changeBox('reward<?=$count?>', 'r<?=$count?>', '<?=$reward['fldSupport']?>', '<?=$reward['pkRewardID']?>')"
                                            <?if($rewardNum == $reward['pkRewardID'] || $rewardorder["reward".$count] != '') echo "checked";?>
                                        /></span></h3><br/>
                                    <div id="reward<?=$count?>" style="margin:0 25px 0 15px;float:right;display:<?if($rewardNum == $reward['pkRewardID'] || $rewardorder["reward".$count] != ''){echo "block";}else{ echo "none";}?>;">
                                        <div class="boxNum"><?=$count?>. Reward</div><br/><br/>
                                        <?if($reward['fldNumAvailable'] != 0){?>
                                        <div class="claim"><?=($reward['fldNumAvailable'] - $reward['fldRewardsLeft'])?> of <?=$reward['fldNumAvailable']?> Claimed</div>
                                        <?}?>
                                    </div>
                                        <span class="delivery"><em>Estimated Delivery Date:<br/><span style="padding-left:40px;"><?=getMonthForReward($reward['fldRewardMonth'])?> <?=$reward['fldRewardYear']?></span></em></span><br/><br/>
                                    <span class="description"><?=$reward['fldDescription']?></span>                                    
                                </div>
                            </div>
                        </div>
                            <!-- end reward -->
                    <?
                            }
                            $count++;
                        }
                    ?>

                        <div class="rewardsPage" style="width:772px; float:left;">
                            <a href="/project.php?id=<?=$projectID?>" style="padding-left:24px; font-size: 25px;">Return to Product page...</a>
                        </div>
                           
                </div>
                <!--End Main Container-->

                <div class="project-details2" style="float:right !important;width:342px;margin-left:0px;">
                        <h3 class="title">Goal <?=$fldDesiredFundingAmount?></h3>
				<div class="progress-info">
				  <div class="info-block first"> <strong><?=$percentComplete?>%</strong> FUNDED </div>
				  <div class="info-block"> <strong><?=$fldActualFunding?></strong> SO FAR </div>
				  <div class="info-block"><br/> <?=$daysText?> <!--<span style="font-size:9px;" align="top" valign="top">(<a style="color: red;" href="#?w=780" rel="daysLeftPopup" class="poplight underline" title="Information on Days Left">?</a>)</span>-->
                                  </div>
				</div>
                </div>
                <div class="project-details2" style="float:right !important;width:342px;margin-left:0px;">
                    <div id="paymentDiv" class="more-details">
                        <? echo_msg_box();?>
                        <p>
                            <img src="/images/Bank-America.jpg" width="200" alt="Bank Of America"\>
                        </p>
                        <form name="paymentForm" action="" method="POST" id="paymentForm">
                            <input type="hidden" name="ordertype" value="SALE"/>
                            <input type="hidden" name="action" value="submitPayment"/>
                            <input type="hidden" name="id" value="<?=$projectID?>"/>
                    <?
                            $rewards = q("SELECT * FROM tblRewards WHERE fkProjectID = \"$projectID\" AND fkPaymentID <= \"0\" ORDER BY pkRewardID");
                            $i = 1;
                            foreach ($rewards as $reward){
                                extract($reward);
                    ?>

                            <input id="r<?=$i?>" type="hidden" name="r<?=$i?>" value="<?if($rewardNum == $reward['pkRewardID'] || $rewardorder["reward".$i] != ''){ echo $reward['pkRewardID'];}?>"/>
                    <?
                                $i++;
                            }
                    ?>
                            Financial Support&nbsp;&nbsp;<br/>
                            $<input id="chargetotal" readonly="readonly" name="chargetotal" type=INT size="8" value="<?if($donationAmt != null) echo $donationAmt; else echo '0';?>"/>&nbsp;&nbsp; minimum $1
                            <span class="formError" id="chargetotalError">charge total is required</span>
                            <br/><br/>
                            <select name="cardtype" id="cardtype">
                                <option value="" SELECTED>--Credit Card Type--
                                <option value="01">Visa
                                <option value="02">Master Card
                                <option value="03">Discover
                                <option value="04">AMEX
                            </select><span class="formError" id="cardtypeError">card type is required</span>
                                <br/><br/>
                            Credit Card Number<br/>
                            <input name="cardnumber" id="cardnumber" type="text" /><span class="formError" id="cardnumberError">card number is required</span><br/><br />
                            Name on Credit Card<br/>
                            <input name="cardholdername" id="cardholdername" type="text" /><span class="formError" id="cardholdernameError">card holder name is required</span><br/><br />
                            Security Code<br/>
                            <input name="secuirty" id="security" type="text" size="2" maxlength="3" />&nbsp;&nbsp; 3 digit number on back of credit card
                                <br/>
                                <span class="formError" id="securityError">security code is required</span>
                                <br/>
                            <select name="cardexpmonth" id="cardexpmonth">
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
                            <select name="cardexpyear" id="cardexpyear">
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
                                <br/>
                                <span class="formError" id="expError">expiration month/year is required</span>
                                <br/><br/><br/>
                            <strong>Shipping Address</strong>
                            <br/><br/>
                    <?
                            if($action == "submitPayment"){
                                if(!$shippingDataOkay){
                                    echo "<span style='color:red;font-weight:bold;'>-- Required fields marked by a * --</span><br/><br/>";
                                }
                            }
                    ?>
                            Street Address<br/>
                            <input name="shippingstreet" id="shippingstreet" type="text" size="25" value="<?=$shippingstreet?>"/>
                            <span class="formError" id="streetError">street address is required</span><br/><br/>

                            City<br/>
                            <input name="shippingcity" id="shippingcity" type="text" size="15" value="<?=$shippingcity?>"/>
                            <span class="formError" id="cityError">city is required</span><br/><br/>

                            State<br/>
                            <input name="shippingstate" id="shippingstate" type="text" size="2" maxlength="2" value="<?=$shippingstate?>"/>
                            <span class="formError" id="stateError">state is required</span><br/><br/>

                            Zip Code<br/>
                            <input name="shippingzip" id="shippingzip" type="text" size="8" maxlength="10" value="<?=$shippingzip?>"/>
                            <span class="formError" id="zipError">zip code is required</span><br/><br/>

                            Email&nbsp;<em>(for confirmation)</em><br/>
                            <input name="shippingemail" id="shippingemail" type="text" size="25" value="<?=$shippingemail?>"/>
                            <span class="formError" id="emailError">confirmation email address is required</span><br/><br/>
                            * Claim your reward(s) by checking the reward box

                            <?if ($fldStatus != 'review'){?>
                            <div class="reward">
                                <div class="num" style="float:right;cursor:pointer;" onClick="confSubmit(document.getElementById('paymentForm'))">Submit Payment</div>
                            </div>
                            <?}?>
                        </form>
                    </div>
                </div>
<?
	include("footer.php");
?>