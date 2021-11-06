<?php
$imageFolder = "../upload/image/";

reset($_FILES);
$temp = current($_FILES);
if (!is_uploaded_file($temp['tmp_name'])) {
	header("HTTP/1.1 500 Server Error");
	exit;
}

if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
	header("HTTP/1.1 400 Invalid file name.");
	exit;
}


if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png", "bmp"))) {
	header("HTTP/1.1 400 Invalid extension.");
	exit;
}

$file = explode(".", $temp['name']);
$suffix = array_pop($file);
$new_name = join(".", $file) . "_" . md5_file($temp['tmp_name']) . "." . $suffix;
$save_path = $imageFolder . date("Ymd") . "/";
if (!file_exists($save_path)) {
	mkdir($save_path, 0755);
}
$filetowrite = $save_path . $new_name;
move_uploaded_file($temp['tmp_name'], $filetowrite);
chmod($filetowrite, 0644);

echo json_encode(array('location' => "/upload/image/" . date("Ymd") . "/" . $new_name));
