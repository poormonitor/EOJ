<?php
require_once("../include/db_info.inc.php");
require_once("../include/check_get_key.php");
$cid = intval($_GET['cid']);

if (!(isset($_SESSION[$OJ_NAME . '_' . "m$cid"])
	|| isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
	|| isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

$sql = "select `defunct` FROM `contest` WHERE `contest_id`=?";
$result = pdo_query($sql, $cid);
$num = count($result);
if ($num < 1) {
	$view_swal =  "No Such Contest!";
	require_once("../template/error.php");
	exit(0);
}
$row = $result[0];
if ($row[0] == 'N')
	$sql = "UPDATE `contest` SET `defunct`='Y' WHERE `contest_id`=?";
else
	$sql = "UPDATE `contest` SET `defunct`='N' WHERE `contest_id`=?";
pdo_query($sql, $cid);

header("Location: contest_list.php");
