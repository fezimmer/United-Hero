<?
	//functions developed by LCM for rewards feature
        //serves to validate that a reward has been selected
        function rewardIsSelected($rewards){
            foreach ($rewards as $reward){
                if($reward != ""){
                    return true;
                }
            }
            return false;
        }

        //counts rewards based off either title, description, support, or image being provided
        function rewardCount($title, $description, $support, $image, $deletion){
            $fcount = 1;
            $count = 1;
            if($title != null){
                foreach($title as $x){
                    if($x != "" && $x != null){
                        $count++;
                    }
                }
            }
            if($count > $fcount){
                $fcount = $count;
            }
            $count = 1;
            if($description != null){
                foreach($description as $x){
                    if($x != "" && $x != null){
                        $count++;
                    }
                }
            }
            if($count > $fcount){
                $fcount = $count;
            }
            $count = 1;
            if($support != null){
                foreach($support as $x){
                    if($x != "" && $x != null){
                        $count++;
                    }
                }
            }
            if($count > $fcount){
                $fcount = $count;
            }
            $count = 1;
            if($image != null){
                foreach($image as $x){
                    if($x != "" && $x != null){
                        $count++;
                    }
                }
            }
            if($count > $fcount){
                $fcount = $count;
            }
            
            return $fcount;
        }

        //reward validation
        function checkRewards($finalCount, $rewardsTitle, $rewardsDesc, $rewardsAvail, $rewardsSupport, $rewardsMonth, $rewardsYear){
            $errors = array();
            for($i=1; $i<$finalCount; $i++){
                if($rewardsTitle[$i] == null){
                    $errors[] = "Title cannot be blank";
                    break;
                }
            }
            for($i=1; $i<$finalCount; $i++){
                if($rewardsDesc[$i] == null){
                    $errors[] = "Description cannot be blank";
                    break;
                } else if(strlen($rewardsDesc[$i]) > 200){
                    $errors[] = "Description cannot be longer than 200 characters";
                    break;
                }
            }
            for($i=1; $i<$finalCount; $i++){
                if($rewardsAvail[$i] != null){
                    if(!is_numeric($rewardsAvail[$i])){
                        $errors[] = "Limit must be an integer";
                        break;
                    }else if($rewardsAvail[$i] < 1){
                        $errors[] = "minimum Limit Amount is 1";
                        break;
                    }
                }
            }
            for($i=1; $i<$finalCount; $i++){
                if($rewardsSupport[$i] != null){
                    if(!is_numeric($rewardsSupport[$i])){
                        $errors[] = "Support must be an integer";
                        break;
                    }else if($rewardsSupport[$i] < 1){
                        $errors[] = "minimum Support Amount is $1";
                        break;
                    }
                }else if($rewardsSupport[$i] == null){
                    $errors[] = "Support cannot be blank";
                    break;
                }
            }
            for($i=1; $i<$finalCount; $i++){
                if($rewardsMonth[$i] == ""){
                    $errors[] = "Delivery Month not selected";
                    break;
                }
            }
            for($i=1; $i<$finalCount; $i++){
                if($rewardsYear[$i] == ""){
                    $errors[] = "Delivery Year not selected";
                    break;
                }
            }
            return $errors;
        }
        //end LCM functions

        function getRewardMonth($val){
            if($val == "01"){
                return "JANUARY (01)";
            }elseif($val == "02"){
                return "FEBRUARY (02)";
            }elseif($val == "03"){
                return "MARCH (03)";
            }elseif($val == "04"){
                return "APRIL (04)";
            }elseif($val == "05"){
                return "MAY (05)";
            }elseif($val == "06"){
                return "JUNE (06)";
            }elseif($val == "07"){
                return "JULY (07)";
            }elseif($val == "08"){
                return "AUGUST (08)";
            }elseif($val == "09"){
                return "SEPTEMBER (09)";
            }elseif($val == "10"){
                return "OCTOBER (10)";
            }elseif($val == "11"){
                return "NOVEMBER (11)";
            }elseif($val == "12"){
                return "DECEMBER (12)";
            }else{
                return "Delivery Month*";
            }
        }

        function getMonthForReward($val){
            if(strcmp($val, "01") == 0){
                return "Jan";
            }if(strcmp($val, "02") == 0){
                return "Feb";
            }if(strcmp($val, "03") == 0){
                return "Mar";
            }if(strcmp($val, "04") == 0){
                return "Apr";
            }if(strcmp($val, "05") == 0){
                return "May";
            }if(strcmp($val, "06") == 0){
                return "Jun";
            }if(strcmp($val, "07") == 0){
                return "Jul";
            }if(strcmp($val, "08") == 0){
                return "Aug";
            }if(strcmp($val, "09") == 0){
                return "Sep";
            }if(strcmp($val, "10") == 0){
                return "Oct";
            }if(strcmp($val, "11") == 0){
                return "Nov";
            }else{
                return "Dec";
            }
        }
        
        function getTimeRemaining($projectID){
		$projCreateDate = q1("SELECT fldDateCreated FROM tblProject WHERE pkProjectID = $projectID");
		$projEndDate = q1("SELECT fldEndDate FROM tblProject WHERE pkProjectID = $projectID");

		$today = date("m/d/y h:i:s");
		//echo "today: $today <br />";

		if (!$projEndDate){
			//use 32 days from the start date if no end date was given

			$projCreateDate = date("m/d/y h:i:s", mysqlDatetimeToUnixTimestamp($projCreateDate));
			//echo "createDate: $projCreateDate<br />";

			$projEndDateTimestamp = strtotime("$projCreateDate +32 days");
			$projEndDate = date("m/d/y h:i:s", $projEndDateTimestamp);
		}
		else{
			$projEndDate = date("m/d/y h:i:s", mysqlDatetimeToUnixTimestamp($projEndDate));
		}

		//echo "endDate: $projEndDate<br />";

		$timeDifference = get_time_difference($today, $projEndDate);
		//echo "difference: <br />";
		//print_r($timeDifference);
		return $timeDifference;
	}

	//function used to display the project vimeo or youtube video
	//video is different dimentions, depending on the page
	function displayVideo($fldVideoHTML, $location = "project"){

		//default dimentions (project page)
		$width = "542";
		$height = "357";
		if ($location == "index"){
			$width = "442";
			$height = "257";
		}

		if (!$fldVideoHTML || $fldVideoHTML == "n/a" || $fldVideoHTML == "na" || $fldVideoHTML == "none" || (strlen($fldVideoHTML) < 6) ){
			//assumes no video...
			global $projectID; //assumes this is in scope
			$projectImage = q1("SELECT fldImage FROM tblProject WHERE pkProjectID = $projectID");
			if ($projectImage){
				echo "<img src=\"/magick.php/$projectImage?resize({$width}x{$height})\" />\n";
			}
		}

		//if the video is vimeo
		if (stristr($fldVideoHTML, "vimeo.com") !== FALSE){
			$vimeoVideoID = str_replace("http://vimeo.com/", "", $fldVideoHTML);
			if ($vimeoVideoID != ""){
				echo "<iframe class=\"video\" src=\"http://player.vimeo.com/video/{$vimeoVideoID}\" width=\"$width\" height=\"$height\"></iframe>\n";
			}
		}
		else if (stristr($fldVideoHTML, "youtube.com") !== FALSE){
			$youTubeArray = parse_url($fldVideoHTML);
			$youTubeVideoQuery = str_replace("v=", "", $youTubeArray['query']);
			$youTubeArray = split("&", $youTubeVideoQuery);
			$youTubeVideoID = $youTubeArray[0];
			//print_r($youTubeArray);
			if ($youTubeVideoID){
				echo "<object width=\"$width\" height=\"$height\"><param name=\"movie\" value=\"http://www.youtube.com/v/{$youTubeVideoID}?version=3&amp;hl=en_US\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/{$youTubeVideoID}?version=3&amp;hl=en_US\" type=\"application/x-shockwave-flash\" width=\"$width\" height=\"$height\" allowscriptaccess=\"always\" allowfullscreen=\"true\"></embed></object>\n";
			}
		}
		else if (stristr($fldVideoHTML, "youtu.be") !== FALSE){
			$youTubeVideoID = str_replace("http://youtu.be/", "", $fldVideoHTML);
			//print_r($youTubeArray);
			if ($youTubeVideoID){
				echo "<object width=\"$width\" height=\"$height\"><param name=\"movie\" value=\"http://www.youtube.com/v/{$youTubeVideoID}?version=3&amp;hl=en_US\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/{$youTubeVideoID}?version=3&amp;hl=en_US\" type=\"application/x-shockwave-flash\" width=\"$width\" height=\"$height\" allowscriptaccess=\"always\" allowfullscreen=\"true\"></embed></object>\n";
			}
		}
	}

	function parseLinks($text){
		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		// Check if there is a url in the text
		if(preg_match($reg_exUrl, $text, $url)) {
			// make the urls hyper links
			return preg_replace($reg_exUrl, "<a href=\"{$url[0]}\">{$url[0]}</a> ", $text);
		}
		else {
		   return $text;
		}
	}

	/*
	function stripNonNumeric($string){
		return $preg_replace ('/[^\d\s]/', '', $string);
	}
	*/

?>