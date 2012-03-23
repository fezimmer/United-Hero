<?
	//function developed by Forrest Z. of LCM for rewards.php file
        //serves to validate that a reward has been selected
        function rewardIsSelected($rewards){
            foreach ($rewards as $reward){
                if($reward == "Claimed"){
                    return true;
                }
            }
            return false;
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