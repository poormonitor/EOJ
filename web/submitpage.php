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
		$view_errors_js = "swal('$MSG_NOT_LOGINED','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
		require("template/error.php");
		exit(0);
	}
}

$problem_id = 1000;
if (isset($_GET['id'])) {
	$id = intval($_GET['id']);
	$problem_id = $id;
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
		require("template/error.php");
		exit(0);
	}
} else if (isset($_GET['cid']) && isset($_GET['pid'])) {
	$cid = intval($_GET['cid']);
	$pid = intval($_GET['pid']);

	$psql = "SELECT problem_id FROM contest_problem WHERE contest_id=? AND num=?";
	$data = pdo_query($psql, $cid, $pid);

	$row = $data[0];
	$problem_id = $row[0];
	$sample_sql = "SELECT sample_input, sample_output, problem_id FROM problem WHERE problem_id = ?";
} else {
	$view_swal = "$MSG_NOT_EXISTED";
	require("template/error.php");
	exit(0);
}

$view_src = "";

if (isset($_GET['sid'])) {
	$sid = intval($_GET['sid']);
	$sql = "SELECT * FROM `solution` WHERE `solution_id`=?";
	$result = pdo_query($sql, $sid);

	$row = $result[0];
	$cid = intval($row['contest_id']);
	$language_id = intval($row['language']);

	if ($row && $row['user_id'] == $_SESSION[$OJ_NAME . '_' . 'user_id'])
		$ok = true;

	if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
		$ok = true;
	} else {
		if (isset($OJ_EXAM_CONTEST_ID)) {
			if ($cid < $OJ_EXAM_CONTEST_ID && !isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
				$view_swal = $MSG_SOURCE_NOT_ALLOWED_FOR_EXAM;
				require("template/error.php");
				exit(0);
			}
		}
	}

	if ($ok == true) {
		$sql = "SELECT `source` FROM `source_code` WHERE `solution_id`=?";
		$result = pdo_query($sql, $sid);

		$row = $result[0];

		if ($row)
			$view_src = $row['source'];
		$view_src = unifyCode($view_src);

		if ($language_id == 6)
			$view_src = str_replace('# coding=utf-8' . PHP_EOL, "", $view_src);

		$sql = "SELECT langmask FROM contest WHERE contest_id=?";

		$result = pdo_query($sql, $cid);
		if (count($result))
			$row = $result[0];

		if ($row && isset($_GET['langmask']))
			$_GET['langmask'] = $row['langmask'];
	}
} elseif (isset($_SESSION[$OJ_NAME . "_" . "last_source"])) {
	if ($_SESSION[$OJ_NAME . "_" . "last_source"][0] == $problem_id) {
		$view_src = $_SESSION[$OJ_NAME . "_" . "last_source"][1];
	}
	unset($_SESSION[$OJ_NAME . "_" . "last_source"]);
}

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
		$view_swal = "$MSG_NOT_EXISTED";
		require("template/error.php");
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
	$OJ_TEST_RUN = false;
}

$blank = pdo_query("SELECT blank from problem where problem_id=?", $problem_id)[0][0];
$blank = unifyCode($blank);

$no_blank = (isset($_GET["blank"]) && $_GET["blank"] == 'false');
$multiline = false;

if ($blank !== NULL) {
	if (isset($sid)) {
		$blank_pat = "#" . preg_quote($blank) . "#";
	
		$single_blank = str_replace("%\*%", "(.*)", $blank_pat);
		preg_match_all($single_blank, $view_src, $single_line_matches);
		$single_line_matches = array_slice($single_line_matches, 1);
	
		$multi_blank = str_replace("\*%\*", "([\s\S]*)", $blank_pat);
		preg_match_all($multi_blank, $view_src, $multi_line_matches);
		$multi_line_matches = array_slice($multi_line_matches, 1);
	}

	$code = $blank;
	$code = htmlentities($code, ENT_QUOTES, "UTF-8");

	$copy = $blank;
	$copy = str_replace("%*%", "__________", $copy);

	$num = substr_count($blank, "%*%");

	for ($i = 0; $i < $num; $i++) {
		$content = isset($single_line_matches) ? $single_line_matches[$i][0] : "";
		$pattern = "<input name='code$i' class='singleline form-control' autocomplete='off' value='$content'>";
		$code = str_replace_limit("%*%", $pattern, $code, 1);
	}

	$num = substr_count($blank, "*%*");

	for ($i = 0; $i < $num; $i++) {
		preg_match("/\n.*\*%\*/m", $code, $matches);
		$len = strlen($matches[0]) - 4;
		$blanks = str_repeat(" ", $len);
		$px = $len * 10;
		$pattern = "<textarea hidden='hidden' id='multiline$i' name='multiline$i'></textarea>";
		$pattern .= "<div class='multiline editor-border' id='source$i' style='margin-left:" . $px . "px'></div>";
		$code = str_replace_limit("\n$blanks*%*\n", $pattern, $code, 1);
		$copy = str_replace("*%*\n", "...\n$blanks...\n", $copy);
		$multiline = true;
	}

	if (!$view_src)
		$view_src = $copy;

	$copy = htmlentities($copy, ENT_QUOTES, "UTF-8");
}

$sql = "SELECT `background` FROM `problem` WHERE `problem_id` = ?";
$result = pdo_query($sql, $problem_id);
if ($result && $result[0] && $result[0][0]) {
	$background_url = $result[0][0];
}

if (isset($code) and !$no_blank) {
	require("template/submitpage_blank.php");
} else {
	require("template/submitpage.php");
}
