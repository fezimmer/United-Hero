<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>&lt;?php</p>
<p>/*</p>
<p> AUTOMATIC IMAGE ROTATOR<br />
  Version 2.2 - December 4, 2003<br />
  Copyright (c) 2002-2003 Dan P. Benjamin, Automatic, Ltd.<br />
  All Rights Reserved.</p>
<p> http://www.hiveware.com/imagerotator.php<br />
    <br />
  http://www.automaticlabs.com/<br />
  <br />
  <br />
  DISCLAIMER<br />
  Automatic, Ltd. makes no representations or warranties about<br />
  the suitability of the software, either express or<br />
  implied, including but not limited to the implied<br />
  warranties of merchantability, fitness for a particular<br />
  purpose, or non-infringement. Dan P. Benjamin and Automatic, Ltd.<br />
  shall not be liable for any damages suffered by licensee<br />
  as a result of using, modifying or distributing this<br />
  software or its derivatives.<br />
  <br />
  <br />
  ABOUT<br />
  This PHP script will randomly select an image file from a<br />
  folder of images on your webserver.  You can then link to it<br />
  as you would any standard image file and you'll see a random<br />
  image each time you reload.<br />
  <br />
  When you want to add or remove images from the rotation-pool,<br />
  just add or remove them from the image rotation folder.<br />
</p>
<p> VERSION CHANGES<br />
  Version 1.0<br />
  - Release version<br />
  <br />
  Version 1.5<br />
  - Tweaked a few boring bugs<br />
  <br />
  Version 2.0<br />
  - Complete rewrite from the ground-up<br />
  - Made it clearer where to make modifications<br />
  - Made it easier to specify/change the rotation-folder<br />
  - Made it easier to specify/change supported image types<br />
  - Wrote better instructions and info (you're them reading now)<br />
  - Significant speed improvements<br />
  - More error checking<br />
  - Cleaner code (albeit more PHP-specific)<br />
  - Better/faster random number generation and file-type parsing<br />
  - Added a feature where the image to display can be specified<br />
  - Added a cool feature where, if an error occurs (such as no<br />
  images being found in the specified folder) *and* you're<br />
  lucky enough to have the GD libraries compiled into PHP on<br />
  your webserver, we generate a replacement &quot;error image&quot; on<br />
  the fly.<br />
  <br />
  Version 2.1<br />
  - Updated a potential security flaw when value-matching<br />
  filenames</p>
<p> Version 2.2<br />
  - Updated a few more potential security issues<br />
  - Optimized the code a bit.<br />
  - Expanded the doc for adding new mime/image types.</p>
<p> Thanks to faithful ALA reader Justin Greer for<br />
  lots of good tips and solid code contribution!<br />
</p>
<p> INSTRUCTIONS<br />
  1. Modify the $folder setting in the configuration section below.<br />
  2. Add image types if needed (most users can ignore that part).<br />
  3. Upload this file (rotate.php) to your webserver.  I recommend<br />
  uploading it to the same folder as your images.<br />
  4. Link to the file as you would any normal image file, like this:</p>
<p> &lt;img src=&quot;http://example.com/rotate.php&quot;&gt;</p>
<p> 5. You can also specify the image to display like this:</p>
<p> &lt;img src=&quot;http://example.com/rotate.php?img=gorilla.jpg&quot;&gt;<br />
    <br />
  This would specify that an image named &quot;gorilla.jpg&quot; located<br />
  in the image-rotation folder should be displayed.<br />
  <br />
  That's it, you're done.</p>
<p>*/<br />
</p>
<p>&nbsp;</p>
<p>/* ------------------------- CONFIGURATION -----------------------<br />
</p>
<p> Set $folder to the full path to the location of your images.<br />
  For example: $folder = '/user/me/example.com/images/';<br />
  If the rotate.php file will be in the same folder as your<br />
  images then you should leave it set to $folder = '.';</p>
<p>*/<br />
</p>
<p> $folder = 'http://www.unitedhero.com/images/photo/';<br />
</p>
<p>/* </p>
<p> Most users can safely ignore this part.  If you're a programmer,<br />
  keep reading, if not, you're done.  Go get some coffee.</p>
<p> If you'd like to enable additional image types other than<br />
  gif, jpg, and png, add a duplicate line to the section below<br />
  for the new image type.<br />
  <br />
  Add the new file-type, single-quoted, inside brackets.<br />
  <br />
  Add the mime-type to be sent to the browser, also single-quoted,<br />
  after the equal sign.<br />
  <br />
  For example:<br />
  <br />
  PDF Files:</p>
<p> $extList['pdf'] = 'application/pdf';<br />
    <br />
  CSS Files:</p>
<p> $extList['css'] = 'text/css';</p>
<p> You can even serve up random HTML files:</p>
<p> $extList['html'] = 'text/html';<br />
  $extList['htm'] = 'text/html';</p>
<p> Just be sure your mime-type definition is correct!</p>
<p>*/</p>
<p> $extList = array();<br />
  $extList['gif'] = 'image/gif';<br />
  $extList['jpg'] = 'image/jpeg';<br />
  $extList['jpeg'] = 'image/jpeg';<br />
  $extList['png'] = 'image/png';<br />
</p>
<p>// You don't need to edit anything after this point.<br />
</p>
<p>// --------------------- END CONFIGURATION -----------------------</p>
<p>$img = null;</p>
<p>if (substr($folder,-1) != '/') {<br />
  $folder = $folder.'/';<br />
  }</p>
<p>if (isset($_GET['img'])) {<br />
  $imageInfo = pathinfo($_GET['img']);<br />
  if (<br />
  isset( $extList[ strtolower( $imageInfo['extension'] ) ] ) &amp;&amp;<br />
  file_exists( $folder.$imageInfo['basename'] )<br />
  ) {<br />
  $img = $folder.$imageInfo['basename'];<br />
  }<br />
  } else {<br />
  $fileList = array();<br />
  $handle = opendir($folder);<br />
  while ( false !== ( $file = readdir($handle) ) ) {<br />
  $file_info = pathinfo($file);<br />
  if (<br />
  isset( $extList[ strtolower( $file_info['extension'] ) ] )<br />
  ) {<br />
  $fileList[] = $file;<br />
  }<br />
  }<br />
  closedir($handle);</p>
<p> if (count($fileList) &gt; 0) {<br />
  $imageNumber = time() % count($fileList);<br />
  $img = $folder.$fileList[$imageNumber];<br />
  }<br />
  }</p>
<p>if ($img!=null) {<br />
  $imageInfo = pathinfo($img);<br />
  $contentType = 'Content-type: '.$extList[ $imageInfo['extension'] ];<br />
  header ($contentType);<br />
  readfile($img);<br />
  } else {<br />
  if ( function_exists('imagecreate') ) {<br />
  header (&quot;Content-type: image/png&quot;);<br />
  $im = @imagecreate (100, 100)<br />
  or die (&quot;Cannot initialize new GD image stream&quot;);<br />
  $background_color = imagecolorallocate ($im, 255, 255, 255);<br />
  $text_color = imagecolorallocate ($im, 0,0,0);<br />
  imagestring ($im, 2, 5, 5,  &quot;IMAGE ERROR&quot;, $text_color);<br />
  imagepng ($im);<br />
  imagedestroy($im);<br />
  }<br />
  }</p>
<p>?&gt;<br />
</p>
</body>
</html>
