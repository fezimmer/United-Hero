<?php
ob_start(); 
include ("email_lib/includes/config.php");
    include('header2.php');
?>
	<div id="googlebar">
		<style type="text/css">
		@import url(http://www.google.com/cse/api/branding.css);
		</style>
		<div style="position:relative;">
		 <form action="http://www.unitedhero.com/results.html" id="cse-search-box">
     
		        <input type="hidden" name="cx" value="partner-pub-5659244258872257:e2agea-4atc" />
		        <input type="hidden" name="cof" value="FORID:9" />
		        <input type="hidden" name="ie" value="ISO-8859-1" />
		       <input type="text" name="q" size="62" class="main-search-box"  align="absmiddle" /> 
		        <input type="submit" name="sa" value="Search" class="search_btn" align="absmiddle" />
     
   		 </form>
		 </div>
		 <div id="googlebar-intro">
		 Time is near. This is the New Way to Donate, your searching and purchases will make a difference. &nbsp;&nbsp;<a href="about.php"><img src="images/findout_btn.png" alt="Find Out More!" class="more-btn" /></a>
		</div>
	</div>
	<div id="imageBox">
		
		<script type="text/javascript">
			random_vidlink();
			
		</script>
		
	</div>
	
	<div id="email-container">
		
		<?php
		include('signup_form.php');
		?>
		
	</div>
	
	

<?php
    include('footer.php');
?>