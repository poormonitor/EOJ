<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/check_get_key.php");

$cid = intval($_GET['cid']);
$page = intval($_GET['page']);
$page = $page ? $page : 1;

if (!(isset($_SESSION[$OJ_NAME . '_' . "m$cid"])
	|| isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
	|| isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

$sql = "select `private` FROM `contest` WHERE `contest_id`=?";
$result = pdo_query($sql, $cid);
$num = count($result);
if ($num < 1) {
	echo "No Such Problem!";

	exit(0);
}
$row = $result[0];
if (intval($row[0]) == 0) $sql = "UPDATE `contest` SET `private`='1' WHERE `contest_id`=?";
else $sql = "UPDATE `contest` SET `private`='0' WHERE `contest_id`=?";
pdo_query($sql, $cid);

$ip = getRealIP();
$sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
pdo_query($sql, "c$cid", $_SESSION[$OJ_NAME . '_' . 'user_id'], "pr change", $ip);

header("Location: contest_list.php?page=$page");
