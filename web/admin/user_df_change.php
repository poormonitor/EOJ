<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/check_get_key.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

$cid = $_GET['cid'];

$sql = "select `defunct` FROM `users` WHERE `user_id`=?";
$result = pdo_query($sql, $cid);

$num = count($result);
if ($num < 1) {
	$view_swal = "No Such User!";
	require_once("../template/error.php");
	exit(0);
}

$row = $result[0];
if ($row[0] == 'N')
	$sql = "UPDATE `users` SET `defunct`='Y' WHERE `user_id`=?";
else
	$sql = "UPDATE `users` SET `defunct`='N' WHERE `user_id`=?";
pdo_query($sql, $cid);

$ip = getRealIP();
$sql = "INSERT INTO `loginlog` VALUES(?,?,?,NOW())";
pdo_query($sql, $cid, "user df changed $row[0] by " . $_SESSION[$OJ_NAME . "_" . "user_id"], $ip);

$page = intval($_GET['page']);
$page = $page ? $page : 1;

header("Location: user_list.php?page=$page");
