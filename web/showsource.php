<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = "Source Code";

require_once("./include/const.inc.php");
if (!isset($_GET['id'])) {
	$view_swal = "$MSG_NOT_EXISTED";
	require("template/error.php");
	exit(0);
}
$ok = false;
$id = intval($_GET['id']);
$sql = "SELECT * FROM `solution` WHERE `solution_id`=?";
$result = pdo_query($sql, $id);
$row = $result[0];
$slanguage = $row['language'];
$sresult = $row['result'];
$stime = $row['time'];
$smemory = $row['memory'];
$sproblem_id = $row['problem_id'];
$view_user_id = $suser_id = $row['user_id'];
$contest_id = $row['contest_id'];
$snick = $row['nick'];
$judge_color = array("label label-info", "label label-info", "label label-warning", "label label-warning", "label label-success", "label label-danger", "label label-danger", "label label-warning", "label label-warning", "label label-warning", "label label-warning", "label label-warning", "label label-warning", "label label-info");


if (isset($OJ_EXAM_CONTEST_ID)) {
	if ($contest_id < $OJ_EXAM_CONTEST_ID && !isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
		$view_swal = $MSG_SOURCE_NOT_ALLOWED_FOR_EXAM;
		require("template/error.php");
		exit();
	}
}

if (isset($OJ_AUTO_SHARE) && $OJ_AUTO_SHARE && isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$sql = "SELECT 1 FROM solution where 
			result=4 and problem_id=$sproblem_id and user_id=?";
	$rrs = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);
	$ok = (count($rrs) > 0);
}

//check whether user has the right of view solutions of this problem
//echo "checking...";
if (isset($_SESSION[$OJ_NAME . '_' . 's' . $sproblem_id])) {
	$ok = true;
	//	echo "Yes";
} else {
	$sql = "select count(1) from privilege where user_id=? and rightstr=?";
	$count = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], "s" . $sproblem_id);
	if ($count && $count[0][0] > 0) {
		$_SESSION[$OJ_NAME . '_' . 's' . $sproblem_id] = true;
		$ok = true;
	} else {
		//echo "not right";
	}
}
$view_source = $MSG_NOT_EXISTED;
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id']) && $row && $row['user_id'] == $_SESSION[$OJ_NAME . '_' . 'user_id']) $ok = true;
if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) $ok = true;

if (!$ok) {
	$view_swal = $MSG_WARNING_ACCESS_DENIED;
	require("template/error.php");
	exit();
}


$sql = "SELECT `source` FROM `source_code` WHERE `solution_id`=?";
$result = pdo_query($sql, $id);
$row = $result[0];
if ($row)
	$view_source = $row['source'];


require("template/showsource.php");

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
