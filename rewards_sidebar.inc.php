
<script language="javascript" type="text/javascript">
	var rewardNum = 1;
        var rewardsLeft = 7;
        $(document).ready(function() {
   
		$("a#addlink").click(function(){

                    if(rewardNum <= rewardsLeft){

		$("#linkList").append("<!-- begin reward -->" +
                "<div class='reward-details' id='r" + rewardNum + "'>" +
                "    <div style='width:47%;float:right;'><a href='#' onclick='removeReward(\"r" + rewardNum + "\")'>Click to Remove&nbsp;&nbsp;</a></div>" +
                "    Title*<br/>" +
                "    <input type='text' name='rewardTitle" + rewardNum + "' id='rewardTitle" + rewardNum + "' size='23'/><br/><br/>" +
                "    <textarea name='rewardDesc" + rewardNum + "' id='rewardDesc" + rewardNum + "' cols='23' rows='5'>Reward Description*</textarea><br/><br/>" +
                "    <div style='width:47%; float:left;margin-top:4px;'>" +
                "        Support Required*<br/>" +
                "        $<input type='text' name='rewardSupport" + rewardNum + "' id='rewardSupport" + rewardNum + "' size='5'/><br/>" +
                "    </div>" +
                "    <div style='width:47%; float:right;'>" +
                "        Limit Amount<input type='checkbox' id='limitAvail" + rewardNum + "' name='limitAvail" + rewardNum + "' onclick='changeAvailBox(\"limitAvail" + rewardNum + "\", \"numAvailable" + rewardNum + "\")'>" +
                "        <input style='display:none;' type='text' name='numAvailable" + rewardNum + "' id='numAvailable" + rewardNum + "' size='5' maxlength='5'/><br/>" +
                "    </div>" +
                "    <span style='margin-left:7px;'>" +
                "    <select name='rewardMonth" + rewardNum + "' id='rewardMonth" + rewardNum + "'>" +
                "            <option value='' SELECTED>Delivery Month*" +
                "            <option value='01'>January (01)" +
                "            <option value='02'>February (02)" +
                "            <option value='03'>March (03)" +
                "            <option value='04'>APRIL (04)" +
                "            <option value='05'>MAY (05)" +
                "            <option value='06'>JUNE (06)" +
                "            <option value='07'>JULY (07)" +
                "            <option value='08'>AUGUST (08)" +
                "            <option value='09'>SEPTEMBER (09)" +
                "            <option value='10'>OCTOBER (10)" +
                "            <option value='11'>NOVEMBER (11)" +
                "            <option value='12'>DECEMBER (12)" +
                "    </select> /</span>" +
                "    <span style='margin-left:7px;'>" +
                "    <select name='rewardYear" + rewardNum + "' id='rewardYear" + rewardNum + "'>" +
                "            <option value='' SELECTED>Delivery Year*" +
                "            <option value='12'>2012" +
                "            <option value='13'>2013" +
                "            <option value='14'>2014" +
                "            <option value='15'>2015" +
                "            <option value='16'>2016" +
                "            <option value='17'>2017" +
                "            <option value='18'>2018" +
                "            <option value='19'>2019" +
                "            <option value='20'>2020" +
                "            <option value='21'>2021" +
                "    </select>" +
                "    </span>" +
                "    <br/><br/>" +
                "    Image Upload" +
                "    <input type='file' name='rewardImage" + rewardNum + "' id='rewardImage" + rewardNum + "'/><br/>" +
                "</div>" +
                "<!-- end reward -->");
                        rewardNum++;
                    }else{
                        alert("Maximum Rewards reached");
                    }
                });
	 });

    function changeAvailBox(id, id2){
        if(document.getElementById(id).checked){
            document.getElementById(id2).style.display = 'block';
        } else {
            document.getElementById(id2).style.display = 'none';
        }
    }

    function removeReward(id){
        document.getElementById(id).parentNode.removeChild(document.getElementById(id));
        rewardsLeft++;
    }
    /*
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
    */
</script>

 <!-- start rewards section -->
                                 
	<div class="reward-div">
		<div class="reward-details">
			<h3 class="title">Rewards</h3>
                </div>

            <div id="linkList">

                <!-- begin reward -->
                <!--
                <div class='reward-details'>
                    <span style='text-align: center'><p><strong>Reward 1</strong></p></span>
                    Title*<br/>
                    <input type='text' name='rewardTitle1' id='rewardTitle1' size='23'/><br/><br/>
                    <textarea name='rewardDesc1' id='rewardDesc1' cols='23' rows='5'>Reward Description*</textarea><br/><br/>
                    <div style='width:47%; float:left;margin-top:4px;'>
                        Support Required*<br/>
                        $<input type='text' name='rewardSupport1' id='rewardSupport1' size='5'/><br/>
                    </div>
                    <div style='width:47%; float:right;'>
                        Limit Amount<input type='checkbox' id='limitAvail1' name='limitAvail1' onclick='changeAvailBox("limitAvail1", "numAvailable1")'>
                        <input style="display:none;"type='text' name='numAvailable1' id='numAvailable1' size='5' maxlength='5'/><br/>
                    </div>
                    <span style='margin-left:7px;'>
                    <select name='rewardMonth1' id='rewardMonth1'>
                            <option value='' SELECTED>Delivery Month*
                            <option value='01'>January (01)
                            <option value='02'>February (02)
                            <option value='03'>March (03)
                            <option value='04'>APRIL (04)
                            <option value='05'>MAY (05)
                            <option value='06'>JUNE (06)
                            <option value='07'>JULY (07)
                            <option value='08'>AUGUST (08)
                            <option value='09'>SEPTEMBER (09)
                            <option value='10'>OCTOBER (10)
                            <option value='11'>NOVEMBER (11)
                            <option value='12'>DECEMBER (12)
                    </select> /</span>
                    <span style='margin-left:7px;'>
                    <select name='rewardYear1' id='rewardYear1'>
                            <option value='' SELECTED>Delivery Year*
                            <option value='12'>2012
                            <option value='13'>2013
                            <option value='14'>2014
                            <option value='15'>2015
                            <option value='16'>2016
                            <option value='17'>2017
                            <option value='18'>2018
                            <option value='19'>2019
                            <option value='20'>2020
                            <option value='21'>2021
                    </select>
                    </span>
                    <br/><br/>
                    Image Upload
                    <input type='file' name='rewardImage1' id='rewardImage1'/><br/>
                </div>-->
                <!-- end reward -->

            </div>
            <div class="reward-details">
                <h3 class="title"><a href="#" id="addlink">Add New Reward</a></h3>
            </div>
	</div>
 <!-- end rewards section -->