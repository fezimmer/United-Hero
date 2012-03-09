<?
	require_once('../preheader.php');

	#the code for the class
	include ('ajaxCRUD.class.php');

	include"../lphp.php";
	$mylphp=new lphp;

// BUILD PAYMENT TABLE

  #this one line of code is how you implement the class
    ########################################################
    ##

    $tblPayment = new ajaxCRUD("Payment", "tblPayment", "pkPaymentID");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....

    #define a relationship to another table
    #the first field is the fk in the table, the second is the second table, the third is the pk in the second table, and the fort h is the field you want to retrieve as the dropdown value
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
//    $tablePayment->
      #actually show to the table
    $tblPayment->showTable();

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "../1001281559.pem"; # name and location of your certificate file
	$myorder["configfile"] = "1001281559";        # the UH store number


	# Amount returned must be less than or equal to the order amount. 
	# If there is more than one return against this order, make sure 
	#the total of the returns doesn't exceed the original sale amount.

	#print "<br> >>" . $tblPayment->fldAmount . "<< <br>";
	#$myorder["chargetotal"]  = "5.00";
	$myorder["type"]    = "return";
	#$myorder["cardnumber"]   = "4111-1111-1111-1111";
	#$myorder["cardexpmonth"] = "03";
	#$myorder["cardexpyear"]  = "05";
	#Must be a valid order ID from a prior Sale
	$myorder["orderid"] = "45AEF5C4-4E6FA8B7-386-159E00";

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


	# Look at returned hash & use the elements you need  #
	while (list($key, $value) = each($result))
	{
		echo "$key = $value\n"; 

	#if you're in web space, look at response like this:
		 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR>\n";
	}

?>

