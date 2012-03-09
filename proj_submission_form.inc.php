<?
	//$editing = $_GET['editing'];
	$buttonValue = "Submit";
	$action = "submitProject";
	$what = "submit";

	if ($editing){
		$buttonValue = "Update Project";
		$action = "updateProject";
		$what = "update";
	}
	$submitButtonHTML = "<p><button class=\"button button-blue btn-close\" style=\"font-size: 14px;\" onClick=\"getElementById('submitForm').submit();\">$buttonValue</button></p>";

	$fldPhoneNumber = q1("SELECT fldPhone FROM tblUser WHERE pkUserID = $userID");
?>

	<script language="javascript" type="text/javascript">
	function limitText(limitField, limitCount, limitNum) {
		if (limitField.value.length > limitNum) {
			limitField.value = limitField.value.substring(0, limitNum);
		} else {
			limitCount.value = limitNum - limitField.value.length;
		}
	}
	</script>

			<div id="create-project">
				<h3>Project Submission</h3>
				<p> Below you may <?=$what?> your project:</p>
			</div>

			 <div class="">

			 <form id="submitForm" name="submitForm" method="post" action="<?=$submitPage?>" enctype="multipart/form-data">
				 <!--p>First Name</p>
				 <input name="first name" type="text" size="35" maxlength="35" readonly value="<?=$userFirstName?>" />
				 <p>Last Name</p>
				 <input name="last name" type="text" size="35" maxlength="35" readonly value="<?=$userLastName?>" />-->

				<p><b>Project Title</b></p>
				 <input name="fldTitle" type="text" size="50" maxlength="75" value="<?=$fldTitle?>"/>
				 <!--p><b>Phone Number</b></p>
				 <input name="fldPhoneNumber" type="text" size="15" maxlength="18" value="<?=$fldPhoneNumber?>"/-->

				<p><b>Project Location</b></p>
				 <input name="fldLocation" type="text" size="30" value="<?=$fldLocation?>">

				<p><b>Desired Funding Amount</b> (Ask for the minimum amount to complete your project)</p>
				 $<input name="fldDesiredFundingAmount" type="text" size="10" maxlength="15" value="<?=$fldDesiredFundingAmount?>" />
				 <br/> <br/>

				<!--p> Now that you've told us a little about your video, lets help you post it to our site.
				 We currently support <a href="http://www.vimeo.com" target="_blank">vimeo</a> and <a href="http://www.youtube.com" target="_blank">youtube</a> video for embeding. please check with the <a href="http://www.vimeo.com" target="_blank">vimeo</a> or <a href="http://www.youtube.com" target="_blank">youtube</a>  link if you have any questions.
				 Otherwise just paste the copied video embed tag in to the field below:</p-->
				 <p><b>Video Embed Code</b> Upload your video to  <a href="http://www.vimeo.com" target="_blank">Vimeo</a> or <a href="http://www.youtube.com" target="_new">YouTube</a> and share your video link here.</p>
				<textarea name="fldVideoHTML" cols="75" rows="4"><?=$fldVideoHTML?></textarea>
				<br/> <br/>

				<p><b>Image Upload</b> Upload an image to your project</p>
				<input name="fldImage" type="file">
				<?if($fldImage != ""){?><span style="margin-left:15px;">Currently: <img src="/magick.php/<?=$fldImage?>" alt="PROJECT IMAGE" height="35"/></span><?}?>
				<br/><br/>

				<p><b>Add Tags</b> (words that will support your video, separated by a comma)</p>
				<input name="fldTags" type="text" size="85" maxlength="75" value="<?=$fldTags?>" />

				 <p><b>Project Description</b> What will you do with the Money? 1100 characters or less, to support your video. Have a website link or Blog put it here.</p>
				 <textarea name="fldDescription" type="text"  cols="75" rows="8" onKeyDown="limitText(this.form.fldDescription,this.form.countdown,1100);" onKeyUp="limitText(this.form.fldDescription,this.form.countdown,1100)"  /><?=$fldDescription?></textarea>
				 <br />
				 You have <input readonly type="text" name="countdown" size="3" value="1100"> characters left.</font>
				 </p>
				 <br /><br />

<?			if (!$editing){?>
				<fieldset>
					<?
						include ('guidelines.inc.php');

						echo $submitButtonHTML;
					?>
				</fieldset>

			<!--p>If you have read, and agree to our <a href="terms.php" target="_blank">terms of service</a>, feel free to submit your video for review. Thanks!</p>

			<p><br />

			<label>I agree to the terms
				<input type="checkbox" name="fldTerms" id="fldTerms" value="1" />
			</label>
			</p-->

<?			}
			else{
				echo $submitButtonHTML;
			}
?>


				<input type="hidden" name="id" value="<?=$id?>" />
				<input type="hidden" name="action" value="<?=$action?>" />
			 </form>
			</div>
			 <p><br/>
			   <br/>
			   <br/>
			   <br/>
			   <!---->
			   </p>
		  <p class=""></p>
