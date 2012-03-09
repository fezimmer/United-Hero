<?php
ob_start(); 
include ("email_lib/includes/config.php");
    include('header.php');
?>
	<div id="contentBox">
		
		<div id="sub-left">
			<h2 style="font-size:16px;">United Hero is going to help individuals!</h2>
			Noah and Matthew have built an Ark, it's a very very large boat with room for every man, woman and child.  Secure your reservations by signing up for a Free Email account.
            <br /><br />
          
          <img src="images/Lily-Style-Anderson.jpg" style="float:left; padding: 0px 20px 10px 0px;" />
          
We fell in Love with Lily an 8 year old beautiful girl.
She is in 3rd grade, and her favorite color is green.
All her friends call her "Style" Anderson. She thinks
peace signs are really cool!
<br /><br />
<div style="clear:both;">
Lily went to the doctors for a stomach ache, the diagnoses was much worse.
<br />
<strong>Find out more at: </strong> 
<br />
<a href="http://www.prayforlilyanderson.com/">http://www.prayforlilyanderson.com/</a><br />
<a href="http://www.active.com/donate/lilyanderson">http://www.active.com/donate/lilyanderson</a><br />
<a href="http://www.facebook.com/search/?q=lily+anderson&init=quick#/group.php?gid=170322356216&ref=ts">On Facebook</a>
</div>				
			
		</div>
		
		<div id="sub-right">
			
			<img src="images/nandm.jpg" alt="Matthew and Noah" />
			
			<div class="quote">
				<em>How do you want to be remembered?</em> 
				
			</div>
			<div class="quote">
				DOUBLE POINT TO THE HEAVENS
			</div>
		</div>
	</div>
	
	<div id="email-container">
		
		<?php
		include('signup_form.php');
		?>
	</div>
	
	

<?php
    include('footer.php');
?>