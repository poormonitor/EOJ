<?php
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');

$view_title = $MSG_SUBMIT;

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	if (isset($OJ_GUEST) && $OJ_GUEST) {
		$_SESSION[$OJ_NAME . '_' . 'user_id'] = "Guest";
	} else {
		$view_errors_js = "swal('需要登陆','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	}
}

$problem_id = 1000;
if (isset($_GET['id'])) {
	$id = intval($_GET['id']);
	$sample_sql = "SELECT sample_input,sample_output,problem_id FROM problem WHERE problem_id = ?";
	if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))
		$sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
	else
		$sql = "SELECT * FROM `problem` WHERE `problem_id`=? AND `defunct`='N' AND `problem_id` NOT IN (
			SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
				SELECT `contest_id` FROM `contest` WHERE `end_time`> now()
				))";
	$result = pdo_query($sql, $id);
	if (count($result) != 1) {
		$view_swal = $MSG_NO_SUCH_PROBLEM;
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	}
} else if (isset($_GET['cid']) && isset($_GET['pid'])) {
	$cid = intval($_GET['cid']);
	$pid = intval($_GET['pid']);

	$psql = "SELECT problem_id FROM contest_problem WHERE contest_id=? AND num=?";
	$data = pdo_query($psql, $cid, $pid);

	$row = $data[0];
	$problem_id = $row[0];
	$sample_sql = "SELECT p.sample_input, p.sample_output, p.problem_id FROM problem p WHERE problem_id = ? ";
} else {
	$view_swal = "题目不存在！";
	require("template/" . $OJ_TEMPLATE . "/error.php");
	exit(0);
}

$view_src = "";

if (isset($_GET['sid'])) {
	$sid = intval($_GET['sid']);
	$sql = "SELECT * FROM `solution` WHERE `solution_id`=?";
	$result = pdo_query($sql, $sid);

	$row = $result[0];
	$cid = intval($row['contest_id']);

	if ($row && $row['user_id'] == $_SESSION[$OJ_NAME . '_' . 'user_id'])
		$ok = true;

	if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
		$ok = true;
	} else {
		if (isset($OJ_EXAM_CONTEST_ID)) {
			if ($cid < $OJ_EXAM_CONTEST_ID && !isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
				$view_swal = $MSG_SOURCE_NOT_ALLOWED_FOR_EXAM;
				require("template/" . $OJ_TEMPLATE . "/error.php");
				exit(0);
			}
		}
	}

	if ($ok == true) {
		$sql = "SELECT `source` FROM `source_code_user` WHERE `solution_id`=?";
		$result = pdo_query($sql, $sid);

		$row = $result[0];

		if ($row)
			$view_src = $row['source'];

		$sql = "SELECT langmask FROM contest WHERE contest_id=?";

		$result = pdo_query($sql, $cid);
		if (count($result))
			$row = $result[0];

		if ($row)
			$_GET['langmask'] = $row['langmask'];
	}
}

if (isset($id))
	$problem_id = $id;

$view_sample_input = "1 2";
$view_sample_output = "3";

if (isset($sample_sql)) {
	//echo $sample_sql;
	if (isset($_GET['id'])) {
		$result = pdo_query($sample_sql, $id);
	} else {
		$result = pdo_query($sample_sql, $problem_id);
	}

	if ($result == false) {
		$view_swal = "题目不存在！";
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	}

	$row = $result[0];
	$view_sample_input = $row[0];
	$view_sample_output = $row[1];
	$problem_id = $row[2];
}

$lastlang = 0;
if (!$view_src) {
	if (isset($_COOKIE['lastlang']) && $_COOKIE['lastlang'] != "undefined") {
		$lastlang = intval($_COOKIE['lastlang']);
	} else {
		$sql = "SELECT language FROM solution WHERE user_id=? ORDER BY solution_id DESC LIMIT 1";
		$result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);

		if (count($result) > 0) {
			$lastlang = $result[0][0];
		} else {
			$lastlang = 0;
		}
		//echo "last=$lastlang";
	}
	$template_file = "$OJ_DATA/$problem_id/template." . $language_ext[$lastlang];

	if (file_exists($template_file)) {
		$view_src = file_get_contents($template_file);
	}
}

$sql = "SELECT count(1) FROM `solution` WHERE result<4";
$result = mysql_query_cache($sql);

$row = $result[0];

if ($row[0] > 10) {
	$OJ_VCODE = true;
	//$OJ_TEST_RUN=false;
	//echo "$row[0]";
}
$blank = pdo_query("select blank from problem where problem_id=?", $problem_id)[0][0];
$no_blank = (isset($_GET["blank"]) and $_GET["blank"] == 'false');
$multiline = false;
if (isset($sid)) {
	$no_blank = true;
}
if ($blank != NULL) {
	$code = $blank;
	$code = htmlentities($code, ENT_QUOTES, "UTF-8");
	$num = substr_count($blank, "%*%");
	for ($i = 0; $i < $num; $i++) {
		$code = str_replace_limit("%*%", "<input name='code$i' style='margin-top:3px;margin-bottom:3px;' autocomplete='off'></input>", $code, 1);
	}
	$num = substr_count($blank, "*%*");
	for ($i = 0; $i < $num; $i++) {
		$code = str_replace_limit("*%*\r\n", "<textarea hidden='hidden' id='multiline$i' name='multiline$i' autocomplete='off'></textarea><pre id='source$i' style='height:150px;width:auto;font-size:13pt;margin-top:8px;'></pre>", $code, 1);
		$multiline = true;
	}
	$copy = $blank;
	$copy = str_replace("%*%", "__________", str_replace("*%*\r\n", "...\r\n...\r\n", htmlentities($copy, ENT_QUOTES, "UTF-8")));
}

require("template/" . $OJ_TEMPLATE . "/submitpage.php");

