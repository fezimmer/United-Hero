<?
	require_once("preheader.php");
	include("header.php");

	$action = $_POST['action'];

	if ($action == "submitPayment"){
		include"lphp.php";
		$mylphp=new lphp;

		# constants
		$myorder["host"]       = "secure.linkpt.net";
		$myorder["port"]       = "1129";
		$myorder["keyfile"]    = "1001281559.pem"; # Change this to the name and location of your certificate file
		$myorder["configfile"] = "1001281559";        # Change this to your store number

		# form data
		$myorder["cardnumber"]    = $_POST["cardnumber"];
		$myorder["cardexpmonth"]  = $_POST["cardexpmonth"];
		$myorder["cardexpyear"]   = $_POST["cardexpyear"];
		$myorder["chargetotal"]   = $_POST["chargetotal"];
		$myorder["ordertype"]     = $_POST["ordertype"];

		if ($_POST["debugging"]){
			$myorder["debugging"]="true";
		}

	  # Send transaction. Use one of two possible methods  #
		//$result = $mylphp->process($myorder);       # use shared library model
		$result = $mylphp->curl_process($myorder);  # use curl methods

		if ($result["r_approved"] != "APPROVED")    // transaction failed, print the reason
		{
			//print "Status:  $result[r_approved]<br>\n";
			//print "Error:  $result[r_error]<br><br>\n";
			$error_msg[] = "Your credit card did not process. Status: " . $result[r_approved] . " | Error Code: " . $result[r_error];
		}
		else	// success
		{
			$code = $result[r_code];
			//print "Status: $result[r_approved]<br>\n";
			//print "Transaction Code: $result[r_code]<br><br>\n";
			$report_msg[] = "Your credit card was successfully processed ($code).";
		}

	# if verbose output has been checked,
	# print complete server response to a table
		if ($_POST["verbose"])
		{
			echo "<table border=1>";

			while (list($key, $value) = each($result))
			{
				# print the returned hash
				echo "<tr>";
				echo "<td>" . htmlspecialchars($key) . "</td>";
				echo "<td><b>" . htmlspecialchars($value) . "</b></td>";
				echo "</tr>";
			}

			echo "</TABLE><br>\n";
		}

	}//action = submitPayment


?>
		  <div class="featuredBox details inner-col">
			 <iframe class="video" src="http://player.vimeo.com/video/4121765" width="542" height="357"></iframe>

			 <h3 class="title">Long project title will go here on this line</h3>
				<div class="location">Seattle, WA</div>
			<div class="submitted-by">
				<em>Submitted by:</em> <a href="#">Noah Greer</a>
		</div>
        <!--<h3 class="title">Details</h3>-->
					<p>
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
						dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
						ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
						eu fugiat nulla pariatur.
					</p>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
							dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
							ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
							eu fugiat nulla pariatur.
						</p>
                        <div class="tag-container">
						<a href="#">A Tag</a> <a href="#">More Tag</a>
						<a href="#">A Tag</a> <a href="#">Another Tag</a>
						<a href="#">A Tag</a> <a href="#">test Tag</a>
						<a href="#">A Tag</a> <a href="#">Tag</a>
					</div>
		  </div>
			<div class="project-details inner-col">

				<div class="more-details">
					<h3 class="title">Donation Processing</h3>
					<? echo_msg_box();?>
					<p>

					</p>
					<p><form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="paymentForm">
						<input type="hidden" name="ordertype" value="SALE">
						<input type="hidden" name="action" value="submitPayment">

                         Financial Support&nbsp;&nbsp;<br/>
                        $<input name="chargetotal" type="text" />&nbsp;&nbsp; minimum $5

                        <br/><br/>
                        <select name="cardtype" >
							<option value="" SELECTED>--credit card type--
							<option value="01">Visa
							<option value="02">masterCard
							<option value="03">Discover
							<option value="04">AMEX

						</select><br/><br/>
						credit card number<br/>
						<input name="cardnumber" type="text" /><br/>
						name on credit card<br/>
						 <input name="cardholdername" type="text" /> <br/>
						 security code<br/>
						 <input name="secuirty" type="text" />   <br/>      <br/>

						 <select name="cardexpmonth" >
							<option value="" SELECTED>--Exipration Month--
							<option value="01">January (01)
							<option value="02">February (02)
							<option value="03">March (03)
							<option value="04">APRIL (04)
							<option value="05">MAY (05)
							<option value="06">JUNE (06)
							<option value="07">JULY (07)
							<option value="08">AUGUST (08)
							<option value="09">SEPTEMBER (09)
							<option value="10">OCTOBER (10)
							<option value="11">NOVEMBER (11)
							<option value="12">DECEMBER (12)
						</select> /
						<select name="cardexpyear">
							<option value="" SELECTED>--Expiration Year--
							<option value="10">2010
							<option value="11">2011
							<option value="12">2012
							<option value="13">2013
							<option value="14">2014
							<option value="15">2015
							<option value="16">2016
							<option value="17">2017
							<option value="18">2018
						</select>

					    <br/>      <br/>
						<div class="action-buttons">
							<button class="button button-gray btn-close" onClick="document.getElementById('paymentForm').submit();">Submit payment</button>
						</div>

                        </form>







		</p>
				</div>

			</div>
		  <div>
			 <div style="clear: both;"></div>
			<div class="similar">Similar Projects
				<a href="/browse_projects.php" class="browse-more">Browse other Projects</a>
			</div>
			 <div class="boxLine_first_new">
				<div class="blogTitle_box blogTitle_box_new" id="postsDiv">
				  <ul>
					 <li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/01.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/02.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/03.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/04.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/05.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/06.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/07.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
						  <h4>Project Title</h4>
						  <p><strong>project tags, tag </strong></p>
						</div>
						</a> </li>
						<li> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
						  <h4>Project Title</h4>
						  <p><strong>project tags, tag </strong></p>
						</div>
						</a> </li>
				  </ul>
				</div>
			 </div>
			<div class="boxLine_featured blogTitle_box">
				 <ul>
					 <li class="first"> <a href="#">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/01.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a>
							<div class="desc">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
								labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
								laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
								voluptate velit esse cillum dolore eu fugiat nulla pariatur.
							</div>
							<div class="desc">
								Ut enim ad minim veniam, quis nostrud exercitation ullamco
								laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.
							</div>
						</li>
					</ul>
			</div>
			 <div style="clear: both;"></div>
		  </div>
		</div>
       <?
	include("footer.php");
?>
