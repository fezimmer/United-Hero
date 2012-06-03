<?php

function resize_image($file, $w, $h, $crop=FALSE) {
    $img = new Imagick($file);
    if ($crop) {
        $img->cropThumbnailImage($w, $h);
    } else {
        $img->thumbnailImage($w, $h, TRUE);
    }

    return $img;
}
resize_image("/images/80w80hTest.jpg", 130, 110);
?>