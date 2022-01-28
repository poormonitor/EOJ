<?php
header("Cache-Control: no-cache, must-revalidate");

$cache_time = 10;
$OJ_CACHE_SHARE = false;

require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
if (isset($OJ_LANG)) {
	require_once("./lang/$OJ_LANG.php");
}

if (isset($_GET["query"]) && trim($_GET["query"]) == "false") {
    $no_query = false;
    require_once("template/parent.php");
    exit(0);
}
if (!isset($_SESSION[$OJ_NAME . '_' . "user_id"]) && $_SESSION[$OJ_NAME . "_" . "vcode"] != trim($_GET["vcode"])) {
    $view_swal = "验证码错误！";
    $error_location = "parent.php?query=false";
    require_once("template/error.php");
    exit(0);
}
require_once('./include/cache_start.php');

$contest = pdo_query("SELECT `contest`.contest_id FROM `contest_problem` JOIN `contest` ON `contest_problem`.`contest_id` = `contest`.`contest_id` WHERE `contest`.`defunct` != 'Y' AND `contest`.`private` = 0 GROUP BY contest_id");
$contests_private = pdo_query("SELECT `contest`.contest_id FROM `contest_problem` JOIN `contest` ON `contest_problem`.`contest_id` = `contest`.`contest_id` WHERE `contest`.`defunct` != 'Y' AND `contest`.`private` = 1 GROUP BY contest_id");


if (isset($_GET['user']) and $_GET['user'] != '') {
    $user = trim($_GET['user']);
    $user = pdo_query("select user_id from users where user_id = ? or nick = ?", $user, $user);
    $user_array = array();
    foreach ($user as $m) {
        array_push($user_array, $m[0]);
    }
    $user_str = join("','", $user_array);
    $nick = pdo_query("select `nick` from `users` where `user_id` IN (?) ORDER BY `user_id` ASC;", $user_str);
    $group = pdo_query("select `group`.`name` from `users` join `group` on `users`.`gid` = `group`.`gid` where `user_id` IN (?) ORDER BY `user_id` ASC;", $user_str);
    $school = pdo_query("select `school` from `users` where `user_id` IN (?) ORDER BY `user_id` ASC;", $user_str);
    $contest_array = array();
    if (count($user) != 1) {
        require_once("template/parent.php");
        exit(0);
    }
    $user = $user[0][0];
} else {
    $view_swal = "没有输入用户！";
    $error_location = "parent.php?query=false";
    require_once("template/error.php");
    exit(0);
}

if (isset($_SESSION[$OJ_NAME . '_' . "last_parent_user"]) && $_SESSION[$OJ_NAME . '_' . "last_parent_user"] != $user) {
    $_SESSION[$OJ_NAME . '_' . "vcode"] = '';
}
$_SESSION[$OJ_NAME . '_' . "last_parent_user"] = $user;
foreach ($contests_private as $i) {
    $cid = $i[0];
    $right = "c" . $cid;
    $sql = "select count(*) from privilege where user_id=? and rightstr='$right' and defunct='N' and valuestr='true'";
    $result = pdo_query($sql, $user)[0][0];
    if ($result != "0") {
        array_push($contest, array($cid));
    }
}

foreach ($contest as $i) {
    array_push($contest_array, $i[0]);
}
asort($contest_array);
$contests = array();
foreach ($contest_array as $i) {
    $basic = pdo_query("select contest_id,title,end_time from contest where contest_id = ?", $i)[0];
    $problem_id = pdo_query("select problem_id from contest_problem where contest_id=? order by problem_id asc", $i);
    $end_time = $basic[2];
    $problem = array();
    foreach ($problem_id as $p) {
        $problems = array();
        $pid = $p[0];
        array_push($problems, $pid);
        $before_ac = intval(pdo_query("select count(`solution_id`) from `solution` where `user_id`=? and `problem_id`='$pid' and `in_date`<='$end_time' and result = 4;", $user)[0][0]);
        array_push($problems, $before_ac);
        $before = intval(pdo_query("select count(`solution_id`) from `solution` where `user_id`=? and `problem_id`='$pid' and `in_date`<='$end_time';", $user)[0][0]);
        array_push($problems, $before);
        $after_ac = intval(pdo_query("select count(*) from `solution` where `user_id`=? and `problem_id`='$pid' and result = 4;", $user)[0][0]);
        array_push($problems, $after_ac);
        $after = intval(pdo_query("select count(*) from `solution` where `user_id`=? and `problem_id`='$pid';", $user)[0][0]);
        array_push($problems, $after);
        $count = intval(pdo_query("SELECT COUNT(*) FROM sim JOIN solution ON solution.solution_id = sim.s_id WHERE solution.user_id = ? AND solution.problem_id= ?;", $user, $pid)[0][0]);
        array_push($problems, $count);
        array_push($problem, $problems);
    }
    array_push($basic, $problem);
    array_push($contests, $basic);
}

require_once("template/parent.php");
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
