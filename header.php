<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
	<title><? if ($pageTitle) echo "$pageTitle - ";?>United Hero</title>

	<meta property="og:title" content="<?=$pageTitle?>" />
	<meta property="og:description" content="<?=$metaDesc?>" />
	<meta property="og:image" content="<? if ($metaImage) echo $metaImage; else echo "http://unitedhero.com/images/logo-sm.jpeg";?>" />

	<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/rewards.css" />
	<link href="css/basic.css" type="text/css" rel="stylesheet" />
	<link href="css/forms.css" rel="stylesheet" type="text/css" />
	<link href="css/modal.css" type="text/css" rel="stylesheet" />

	<link rel="image_src" type="image/jpeg" href="<? if ($metaImage) echo $metaImage; else echo "http://unitedhero.com/images/logo-sm.jpeg";?>" />
	<script src="js/jquery.1.5.min.js" type="text/javascript"> </script>
	<script src="js/modal.js" type="text/javascript"> </script>
	<script src="js/tipsy.js" type="text/javascript"> </script>
	<script src="js/globalFunctions.js" type="text/javascript"> </script>

	<style>
		a.flash:hover{
			color: #62C9DF;
		}
	</style>
</head>
<body id="home">
<div class="wrapperOuter">
  <div class="wrapperMain">
	 <div class="main">
		<!--Start Header-->
		<div class="headerSec">
		  <div class="logoSec"> <a href="/index.php"><img src="images/uh-logo_2.png" border="0" alt="united hero" /></a> </div>

          <div id ="header_blurb">
          We are a Global Platform
			<br/>for Funding Peoples’ Dreams. <a href="#?w=780" rel="learnMorePopup" class="poplight flash" title="Learn More About Hero Account">Learn More!</a>
            </div>

		  <div class="headerCTA">
		  	<?	if(!is_logged_in()){?>
		  			<a href="#?w=400" rel="loginForm" class="login poplight" onClick="document.getElementById('login_email').focus();" title="Login to Your Account">Login</a>
		  			<a href="#?w=780" rel="signupForm" class="signup poplight" title="United Hero Account Creation">Signup</a>
		  	<? 	}
		  		else{?>
		  			<a href="/login.php?action=logout" rel="loginForm" class="login" title="Logout">Log Out</a>
		  			<a href="my_account.php" class="signup" title="My Account">My Account</a>
		  			<br /><div style="font-size:10px; color: #ffffff; text-align:right;">Welcome, <?=$_SESSION['user_name'];?></div>
		  	<?	}?>
		  </div>
		</div>
		<div class="searchSec">
		  <div class="navi">
			 <ul>
				<li><a href="/index.php">Home</a></li>
				<li><a href="/browse_projects.php?pageNum=1">Projects</a></li>
				<li><a href="/mission.php">Mission</a></li>
				<li><a href="faq.php">FAQ</a></li>
				<li><a href="contact.php">Contact</a></li>
			 </ul>
		  </div>
		  <div class="searchPanl">
			 <form action="search_result.php" id="searchform" method="get" role="search" onSubmit="return searchValidation(s);">
				<div class="searchPanl_main">
				  <div class="searchBox_sec">
                                      <input type="hidden" name="pageNum" value="1">
                                      <input id="s" name="s" value="Search site..." class="searchBox" onFocus="javascript:if(this.value=='Search site...') this.value ='';" onBlur="javascript:if(this.value=='')this.value='Search site...';" type="text">
				  </div>
				  <div class="searchIcon_sec">
					 <input id="searchsubmit" value="" class="searchIcon" type="submit">
				  </div>
				</div>
			 </form>
		  </div>
		</div>
		<!--End Header-->
		<!--Start Main Container-->
		<div class="mainContainer">
