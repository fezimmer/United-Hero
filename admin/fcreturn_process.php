<?
	require_once('../preheader.php');

	#the code for the class
	include ('ajaxCRUD.class.php');
	include"../lphp.php";
	$mylphp=new lphp;

	# get the project ID so that we can query the payments table for 
	# all payments made for the project
	if (isset($_GET['pkProjectID'])) { 
	    $projectID = $_GET['pkProjectID']; 
	}
 
	// BUILD PAYMENT TABLE
	# the tblPayment ajaxCRUD object is used solely to display the payment info.  It is displayed
	# in a table at the top of the screen.
	#
	#this one line of code is how you implement the class
	########################################################
	##
	$tblPayment = new ajaxCRUD("Payment", "tblPayment", "pkPaymentID");
	##
	########################################################

	## what follows is setup configuration for your fields....
	# define a relationship to another table
	# the first field is the fk in the table, the second is the second table, 
	# the third is the pk in the second table, and the forth is the field you 
	# want to retrieve as the dropdown value
	$tblPayment->defineRelationship("fkUserID", "tblUser", "pkUserID", "CONCAT(fldFName, ' ', fldLName)", "fldFName");

	$tblPayment->displayAs("fldAmount", "Donation");
	$tblPayment->displayAs("fldOrderNum", "Order ID");
	$tblPayment->omitField("pkPaymentID");
	$tblPayment->omitField("fldName");
	$tblPayment->omitField("fkProjectID");
	$tblPayment->omitField("fldDatetime");
	$tblPayment->omitField("fldTransactionID");
	$tblPayment->omitField("fldRef");
	$tblPayment->omitField("fldIPAddress");
	$tblPayment->disallowDelete();
	$tblPayment->disallowAdd();

	#actually show to the table
	$tblPayment->showTable();

	# get all payments for this project from the database
	$sql_stmt = "SELECT * from tblPayment WHERE fkProjectID = $projectID";
	$payments = q($sql_stmt);

	//iterate over each and every payment made to this project
	foreach ($payments as $payment){
		 $id = $payment['pkPaymentID'];
		 print "<p>id = >>" . $id . "<<<br>";
		
		# initialize the myorder array...
		$myorder = array( );

		# Next a hash is created with the transaction info to be sent to the First Data
		# globalgateway server
		$myorder["host"]       = "secure.linkpt.net";
		$myorder["port"]       = "1129";
		$myorder["keyfile"]    = "../1001281559.pem"; # name and location of certificate file
		$myorder["configfile"] = "1001281559";        # the UH store number
		$myorder["ordertype"]    = "CREDIT";

		# We need to create a 
		$myorder["oid"] = $payment['fldOrderNum'];

		# Amount returned must be less than or equal to the order amount. 
		# If there is more than one return against this order, make sure 
		# the total of the returns doesn't exceed the original sale amount.
		$myorder["chargetotal"] = $payment['fldAmount'];
 
	        # Look at returned hash & use the elements you need  #
		while (list($key, $value) = each($myorder))
		{
			#echo "$key = $value\n";
			#if you're in web space, look at response like this:
			 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR>\n";
		}

		# Send transaction. 
		$result = $mylphp->curl_process($myorder);  # use curl methods
		if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
		{
			print "Status: $result[r_approved]\n";
			print "Error: $result[r_error]\n";
		}
		else
		{					// success
			print "Status: $result[r_approved]\n";
			print "Code: $result[r_code]\n";
			print "OID: $result[r_ordernum]\n\n";
		}
	}
?>

