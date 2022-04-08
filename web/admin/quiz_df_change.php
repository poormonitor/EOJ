<?php
require_once("../include/db_info.inc.php");
require_once("../include/const.inc.php");
require_once("../include/check_get_key.php");
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
}
header("Location: quiz_list.php");
