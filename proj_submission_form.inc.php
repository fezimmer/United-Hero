<?
	//$editing = $_GET['editing'];
	$buttonValue = "Submit";
	$action = "submitProject";
	$what = "submit";

	//reward readonly bool
	$readOnlyKeyword = false;

	if ($editing){
		$buttonValue = "Update Product";
		$action = "updateProject";
		$what = "update";
	}
	$submitButtonHTML = "<button align=\"center\" class=\"button button-blue\" style=\"font-size: 20px;\" onClick=\"validateAndSubmit('submitForm')\">$buttonValue</button>";

	$fldPhoneNumber = q1("SELECT fldPhone FROM tblUser WHERE pkUserID = $userID");
?>

	<script language="javascript" type="text/javascript">
		var fileName = "";
		$(function() {
			$("input:file").change(function (){
				fileName = $(this).val();
			});
		});
		function limitText(limitField, limitCount, limitNum) {
			if (limitField.value.length > limitNum) {
				limitField.value = limitField.value.substring(0, limitNum);
			} else {
				limitCount.value = limitNum - limitField.value.length;
			}
		}

        function validateAndSubmit(form){
            //alert("validating");
            if(fileName == ""){
                document.getElementById("fldImageError").style.display = "block";
            }else{
                document.getElementById("fldImageError").style.display = "none";
                document.getElementById("submitForm").target="";
                document.getElementById("fileframe").value = "false";
                form.submit();
            }
            return;
        }
	</script>

			 <div class="">


				 <!--p>First Name</p>
				 <input name="first name" type="text" size="35" maxlength="35" readonly value="<?=$userFirstName?>" />
				 <p>Last Name</p>
				 <input name="last name" type="text" size="35" maxlength="35" readonly value="<?=$userLastName?>" />-->

				<p><b>PRODUCT TITLE</b></p>
				 <input name="fldTitle" type="text" size="50" maxlength="75" value="<?=$fldTitle?>"/>
				 <!--p><b>Phone Number</b></p>
				 <input name="fldPhoneNumber" type="text" size="15" maxlength="18" value="<?=$fldPhoneNumber?>"/-->

				<p><b>PRODUCT LOCATION</b></p>
				 <input name="fldLocation" type="text" size="30" value="<?=$fldLocation?>">

				<p><b>DESIRED GOAL AMOUNT</b> (<span style="color:red;">Ask for the Dollar $ amount of Products you want to sell in 60 days. Review Product Guidelines below</span>)</p>
				 $<input name="fldDesiredFundingAmount" type="text" size="10" maxlength="15" value="<?=$fldDesiredFundingAmount?>" />
				 <br/> <br/>

				<!--p> Now that you've told us a little about your video, lets help you post it to our site.
				 We currently support <a href="http://www.vimeo.com" target="_blank">vimeo</a> and <a href="http://www.youtube.com" target="_blank">youtube</a> video for embeding. please check with the <a href="http://www.vimeo.com" target="_blank">vimeo</a> or <a href="http://www.youtube.com" target="_blank">youtube</a>  link if you have any questions.
				 Otherwise just paste the copied video embed tag in to the field below:</p-->
				 <p><b>VIDEO URL CODE</b> Upload your video to  <a href="http://www.vimeo.com" target="_blank">Vimeo</a> or <a href="http://www.youtube.com" target="_new">YouTube</a> and share your video link here.</p>
				<textarea name="fldVideoHTML" cols="75" rows="4"><?=$fldVideoHTML?></textarea>
				<br/> <br/>

				<p><b>IMAGE UPLOAD</b> Upload an image of your Product</p>
                                <div id="fldImageError" style="display:none; color:red;">Image is required</div>
				<input name="fldImage" id="fldImage" type="file">
				<?if($fldImage != ""){?><span style="margin-left:15px;">Currently: <img src="/magick.php/<?=$fldImage?>" alt="PROJECT IMAGE" height="35"/></span><?}?>
				<br/><br/>

				<p><b>ADD TAGS</b> (Search words that will help find your video, separated by a comma)</p>
				<input name="fldTags" type="text" size="85" maxlength="75" value="<?=$fldTags?>" />

				<p><b>PRODUCT DESCRIPTION,</b> Tell about your Product and about Yourself to help support your video, also if you have a Website link, FaceBook Page or Twitter Link, Put it here!</p>
				<textarea name="fldDescription" type="text"  cols="75" rows="8" onKeyDown="limitText(this.form.fldDescription,this.form.countdown,1100);" onKeyUp="limitText(this.form.fldDescription,this.form.countdown,1100)"  /><?=$fldDescription?></textarea>
				<br />
				You have <input readonly type="text" name="countdown" size="3" value="1100"> characters left.</font>
				</p>
				<br /><br />

				<center>
					<? echo $submitButtonHTML;?>
				</center>
				<div style="clear: both;"></div><br />

				<fieldset><?include ('guidelines.inc.php');?></fieldset>

			<!--p>If you have read, and agree to our <a href="terms.php" target="_blank">terms of service</a>, feel free to submit your video for review. Thanks!</p>

			<p><br />

			<label>I agree to the terms
				<input type="checkbox" name="fldTerms" id="fldTerms" value="1" />
			</label>
			</p-->

				<input type="hidden" name="id" value="<?=$id?>" />
				<input type="hidden" name="action" value="<?=$action?>" />
			</div>
			 <p><br/>
			   <br/>
			   <br/>
			   <br/>
			   <!---->
			   </p>
		  <p class=""></p>
