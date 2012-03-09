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
				<?if($fldImage != ""){?><span style="margin-left:15px;">Currently: <img src="project_images/<?=$fldImage?>" alt="PROJECT IMAGE" height="35"/></span><?}?>
				<br/><br/>

				<p><b>Add Tags</b> (words that will support your video, separated by a comma)</p>
				<input name="fldTags" type="text" size="85" maxlength="75" value="<?=$fldTags?>" />

				 <p><b>Project Description</b> What will you do with the Money? 900 characters or less, to support your video. Have a website link or Blog put it here.</p>
				 <textarea name="fldDescription" type="text"  cols="75" rows="8"   /><?=$fldDescription?></textarea>
				 </p>

<?			if (!$editing){?>
				<fieldset>

						<h2>Dream/Project Guidelines</h2>

						<p>	1.&nbsp;&nbsp;<strong>Show your passion with an inspiring video of not more than 3 minutes.</strong><br />
							&nbsp;&nbsp; &nbsp; &nbsp;This video should explain the who, why, what and where.  All this means is you have a specific thing you will do with the money you are supported with. &nbsp;<br /></p>
						<p>
							2.&nbsp;&nbsp;<strong>Where can your Dream Supporters find out more about your dream?</strong></p>
						<p>
							&nbsp;&nbsp; &nbsp; &nbsp;It will be beneficial if you have a web site, Facebook page (<a href="http://www.facebook.com/pages/create.php">http://www.facebook.com/pages/create.php</a>), <a href="http://www.twitter.com">Twitter</a>, or any other sources. We recommend A Facebook Fan page!  WHY?  'There are 750 Million People on Facebook.'  If they just gave you their 2 cents it would be Millions of dollars towards your dream, also Facebook Fan page offers advertisement to bring more supporters to your Dream.</p>
						<p>
							<br />
							&nbsp;3.&nbsp;&nbsp;<strong>How much money would you like to raise?</strong></p>
						<p>
							&nbsp;&nbsp; &nbsp; &nbsp; You have a limited amount of time to receive your goal amount. If you get support up and over your goal amount you get that money too. If you do not reach your goal  by the time limit  no money exchanges.  Only when a dream is fully funded United Hero charges a 7% fee to the amount supported to maintain this platform and keep this dream catcher up and online.<br />
							&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</p>
						<p>
							&nbsp;4.&nbsp;&nbsp;<strong>As a Project Creator you are ultimately responsible to direct supporters to United Hero&#39;s Funding Platform.</strong></p>
						<p>
							&nbsp;&nbsp; &nbsp; &nbsp; Start sharing the news with your friends and family on Facebook and Twitter, they are your strongest support.</p>
						<p>
							&nbsp;5.&nbsp;&nbsp;<strong>Please read our <a href="/terms.php">Terms and Conditions</a> and <a href="/privacy_policy.php">Privacy Policy</a>.</strong></p>
						<p>
							<strong>&nbsp;6. &nbsp;</strong><strong>We reserve the right to refuse any Dreams, Projects and/or Ideas deemed to have inappropriate content.</strong></p>
						<p>
							<strong>&nbsp;7.&nbsp;</strong>&nbsp;&nbsp;<strong>We&#39;re proud to provide this service for you, Happy to help you with the process, </strong><strong>and thrilled that you&#39;re using UNITED HERO!&nbsp; </strong><strong>&#39;Can&#39;t wait to see your Project.&#39;</strong></p>
						<p>
							<strong>Thank You,</strong> UNITED HERO</p>
						</p>

					<? echo $submitButtonHTML;?>



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
