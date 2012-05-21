<?
	//count Number of rewards submitted through _POST
	$finalCount = rewardCount($rewardsTitle, $rewardsDesc, $rewardsSupport, $rewardsImage, $rewardsDelete);

       //add rewards already in database
	if($finalCount == 1){
		$finalCount += q1("SELECT COUNT(*) FROM tblRewards WHERE fkProjectID = \"$id\" AND fkPaymentID <= \"0\"");
	}

	if($reward_errors == null){
		//dont show save rewards button
		$rewardsSave = "none";
	} else {
		//validation errors occured
		$rewardsSave = "block";
	}

	//set if rewards should be readonly
	$readOnly = "";
	if($readOnlyKeyword){
		$readOnly = "readonly=\"readonly\"";
		$disabled = "disabled=\"disabled\"";
	}
        if($finalCount == 1) $rewardNum = 2;
        else  $rewardNum = $finalCount;
?>

<script language="javascript" type="text/javascript">
    var rewardNum = <?=$rewardNum?>;

    $(document).ready(function() {
        $("a#addlink").click(function(){
        	$("#rewards-save").show();
            if(rewardNum > 7){
                alert("Maximum Rewards reached");
            }else{
                $("#linkList").append("<!-- begin reward --><div class='reward-details' id='r" + rewardNum + "'>" + "<div style='width:47%;float:right;cursor:pointer;cursor:hand;'><a id='removal" + rewardNum + "' removalhref='#' onclick=\"removeReward('r" + rewardNum + "')\">Click to Remove&nbsp;&nbsp;</a></div>Title*<br/><" + "input type='text' name='rewardTitle" + rewardNum + "' id='rewardTitle" + rewardNum + "' size='23'/><br/><br/>Description*<br/><" + "textarea name='rewardDesc" + rewardNum + "' id='rewardDesc" + rewardNum + "' cols='23' rows='5' maxlength='200'></textarea><br/><br/><div style='width:48%; float:left;margin-top:4px;'>" + "Purchase Price*<br/>" + "$<" + "input type='text' name='rewardSupport" + rewardNum + "' id='rewardSupport" + rewardNum + "' size='5'/><br/></div><div style='width:52%; float:right;'>Limit # Available<" + "input type='checkbox' id='limitAvail" + rewardNum + "' name='limitAvail" + rewardNum + "' onclick='changeAvailBox(\"limitAvail" + rewardNum + "\", \"numAvailable" + rewardNum + "\")'><" + "input style='display:none;' type='text' name='numAvailable" + rewardNum + "' id='numAvailable" + rewardNum + "' size='5' maxlength='5'/></div><" + "span style='margin-left:7px;'><div>&nbsp;</div>Est. Shipping Date*<br/><" + "select name='rewardMonth" + rewardNum + "' id='rewardMonth" + rewardNum + "'><option value='' SELECTED>Month*</option><" + "option value='01'>JANUARY (01)</option><option value='02'>FEBRUARY (02)</option><" + "option value='03'>March (03)</option><option value='04'>APRIL (04)</option><" + "option value='05'>MAY (05)</option><option value='06'>JUNE (06)</option><" + "option value='07'>JULY (07)</option><option value='08'>AUGUST (08)</option><" + "option value='09'>SEPTEMBER (09)</option><option value='10'>OCTOBER (10)</option><" + "option value='11'>NOVEMBER (11)</option><option value='12'>DECEMBER (12)</option></select>&nbsp;" + "</span><" + "span style='margin-left:7px;'><" + "select name='rewardYear" + rewardNum + "' id='rewardYear" + rewardNum + "'><option value='' SELECTED>Year*</option><option value='2012'>2012</option><" + "option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><" + "option value='2016'>2016</option><option value='2017'>2017</option><option value='2018'>2018</option><" + "option value='2019'>2019</option><option value='2020'>2020</option><option value='2021'>2021</option></select><" + "/span><br/><br/>Image Upload<" + "input type='file' size='15' name='rewardImage" + rewardNum + "' id='rewardImage" + rewardNum + "'/><br/></div><!-- end reward -->");
                if(rewardNum > 1){
                    document.getElementById('removal'+(rewardNum-1)).style.display = 'none';
                }
                rewardNum++;
                return false;
            }
        });
     });

    function changeAvailBox(id, id2){
        if(document.getElementById(id).checked){
           document.getElementById(id2).style.display = 'block';
        }else{
           document.getElementById(id2).style.display = 'none';
           document.getElementById(id2).value = "";
        }
    }
    function removeReward(id, deleteID){
        $("#deleteList").append("<input type='hidden' id='deleteReward" + (rewardNum-1) + "' name='deleteReward" + (rewardNum-1) + "' value='" + deleteID + "'/>");
        document.getElementById(id).parentNode.removeChild(document.getElementById(id));
        rewardNum--;
        document.getElementById('removal'+(rewardNum-1)).style.display = 'block';
        return false;
    }
</script>
<style>
    .title a:hover{
        text-decoration: underline;
    }
</style>
 <!-- start rewards section -->
	<div class="reward-div">
            <div class="reward-details">
                <h3 class="title">Rewards</h3>
                    <?
                    $error_msg = $reward_errors;
                    echo_msg_box();
                    ?>
            </div>

            <div id="linkList">
                <?

                    //grab reward values from db
                    $reward = q("SELECT * FROM tblRewards WHERE fkProjectID = \"$id\" AND fkPaymentID <= \"0\" ORDER BY pkRewardID");
                    $count = 0;
                    $i = 1;
                    if($finalCount == 1 && !$readOnlyKeyword){
                        echo ("<!-- begin reward --><div class='reward-details' id='r".$i . "'>" . "<div style='width:47%;float:right;cursor:pointer;cursor:hand;'><a id='removal" . $i . "' removalhref='#' onclick=\"removeReward('r" . $i . "')\">Click to Remove&nbsp;&nbsp;</a></div>Title*<br/><" . "input type='text' name='rewardTitle" . $i . "' id='rewardTitle" . $i . "' size='23'/><br/><br/>Description*<br/><" . "textarea name='rewardDesc" . $i . "' id='rewardDesc" . $i . "' cols='23' rows='5'></textarea><br/><br/><div style='width:48%; float:left;margin-top:4px;'>" . "Purchase Price*<br/>" . "$<" . "input type='text' name='rewardSupport" . $i . "' id='rewardSupport" . $i . "' size='5'/><br/></div><br/><div style='width:52%; float:right;'>Limit # Available<" . "input type='checkbox' id='limitAvail" . $i . "' name='limitAvail" . $i . "' onclick='changeAvailBox(\"limitAvail" . $i . "\", \"numAvailable" . $i . "\")'><" . "input style='display:none;' type='text' name='numAvailable" . $i . "' id='numAvailable" . $i . "' size='5' maxlength='5'/></div><" . "span style='margin-left:7px;'><div>&nbsp;</div>Est. Shipping Date*<br/><" . "select name='rewardMonth" . $i . "' id='rewardMonth" . $i . "'><option value='' SELECTED>Month*</option><" . "option value='01'>JANUARY (01)</option><option value='02'>FEBRUARY (02)</option><" . "option value='03'>March (03)</option><option value='04'>APRIL (04)</option><" . "option value='05'>MAY (05)</option><option value='06'>JUNE (06)</option><" . "option value='07'>JULY (07)</option><option value='08'>AUGUST (08)</option><" . "option value='09'>SEPTEMBER (09)</option><option value='10'>OCTOBER (10)</option><" . "option value='11'>NOVEMBER (11)</option><option value='12'>DECEMBER (12)</option></select>&nbsp;" . "</span><" . "span style='margin-left:7px;'><" . "select name='rewardYear" . $i . "' id='rewardYear" . $i . "'><option value='' SELECTED>Year*</option><option value='2012'>2012</option><" . "option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><" . "option value='2016'>2016</option><option value='2017'>2017</option><option value='2018'>2018</option><" . "option value='2019'>2019</option><option value='2020'>2020</option><option value='2021'>2021</option></select><" . "/span><br/><br/>Image Upload<" . "input type='file' size='15' name='rewardImage" . $i . "' id='rewardImage" . $i . "'/><br/></div><!-- end reward -->");
                    }
                    for($i;$i<$finalCount;$i++){
                        //attempt to pull reward info from db
                        $rwd = $reward[$count];
                        $fileName = null;
                        $rewardID = null;
                        $count++;

                        //if the reward is in the db, fill the _POST Arrays
                        if($rwd != null){
                            extract($rwd);
                            $rewardsTitle[$i]   =   $rwd['fldTitle'];
                            $rewardsDesc[$i]    =   $rwd['fldDescription'];
                            $rewardsSupport[$i] =   $rwd['fldSupport'];
                            $rewardsAvail[$i]   =   $rwd['fldNumAvailable'];
                            $rewardsMonth[$i]   =   $rwd['fldRewardMonth'];
                            $rewardsYear[$i]    =   $rwd['fldRewardYear'];
                            $fileName           =   $rwd['fldImage'];
                            $rewardID           =   $rwd['pkRewardID'];
                        }
                        /*echo "Title: " . $rewardsTitle[$i] . ", Desc: " . $rewardsDesc[$i] . ", Supp: " . $rewardsSupport[$i] .
                            ", Avail: " . $rewardsAvail[$i] . ", Month: " . $rewardsMonth[$i] . ", Year: " . $rewardsYear[$i] .
                            ", Image: " . $fileName . ", ID: " . $rewardID;*/

                        echo "<!-- begin reward -->" .
                            "<div class='reward-details' id='r" . $i . "'>";
                    if($readOnly == "") {
                        echo "    <div style='width:47%;float:right;cursor:pointer;cursor:hand;'><a ";
                        if(($finalCount - $i) == 1 && $submitPage != "my_account.php"){
                            echo "style='display:block;'";
                        }else{
                            echo "style='display:none;'";
                        }
                        echo " id='removal".$i."' href='#' onclick=\"removeReward('r" . $i . "', '" . $rewardID . "')\">Click to Remove&nbsp;&nbsp;</a>" .
                            "</div>";
                    }
                        echo "Title*<br/>" .
                            "    <input value='" .$rewardsTitle[$i] . "' type='text'";
                        echo $readOnly;
                            echo "name='rewardTitle" . $i . "' id='rewardTitle" . $i . "' size='23'/><br/><br/>" .
                            "    Description*<br/><textarea name='rewardDesc" . $i . "'";
                            echo $readOnly;
                            echo "id='rewardDesc" . $i . "' cols='23' rows='5' maxlength='200'>";
                        if($rewardsDesc[$i] != '' && $rewardsDesc[$i] != null)echo $rewardsDesc[$i];
                        echo "</textarea><br/><br/>" .
                            "    <div style='width:48%; float:left;margin-top:4px;'>" .
                            "        Purchase Price*<br/>" .
                            "        $<input value='" .$rewardsSupport[$i] . "' type='text'";
                            echo $readOnly;
                            echo "name='rewardSupport" . $i . "' id='rewardSupport" . $i . "' size='5'/><br/>" .
                            "    </div>" .
                            "    <div style='width:52%; float:right;'>";
                    if($readOnly != ""){
                        echo "<p style='margin-top:3px; '>Limit # Available</p>";
                    }else{
                                echo "        Limit # Available<input type='checkbox' ";
                        if($rewardsAvail[$i] == 0){
                            $rewardsAvail = "";
                        }
                        if($rewardsAvail[$i] != "" && $rewardsAvail[$i] != null){
                            echo " checked='yes' ";
                        }
                        echo $readOnly;
                        echo "id='limitAvail" . $i . "' name='limitAvail" . $i . "' onclick='changeAvailBox(\"limitAvail" . $i . "\", \"numAvailable" . $i . "\")'>";
                    }
                        echo "        <input value='" .$rewardsAvail[$i] . "' style='display:";
                        if($rewardsAvail[$i] != "" && $rewardsAvail[$i] != null){
                            echo " block;";
                        }else{
                            echo " none;";
                        }
                        echo "' type='text' name='numAvailable" . $i . "'";
                        echo $readOnly;
                        echo "id='numAvailable" . $i . "' size='5' maxlength='5'/>";
                        echo "</div>" .
                            "    <span style='margin-left:7px;'><div>&nbsp;</div>Est. Shipping Date*<br/>" .
                            "    <select value='" .$rewardsMonth[$i] . "' name='rewardMonth" . $i . "'";
                        echo $disabled;
                        echo "id='rewardMonth" . $i . "'>" .
                            "            <option value='".$rewardsMonth[$i]."' SELECTED>";
                        if($rewardsMonth[$i] != "" && $rewardsMonth[$i] != null) echo getRewardMonth($rewardsMonth[$i]);
                        else echo "Month*<br/>";
                        echo "</option><option value='01'>JANUARY (01)</option>" .
                            "            <option value='02'>FEBRUARY (02)</option>" .
                            "            <option value='03'>March (03)</option>" .
                            "            <option value='04'>APRIL (04)</option>" .
                            "            <option value='05'>MAY (05)</option>" .
                            "            <option value='06'>JUNE (06)</option>" .
                            "            <option value='07'>JULY (07)</option>" .
                            "            <option value='08'>AUGUST (08)</option>" .
                            "            <option value='09'>SEPTEMBER (09)</option>" .
                            "            <option value='10'>OCTOBER (10)</option>" .
                            "            <option value='11'>NOVEMBER (11)</option>" .
                            "            <option value='12'>DECEMBER (12)</option>" .
                            "    </select>&nbsp;</span>" .
                            "    <span style='margin-left:7px;'>" .
                            "    <select value='" .$rewardsYear[$i] . "' name='rewardYear" . $i . "'";
                        echo $disabled;
                        echo "id='rewardYear" . $i . "'>" .
                            "            <option value='".$rewardsYear[$i]."' SELECTED>";
                        if($rewardsYear[$i] != "" && $rewardsYear[$i] != null) echo $rewardsYear[$i];
                        else echo "Year*";
                        echo "</option><option value='2012'>2012</option>" .
                            "            <option value='2013'>2013</option>" .
                            "            <option value='2014'>2014</option>" .
                            "            <option value='2015'>2015</option>" .
                            "            <option value='2016'>2016</option>" .
                            "            <option value='2017'>2017</option>" .
                            "            <option value='2018'>2018</option>" .
                            "            <option value='2019'>2019</option>" .
                            "            <option value='2020'>2020</option>" .
                            "            <option value='2021'>2021</option>" .
                            "    </select>" .
                            "    </span>" .
                            "    <br/><br/>";
                        if($readOnly == ""){
                            echo "    Image Upload" .
                            "    <input value='" .$rewardsImage[$i] . "' type='file' size='15'" .
                            "name='rewardImage" . $i . "' id='rewardImage" . $i . "'/><br/>";
                        }
                        if($fileName == null || $fileName == ""){
                            $fileName = q1("SELECT fldImage FROM tblRewards WHERE pkRewardID = \"$rewardID\" AND fkProjectID = \"$id\"");
                        }
                        if($fileName != null){
                            //echo "Current Image: " . $fileName . "<br/>";
                            echo "<img src=\"/magick.php/$fileName\" width=\"100\" />\n";
                        }
                        echo "<br/><input type='hidden' name='rewardpkID" . $i . "' value='" . $rewardID . "'/>";


                        echo "</div>" .
                            "<!-- end reward --></br>";
                    }
                ?>
            </div>
            <?
                if($readOnly == ""){
            ?>
            <div class="reward-details">
                <h3 class="title"><a href="#" id="addlink">Add New Reward</a></h3>
            </div>
<!--
            <div class="reward-details" id="rewards-save" style="display: <?=$rewardsSave?>;" >
            	<br />
                <? echo "<center><button align=\"center\" class=\"button button-blue\" style=\"font-size: 14px;\" onClick=\"getElementById('submitForm').submit();\">Save Reward(s)</button></center>\n";?>
                <br />
            </div>
-->
            <div id="deleteList"></div>
            <?}?>
	</div>
 <!-- end rewards section -->