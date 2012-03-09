<a href="../unitedmail/"><img src="images/goto-login-btn.png" alt="login to email account" /></a>
		<strong>Dont have an email account?</strong>
		
		<h2>Sign-up Now</h2>
		<p>The 1st email where you can make a real difference!</p>
		
		<div id="form-container">
		<form name="emailform" method="POST" action="email_lib/add_email.php">
        
        <div style="width:120px; float:left;">
			<h3>First name:</h3>
			<input type="text" name="firstname" id="firstname" class="signup-input" style="width:100px;" /> 
        </div>
        
        <div style="width:120px; float:left;">
			<h3>Last name:</h3>
			<input type="text" name="lastname" id="lastname" class="signup-input" style="width:100px;" /> 
        </div>
        
			<h3>Desired email address:</h3>
			<input maxlength="20" value="" name="email" id="email" class="email-input" type="text"> <? echo '@'.$domain_name; ?>
			<h3>Password(6 char min):</h3>
			<input size=20 name="password" id="password" value="" type="password" class="signup-input">
			<h3>Retype Password:</h3>
			<input size=20 name="confirm" id="confirm" value="" type="password" class="signup-input">
			<h3></h3>
            Alternative email
            <input type="text" name="alt_email" id="alt_email" class="signup-input">
            <h3></h3>
           <!-- Age <input type="text" name="age" id="age" class="signup-input" style="width:25px;" />--> Location: <input type="text" name="location" id="location" class="signup-input" style="width:126px;" />
            <h3></h3>
			<!--<input name="submit" type="submit" id="submit" value="Signup"> -->
			<a href="#" class="submit-signup">  <img src="images/submit_signup.gif" alt="Submit Sign-Up" /> </a>
			
		</form>
		</div>