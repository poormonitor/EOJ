<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");


if (!isset($_GET["file"])) {
    header("HTTP/1.1 400 Bad Request.");
    exit(0);
};

$token = sha1(md5($DB_PASS . $DB_USER));

$file = base64url_decode($_GET["file"]);
$file = explode("_", $file);
$path = "../upload/images_$token/" . $file[0] . "/";
$filename  = md5($file[1]);
$file = $path . $filename;

if (!(isset($_SESSION[$OJ_NAME . '_' . 'user_id'])
    || isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
    || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
    header("HTTP/1.1 401 Unauthorized.");
    exit(0);
};

if (!file_exists($file)) {
    header("HTTP/1.1 404 Not Found.");
    exit(0);
};

$image = imagecreatefromstring(file_get_contents($file));
$img_info = getimagesize($file);
$width = 400;
header("Cache-Control: private");

if (isset($_GET["large"]) || $img_info[0] <= $width) {
    imagedestroy($image);
    $type = mime_content_type($file);
    header("Content-Type: $type");
    set_time_limit(0);
    readfile($file);
} else {
    header("Content-Type: image/jpeg");
    $height = $img_info[1] * ($width / $img_info[0]);
    $com_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($com_image, $image, 0, 0, 0, 0, $width, $height, $img_info[0], $img_info[1]);
    imagejpeg($com_image);
    imagedestroy($com_image);
}
