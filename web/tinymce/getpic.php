<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");


if (!isset($_GET["file"])) {
    header("HTTP/1.1 400 Bad Request.");
    exit(0);
};

$file = "../upload/images/" . str_replace("_", "/", $_GET["file"]);

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
header("Content-Type: image/jpeg");
header("Cache-Control: private");

if (isset($_GET["large"]) || $img_info[0] <= $width) {
    imagejpeg($image);
    imagedestroy($image);
} else {
    $height = $img_info[1] * ($width / $img_info[0]);
    $com_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($com_image, $image, 0, 0, 0, 0, $width, $height, $img_info[0], $img_info[1]);
    imagejpeg($com_image);
    imagedestroy($com_image);
}
