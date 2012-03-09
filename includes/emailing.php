<?
function isValidEmail($email) {
	if ($email == ''){
		return FALSE;
	}

   if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email))
   {
      return FALSE;
   }

   list($Username, $Domain) = split("@",$email);

   if(@getmxrr($Domain, $MXHost))
   {
      return TRUE;
   }
   else
   {
      if(@fsockopen($Domain, 25, $errno, $errstr, 30))
      {
         return TRUE;
      }
      else
      {
         return FALSE;
      }
   }
}

function send_email_from_template($template_name,$email,$subject,$email_template_params, $send_copy_to_admin = "0",$from_email = '',$main_template_file="frame.html"){
	global $report_msg, $error_msg, $globals;

	if($email != ''){
		$email = trim($email);
		//print_r($email_template_params);

		$datasource = "/home/loudcanv/www/";
		$lcm_datasource = $datasource;

		if ($globals['site_root_dir'] != "" && $globals['site_root_dir'] != "/"){
			$datasource = $globals['site_root_dir'];
		}

		$datetime = date("l, M j h:i:s");

		//read templates in and form email content
		$template = $datasource . "includes/email_templates/".$template_name;

		//if the variable was set BUT no template exists in that location default to lcm dir location
		if (!file_exists($template)){
			$datasource = $lcm_datasource;
			$template = $datasource . "includes/email_templates/".$template_name;
		}

		$handle = fopen($template,"r");

		$template_content = fread($handle, filesize($template));
		//echo "xxx".$template_content."xxx";
		$success = fclose($handle);

		if (!$success){
			$error_msg[] = "An error occured locating the outter email template";
		}

		//$main_template = $globals['site_root_dir'].'includes/email_templates/frame.html';
		$main_template = $datasource . "includes/email_templates/$main_template_file";

		if (!file_exists($main_template)){
			$datasource = $lcm_datasource;
			$main_template = $datasource . "includes/email_templates/$main_template_file";
		}

		$handle = fopen($main_template,"r");
		$main_template_content = fread($handle, filesize($main_template));
		$success = fclose($handle);;

		if (!$success){
			$error_msg[] = "An error occured locating the inner email template";
		}

		//default values
		$main_template_content = str_replace("::TITLE::",$subject,$main_template_content);
		$main_template_content = str_replace("::CONTENT::",$template_content,$main_template_content);
		//$main_template_content = str_replace("::EMAIL::",$globals[contact_email],$main_template_content);
		$main_template_content = str_replace("::YEAR::",$globals[site_year],$main_template_content);
        if ($email_template_params[SITENAME] == ""){
        	$main_template_content = str_replace("::SITENAME::",$globals[site_name],$main_template_content);
        }
        $main_template_content = str_replace("::HEADER_IMAGE::",$globals[header_image],$main_template_content);
        $main_template_content = str_replace("::HEADER_IMAGE_WIDTH::",$globals[header_image_width],$main_template_content);
        $main_template_content = str_replace("::TIME::",$datetime,$main_template_content);

		foreach(array_keys($email_template_params) as $param){
			//echo "replace(::".$param."::,".$email_template_params[$param];
			$main_template_content = str_replace("::".$param."::",stripslashes($email_template_params[$param]),$main_template_content);
		}

		//default (if not overridden)
		$main_template_content = str_replace("::TOP-HEADER-TEXT::","This notification is for your information only. No action is required.",$main_template_content);

		//email html letter
		if($from_email == ''){
			//$from_string = "$globals[contact_email] (" . $globals[contact_email_name] . ")";
            //$from_string = "$globals[contact_email] (" . $globals[site_name] . ")";
            $from_string = "noreploy@" .  $globals[site_name] . " (" . $globals[site_name] . ")";
        }
		else{
			$from_string = $from_email;
        }

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: $from_string \n";
		//$headers .= "Reply-To: $from_string\n";
		$headers .= "Message-ID: <".md5($email)."@".$globals[domain_name].$globals[domain_ext].">"."\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n";

		//safemode restricts the 5th parameter
		//if(mail($email,$subject,$main_template_content,$headers,"-f$globals[contact_email]"))
		//if(mail(trim($email),$subject,$main_template_content,$headers)){
		if (count($error_msg) == 0){
			if (mail(trim($email),$subject,$main_template_content,$headers."From: ".$from_string."\nReply-To:".$from_string."\n","-f" . $from_string)){
				$return = true;
			}
			else{
				$error_msg[] = "Could not send mail to address: ".$email.". Please contact ".$globals[contact_email].".";
				//echo "headers:<br />\n" . $headers . "<br />\n";
				//echo "content:<br />\n" . $main_template_content . "<br />\n";
				$return = false;
			}
			if($send_copy_to_admin == "1" && (count($error_msg) == 0)){
				//send a copy to the admin
				$admin_email = $globals['webmaster_email'];
				if ($admin_email == "") $admin_email = "arts@loudcanvas.com";
				mail(trim($admin_email),"ADMIN COPY: $subject (sent to $email)",$main_template_content,$headers."From: ".$globals[contact_email]."\nReply-To:".$globals[contact_email]."\n","-finfo@".$globals[domain_name].$globals[domain_ext]);
			}
		}
	}//email != ''

	return $return;


}//function



?>