<?php

    session_start();
    $font = 'LaBelleAurore.ttf';
    $font2 = 'Xerox Sans Serif Narrow.ttf';
    
    header('Content-Type: image/png');
    
    $im       = imagecreatetruecolor(200,125);
    
    $mag      = imagecolorallocate($im, 209, 25, 83);
    $teal     = imagecolorallocate($im, 25, 209, 193);
    
    imagefilledrectangle($im, 8, 8, 185, 110, $teal);
    
    $length = 4;
    $text1 = substr(str_shuffle(md5(time())), 0, $length);
    $text2 = substr(str_shuffle(md5(time())), 0, $length);
    
    $text = $text1.$text2;
    $_SESSION["captcha"] = $text;
    
    //$ses="8ilvvgk5r4ep34o9l4qs7v9dh4";
    
    imagettftext($im, 20, -15, 45, 30, $mag, $font, $text1);
    imagettftext($im, 25, 15, 60, 80, $mag, $font, $text2);
    //imagettftext($im, 10, 0, 15, 90, $black, $font2, $text);
    //imagettftext($im, 10, 0, 15, 100, $black, $font2, $ses);
    imagepng($im);
    imagedestroy($im);

?>
