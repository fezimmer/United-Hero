<?php

	include"../lphp.php";
	$mylphp=new lphp;

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "../1001281559.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1001281559";        # Change this to your store number 

	# Amount returned must be less than or equal to the order amount. 
	# If there is more than one return against this order, make sure 
	#the total of the returns doesn't exceed the original sale amount.
	$myorder["chargetotal"]  = "1.00";
	$myorder["ordertype"]    = "CREDIT";
	#$myorder["cardnumber"]   = "4111-1111-1111-1111";
	#$myorder["cardexpmonth"] = "03";
	#$myorder["cardexpyear"]  = "05";
	#Must be a valid order ID from a prior Sale
	$myorder["oid"] = "45AEF5C4-4E6FA8B7-386-159E00";




  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
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

/*
	# Look at returned hash & use the elements you need  #
	while (list($key, $value) = each($result))
	{
		echo "$key = $value\n"; 

	#if you're in web space, look at response like this:
		 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR>\n";
	}
*/
?>
