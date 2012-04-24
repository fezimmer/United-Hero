<?
        //count Number of rewards
        $finalCount = rewardCount($rewardsTitle, $rewardsDesc, $rewardsSupport, $rewardsImage, $rewardsDelete);
        if($finalCount == 1){
            $finalCount += q1("SELECT COUNT(*) FROM tblRewards WHERE fkProjectID = \"$id\"");
        }
?>

<script language="javascript" type="text/javascript">
    var rewardNum = <?=$finalCount?>;

    $(document).ready(function() {
        $("a#addlink").click(function(){
            if(rewardNum > 7){
                alert("Maximum Rewards reached");
            }else{
                $("#linkList").append("<!-- begin reward --><div class='reward-details' id='r" + rewardNum + "'>" + "<div style='width:47%;float:right;cursor:pointer;cursor:hand;'><a id='removal" + rewardNum + "' removalhref='#' onclick=\"removeReward('r" + rewardNum + "')\">Click to Remove&nbsp;&nbsp;</a></div>Title*<br/><" + "input type='text' name='rewardTitle" + rewardNum + "' id='rewardTitle" + rewardNum + "' size='23'/><br/><br/>Description*<br/><" + "textarea name='rewardDesc" + rewardNum + "' id='rewardDesc" + rewardNum + "' cols='23' rows='5'></textarea><br/><br/><div style='width:55%; float:left;margin-top:4px;'>" + "Purchase Amount*<br/>" + "$<" + "input type='text' name='rewardSupport" + rewardNum + "' id='rewardSupport" + rewardNum + "' size='5'/><br/></div><div style='width:45%; float:right;'>Limit Amount<" + "input type='checkbox' id='limitAvail" + rewardNum + "' name='limitAvail" + rewardNum + "' onclick='changeAvailBox(\"limitAvail" + rewardNum + "\", \"numAvailable" + rewardNum + "\")'><" + "input style='display:none;' type='text' name='numAvailable" + rewardNum + "' id='numAvailable" + rewardNum + "' size='5' maxlength='5'/><br/></div><" + "span style='margin-left:7px;'><" + "select name='rewardMonth" + rewardNum + "' id='rewardMonth" + rewardNum + "'><option value='' SELECTED>Delivery Month*</option><" + "option value='01'>JANUARY (01)</option><option value='02'>FEBRUARY (02)</option><" + "option value='03'>March (03)</option><option value='04'>APRIL (04)</option><" + "option value='05'>MAY (05)</option><option value='06'>JUNE (06)</option><" + "option value='07'>JULY (07)</option><option value='08'>AUGUST (08)</option><" + "option value='09'>SEPTEMBER (09)</option><option value='10'>OCTOBER (10)</option><" + "option value='11'>NOVEMBER (11)</option><option value='12'>DECEMBER (12)</option></select>/" + "</span><" + "span style='margin-left:7px;'><" + "select name='rewardYear" + rewardNum + "' id='rewardYear" + rewardNum + "'><option value='' SELECTED>Delivery Year*</option><option value='2012'>2012</option><" + "option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><" + "option value='2016'>2016</option><option value='2017'>2017</option><option value='2018'>2018</option><" + "option value='2019'>2019</option><option value='2020'>2020</option><option value='2021'>2021</option></select><" + "/span><br/><br/>Image Upload<" + "input type='file' size='15' name='rewardImage" + rewardNum + "' id='rewardImage" + rewardNum + "'/><br/></div><!-- end reward -->");
                if(rewardNum > 1){
                    document.getElementById('removal'+(rewardNum-1)).style.display = 'none';
                }
                rewardNum++;
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
        $("#deleteList").append("<input type='hidden' name='deleteReward" + (rewardNum-1) + "' value='" + deleteID + "'/>");
        document.getElementById(id).parentNode.removeChild(document.getElementById(id));
        rewardNum--;
        document.getElementById('removal'+(rewardNum-1)).style.display = 'block';
    }
</script>

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
                $readOnly = "";
                if($readOnlyKeyword){
                    $readOnly = "readonly=\"readonly\"";
                    $disabled = "disabled=\"disabled\"";
                }
                    $fCount = rewardCount($rewardsTitle, $rewardsDesc, $rewardsSupport, $rewardsImage, $rewardsDelete);
                    $count = 0;
                    $identifier = "";
                    if($id == null) $identifier = $projectID;
                    else $identifier = $id;

                    if($fCount == 1){
                        $fCount += q1("SELECT COUNT(*) FROM tblRewards WHERE fkProjectID = \"$identifier\" AND fkPaymentID = \"\"");
                    }
                    $reward = q("SELECT * FROM tblRewards WHERE fkProjectID = \"$identifier\" AND fkPaymentID = \"\" ORDER BY pkRewardID");

                    for($i=1;$i<$fCount;$i++){
                        //attempt to pull reward info from db
                        $rwd = $reward[$count];
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

                        echo "<!-- begin reward -->" .
                            "<div class='reward-details' id='r" . $i . "'>" .
                            "    <div style='width:47%;float:right;cursor:pointer;cursor:hand;'><a ";
                        if(($finalCount - $i) == 1 && $submitPage != "my_account.php"){
                            echo "style='display:block;'";
                        }else{
                            echo "style='display:none;'";
                        }
                        echo " id='removal".$i."' href='#' onclick=\"removeReward('r" . $i . "', '" . $rewardID . "')\">Click to Remove&nbsp;&nbsp;</a>" .
                            "</div>    Title*<br/>" .
                            "    <input value='" .$rewardsTitle[$i] . "' type='text'";
                        echo $readOnly;
                            echo "name='rewardTitle" . $i . "' id='rewardTitle" . $i . "' size='23'/><br/><br/>" .
                            "    Description*<br/><textarea name='rewardDesc" . $i . "'";
                            echo $readOnly;
                            echo "id='rewardDesc" . $i . "' cols='23' rows='5'>";
                        if($rewardsDesc[$i] != '' && $rewardsDesc[$i] != null)echo $rewardsDesc[$i];
                        echo "</textarea><br/><br/>" .
                            "    <div style='width:55%; float:left;margin-top:4px;'>" .
                            "        Purchase Amount*<br/>" .
                            "        $<input value='" .$rewardsSupport[$i] . "' type='text'";
                            echo $readOnly;
                            echo "name='rewardSupport" . $i . "' id='rewardSupport" . $i . "' size='5'/><br/>" .
                            "    </div>" .
                            "    <div style='width:45%; float:right;'>" .
                            "        Limit Amount<input type='checkbox' ";
                        if($rewardsAvail[$i] == 0){
                            $rewardsAvail = "";
                        }
                        if($rewardsAvail[$i] != "" && $rewardsAvail[$i] != null){
                            echo " checked='yes' ";
                        }
                        echo $readOnly;
                        echo "id='limitAvail" . $i . "' name='limitAvail" . $i . "' onclick='changeAvailBox(\"limitAvail" . $i . "\", \"numAvailable" . $i . "\")'>" .
                            "        <input value='" .$rewardsAvail[$i] . "' style='display:";
                        if($rewardsAvail[$i] != "" && $rewardsAvail[$i] != null){
                            echo " block;";
                        }else{
                            echo " none;";
                        }
                        echo "' type='text' name='numAvailable" . $i . "'";
                        echo $readOnly;
                        echo "id='numAvailable" . $i . "' size='5' maxlength='5'/><br/>" .
                            "    </div>" .
                            "    <span style='margin-left:7px;'>" .
                            "    <select value='" .$rewardsMonth[$i] . "' name='rewardMonth" . $i . "'";
                        echo $disabled;
                        echo "id='rewardMonth" . $i . "'>" .
                            "            <option value='".$rewardsMonth[$i]."' SELECTED>";
                        if($rewardsMonth[$i] != "" && $rewardsMonth[$i] != null) echo getRewardMonth($rewardsMonth[$i]);
                        else echo "Delivery Month*";
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
                            "    </select>/</span>" .
                            "    <span style='margin-left:7px;'>" .
                            "    <select value='" .$rewardsYear[$i] . "' name='rewardYear" . $i . "'";
                        echo $disabled;
                        echo "id='rewardYear" . $i . "'>" .
                            "            <option value='".$rewardsYear[$i]."' SELECTED>";
                        if($rewardsYear[$i] != "" && $rewardsYear[$i] != null) echo $rewardsYear[$i];
                        else echo "Delivery Year*";
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
                        if($fileName == null){
                            $fileName = q1("SELECT fldImage FROM tblRewards WHERE fldTitle = \"$rewardsTitle[$i]\" AND fkProjectID = \"$id\"");
                        }
                        if($fileName != null){
                            echo "Current Image: " . $fileName . "<br/>";
                        }
                        echo $rewardID . " and " . $i;
                        echo "<input type='hidden' name='rewardpkID" . $i . "' value='" . $rewardID . "'/>";
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
            <div id="deleteList"></div>
            <?}?>
	</div>
 <!-- end rewards section -->