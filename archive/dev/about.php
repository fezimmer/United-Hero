<?php
ob_start(); 
include ("email_lib/includes/config.php");
    include('header.php');
?>
	<div id="contentBox">
		
		<div id="sub-left">
			<h2>
            UNITED HERO IS A SEARCH ENGINE TO HELP INDIVIDUALS
            </h2>
			<em>Let's Cure Breast Cancer, Heart Disease, Help our Veterans ,
			and Feed every Hungry American child!</em>
			<ul>
				<li>
					<h3>Q: How are we going to do this?</h3>
					A: Google has 70% of the USA search market and made 22 billion dollars last year. Google is sharing revenue with United Hero!!!
				</li>
				<li>
					<h3>Q: How does this work?</h3>
					A: Go to UnitedHero.com and do all of your normal searching. The search engine is powered by Google so there won't be anything that you are not used to. 
					With your normal searching you will be making a difference for Americans!
				</li>
				<li>
					<h3>Q: How much do you give to Americans?</h3>
					A: United Hero gives 50% of the proceeds to Americans! The other 50% goes to create new products to help (more) Americans.
				</li>
			</ul>
		</div>
		
		<div id="sub-right" style="position:relative;">
			
			<img src="images/nandm2.jpg" alt="Matthew and Noah" />
			
			<div class="quote">
				<em>"We need to help Lily Anderson today!"</em> 
				<br /> <strong>-  Matthew Downing</strong>
			</div>
			<div class="quote">
				<em>"Let's show Lily the Power We have together!"</em> 
				<br /><strong>- Noah Embree</strong>
                
                  <a href="images/product-img1.jpg" rel="group" class="product-info"><img src="images/bottle-water.jpg" class="water" /></a>
                	<a href="images/product-img2.jpg" rel="group" class="product-info"></a>
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