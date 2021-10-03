<?php
    require_once('include/db_info.inc.php');
    ob_clean(); 

    $width = 100;
    $height = 34;
    $font_size = 20;
    $font = "include/NotoSans-Bold.ttf";
    $font = realpath($font);
    $chars_length = 4;
    if (isset($_GET['small'])){
        $width = 70;
        $height = 25;
    }
    $captcha_characters = '0123456789abcdefghijkmnpqrstuvwxyz';

    $image = imagecreatetruecolor($width, $height);
    $bg_color = imagecolorallocate($image, rand(0,127), rand(0,127), rand(0,127));
    $font_color = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

    $vert_line = round($width/5);
    for($i=0; $i < $vert_line; $i++) {
        $color = imagecolorallocate($image, rand(128,255), rand(128,255), rand(128,255));
        imageline($image, rand(0,$width), rand(0,$height), rand(0,$height), rand(0,$width), $color);
    }

    $xw = ($width/$chars_length);
    $x = 0;
    $font_gap = $xw/2-$font_size/2;
    $digit = '';
    for($i = 0; $i < $chars_length; $i++) {
        $letter = $captcha_characters[rand(0, strlen($captcha_characters)-1)];
        $digit .= $letter;
        if ($i == 0) {
            $x = 0;
        } else {
            $x = $xw*$i;
        }
        imagettftext($image, $font_size, rand(-20,20), $x+$font_gap, rand(21, $height-5), $font_color, $font, $letter);
    }

    $_SESSION[$OJ_NAME.'_'.'vcode'] = strtolower($digit);
    // display image
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pramga: no-cache");
    header('Content-Type: image/png');
    header('x-vcode:'.strtolower($digit));
    imagepng($image);
    imagedestroy($image);
?>
