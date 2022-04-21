<?php
require_once("../include/db_info.inc.php");
require_once("../include/check_get_key.php");
$qid = intval($_GET['qid']);
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])))
	exit();
$sql = "select `private` FROM `quiz` WHERE `quiz_id`=?";
$result = pdo_query($sql, $qid);
$num = count($result);
if ($num < 1) {
	echo "No Such Problem!";
	exit(0);
}
$row = $result[0];
if (intval($row[0]) == 0) $sql = "UPDATE `quiz` SET `private`='1' WHERE `quiz_id`=?";
else $sql = "UPDATE `quiz` SET `private`='0' WHERE `quiz_id`=?";
pdo_query($sql, $qid);

header("Location: quiz_list.php");