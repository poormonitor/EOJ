<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;

//require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
if (isset($OJ_LANG)) {
	require_once("./lang/$OJ_LANG.php");
}

$now = strftime("%Y-%m-%d %H:%M", time());

if (isset($_GET['cid']))
	$ucid = "&cid=" . intval($_GET['cid']);
else
	$ucid = "";
$pr_flag = false;
$co_flag = false;

if (isset($_GET['id'])) {
	//practice
	$id = intval($_GET['id']);
	$pid = $id;
	$cid = 0;
	//require("oj-header.php");

	if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))
		$sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
	else
		$sql = "SELECT * FROM `problem` WHERE `problem_id`=? AND `defunct`='N' AND `problem_id` NOT IN (
				SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
					SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now'
				)
			)";        //////////  people should not see the problem used in contest before they end by modifying url in browser address bar
	/////////   if you give students opportunities to test their result out side the contest ,they can bypass the penalty time of 20 mins for
	/////////   each non-AC sumbission in contest. if you give them opportunities to view problems before exam ,they will ask classmates to write
	/////////   code for them in advance, if you want to share private contest problem to practice you should modify the contest into public

	$pr_flag = true;
	$result = pdo_query($sql, $id);
} else if (isset($_GET['cid']) && isset($_GET['pid'])) {
	//contest
	$cid = intval($_GET['cid']);
	$pid = intval($_GET['pid']);

	if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))
		$sql = "SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=?";
	else
		$sql = "SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=? AND (`start_time`<='$now' AND '$now'<`end_time`)";

	$result = pdo_query($sql, $cid);
	$rows_cnt = count($result);
	if ($rows_cnt == 0) {
		$view_swal = "作业不存在！";
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	}

	$row = ($result[0]);
	$contest_ok = true;

	if ($row[1] && !isset($_SESSION[$OJ_NAME . '_' . 'c' . $cid]))
		$contest_ok = false;

	if ($row[2] == 'Y')
		$contest_ok = false;

	if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))
		$contest_ok = true;

	$ok_cnt = $rows_cnt == 1;
	$langmask = $row[0];

	if ($ok_cnt != 1) {
		//not started
		$view_swal = "作业不存在！";
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	} else {
		//started
		//	$sql = "SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(  // <- defunct problem not in list
		//	$sql = "SELECT * FROM `problem` WHERE `problem_id`=(    // <-- defunct problem in list for contest but, not in list for practice
		$sql = "SELECT * FROM `problem` WHERE `problem_id`=(
			SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? AND `num`=?
		)";

		$result = pdo_query($sql, $cid, $pid);
		if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
			$sql = "SELECT solution_id FROM solution WHERE `user_id`=? AND `result` = 4 AND problem_id = ?;";
			$s_id = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], $result[0]['problem_id']);
			if (count($s_id) != 0) {
				$s_id = $s_id[0][0];
			} else {
				unset($s_id);
			}
		}
	}

	//public
	if (!$contest_ok) {
		$view_swal = "您未被邀请！";
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	}

	$co_flag = true;
} else {
	$view_swal = "$MSG_NO_SUCH_PROBLEM";
	require("template/" . $OJ_TEMPLATE . "/error.php");
	exit(0);
}

if (count($result) != 1) {
	$view_swal = "$MSG_NO_SUCH_PROBLEM";
	require("template/" . $OJ_TEMPLATE . "/error.php");
	exit(0);
} else {
	$row = $result[0];
	$view_title = $row['title'];
}
//检查当前题目是不是在NOIP模式比赛中，如果是则不显示AC数量 2020.7.11 by ivan_zhou
$now = strftime("%Y-%m-%d %H:%M", time());
$sql = "select 1 from `contest_problem` where (`problem_id`= ? or `num` = ? and `contest_id`=?) and `contest_id` IN (select `contest_id` from `contest` where `start_time` < ? and `end_time` > ? and `title` like ?)";
$rrs = pdo_query($sql, $id, $pid, $cid, $now, $now, "%$OJ_NOIP_KEYWORD%");
$flag = count($rrs) > 0;
if ($flag) {
	$row['accepted'] = '<font color="red"> ? </font>';
}


require("template/" . $OJ_TEMPLATE . "/problem.php");

//if(file_exists('./include/cache_end.php'))
	//require_once('./include/cache_end.php');
