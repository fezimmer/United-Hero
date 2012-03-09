<?php
// === CONFIGURATION =====================================================
//  location of source images (no trailing /)
$image_path = './images';
$image_path2 = './project_images';

//  location of cached images (no trailing /)
$cache_path = './images/cache';

//  location of imagemagick's convert utility
$convert_path = '/usr/bin/convert';

// === CHECK INPUT =======================================================
// first, check if an image location is given
if (!isset($_SERVER['PATH_INFO'])) {
	die('ERROR: No image specified.');
}
$image = $image_path.$_SERVER['PATH_INFO'];

// next, check if the file exists
if (!file_exists($image)) {
	$image = $image_path2.$_SERVER['PATH_INFO'];
	if (!file_exists($image)) {
		die('ERROR: That image does not exist.');
	}
}

// === PARSE COMMANDS ====================================================
// extract the commands from the query string
// eg.: ?resize(....)+flip+blur(...)
preg_match_all('/\+*(([a-z]+)(\(([^\)]*)\))?)\+*/',
               $_SERVER['QUERY_STRING'],
               $matches, PREG_SET_ORDER);

// concatenate commands for use in cache file name
$cache = $_SERVER['PATH_INFO'];
foreach ($matches as $match) {
	$cache .= '%'.$match[2].':'.$match[4];
}
$cache = str_replace('/','_',$cache);
$cache = $cache_path.'/'.$cache;
$cache = escapeshellcmd($cache);

// === RUN CONVERT =======================================================
if (!file_exists($cache)) {
	// there is no cached image yet, so we'll need to create it first

	// convert query string to an imagemagick command string
	$commands = '';
	foreach ($matches as $match) {
		// $match[2] is the command name
		// $match[4] the parameter

		// check input
		if (!preg_match('/^[a-z]+$/',$match[2])) {
			die('ERROR: Invalid command.');
		}
		if (!preg_match('/^[a-z0-9\/{}+-<>!@%]+$/',$match[4])) {
			die('ERROR: Invalid parameter.');
		}

		// replace } with >, { with <
		// > and < could give problems when using html
		$match[4] = str_replace('}','>',$match[4]);
		$match[4] = str_replace('{','<',$match[4]);

		// check for special, scripted commands
		switch ($match[2]) {
			case 'colorizehex':
				// imagemagick's colorize, but with hex-rgb colors
				// convert to decimal rgb
				$r = round((255 - hexdec(substr($match[4], 0, 2))) / 2.55);
				$g = round((255 - hexdec(substr($match[4], 2, 2))) / 2.55);
				$b = round((255 - hexdec(substr($match[4], 4, 2))) / 2.55);

				// add command to list
				$commands .= ' -colorize "'."$r/$g/$b".'"';
				break;

			case 'part':
				// crops the image to the requested size
				if (!preg_match('/^[0-9]+x[0-9]+$/',$match[4])) {
					die('ERROR: Invalid parameter.');
				}

				list($width, $height) = explode('x', $match[4]);

				// get size of the original
				$imginfo = getimagesize($image);
				$orig_w = $imginfo[0];
				$orig_h = $imginfo[1];

				// resize image to match either the new width
				// or the new height

				// if original width / original height is greater
				// than new width / new height
				if ($orig_w/$orig_h > $width/$height) {
					// then resize to the new height...
					$commands .= ' -resize "x'.$height.'"';

					// ... and get the middle part of the new image
					// what is the resized width?
					$resized_w = ($height/$orig_h) * $orig_w;

					// crop
					$commands .= ' -crop "'.$width.'x'.$height.
					             '+'.round(($resized_w - $width)/2).'+0"';
				} else {
					// or else resize to the new width
					$commands .= ' -resize "'.$width.'"';

					// ... and get the middle part of the new image
					// what is the resized height?
					$resized_h = ($width/$orig_w) * $orig_h;

					// crop
					$commands .= ' -crop "'.$width.'x'.$height.
					             '+0+'.round(($resized_h - $height)/2).'"';
				}
				break;

			case 'type':
				// convert the image to this file type
				if (!preg_match('/^[a-z]+$/',$match[4])) {
					die('ERROR: Invalid parameter.');
				}
				$new_type = $match[4];
				break;

			default:
				// nothing special, just add the command
				if ($match[4]=='') {
					// no parameter given, eg: flip
					$commands .= ' -'.$match[2].'';
				} else {
					$commands .= ' -'.$match[2].' "'.$match[4].'"';
				}
		}
	}

	// create the convert-command
	$convert = $convert_path.' '.$commands.' "'.$image.'" ';
	if (isset($new_type)) {
		// send file type-command to imagemagick
		$convert .= $new_type.':';
	}
	$convert .= '"'.$cache.'"';

	// execute imagemagick's convert, save output as $cache
	exec($convert);
}

// === OUTPUT ============================================================
// there should be a file named $cache now
if (!file_exists($cache)) {
	die('ERROR: Image conversion failed.');
}

// get image data for use in http-headers
$imginfo = getimagesize($cache);
$content_length = filesize($cache);
$last_modified = gmdate('D, d M Y H:i:s',filemtime($cache)).' GMT';

// array of getimagesize() mime types
$getimagesize_mime = array(1=>'image/gif',2=>'image/jpeg',3=>'image/png',
                           4=>'application/x-shockwave-flash',5=>'image/psd',
                           6=>'image/bmp',7=>'image/tiff',8=>'image/tiff',
                           9=>'image/jpeg',
                           13=>'application/x-shockwave-flash',
                           14=>'image/iff');

// did the browser send an if-modified-since request?
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	// parse header
	$if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);

	if ($if_modified_since == $last_modified) {
		// the browser's cache is still up to date
		header("HTTP/1.0 304 Not Modified");
		header("Cache-Control: max-age=86400, must-revalidate");
		exit;
	}
}

// send other headers
header('Cache-Control: max-age=86400, must-revalidate');
header('Content-Length: '.$content_length);
header('Last-Modified: '.$last_modified);
if (isset($getimagesize_mime[$imginfo[2]])) {
	header('Content-Type: '.$getimagesize_mime[$imginfo[2]]);
} else {
	// send generic header
	header('Content-Type: application/octet-stream');
}

// and finally, send the image
readfile($cache);

?>