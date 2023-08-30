<?php
require_once("../include/db_info.inc.php");
require_once("../include/const.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/check_get_key.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
	|| isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

$qid = intval($_GET['qid']);
if (!(isset($_SESSION[$OJ_NAME . '_' . "mq$qid"]) || isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) exit(0);
$sql = "SELECT `defunct` FROM `quiz` WHERE `quiz_id`=?";
$result = pdo_query($sql, $qid);
if ($result) {
	$row = $result[0];
	if ($row[0] == 'N')
		$sql = "UPDATE `quiz` SET `defunct`='Y' WHERE `quiz_id`=?";
	else
		$sql = "UPDATE `quiz` SET `defunct`='N' WHERE `quiz_id`=?";
	pdo_query($sql, $qid);

	$ip = getRealIP();
	$sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
	pdo_query($sql, "q$qid", $_SESSION[$OJ_NAME . '_' . 'user_id'], "df change", $ip);
}

$page = intval($_GET['page']);
$page = $page ? $page : 1;

header("Location: quiz_list.php?page=$page");
