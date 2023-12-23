<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;

require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
}

if (!isset($_SESSION[$OJ_NAME . '_' . "user_id"]) && $_SESSION[$OJ_NAME . "_" . "vcode"] != trim($_GET["vcode"])) {
    $view_swal = "$MSG_VCODE_WRONG";
    $error_location = "contest.php";
    require_once("template/error.php");
    exit(0);
}
require_once('./include/cache_start.php');

$contest = pdo_query("SELECT `contest`.contest_id FROM `contest_problem` JOIN `contest` ON `contest_problem`.`contest_id` = `contest`.`contest_id` WHERE `contest`.`defunct` != 'Y' AND `contest`.`private` = 0 GROUP BY contest_id");
$contests_private = pdo_query("SELECT `contest`.contest_id FROM `contest_problem` JOIN `contest` ON `contest_problem`.`contest_id` = `contest`.`contest_id` WHERE `contest`.`defunct` != 'Y' AND `contest`.`private` = 1 GROUP BY contest_id");


if (isset($_GET['user']) and $_GET['user'] != '') {
    $user = trim($_GET['user']);
    $sql = "SELECT user_id, g.name, nick, school, users.gid from users 
            LEFT JOIN `group` g ON g.gid = users.gid
            where user_id = ? or nick = ?";
    $users = pdo_query($sql, $user, $user);
} else {
    $view_swal = $MSG_PARAMS_ERROR;
    $error_location = "contest.php";
    require_once("template/error.php");
    exit(0);
}

if (count($users) != 1) {
    $multiple = true;
    require_once("template/query.php");
    exit(0);
}

$user = $users[0][0];
$group = $users[0][1];
$nick = $users[0][2];
$school = $users[0][3];
$gid = $users[0][4];

if (isset($_SESSION[$OJ_NAME . '_' . "last_query_user"]) && $_SESSION[$OJ_NAME . '_' . "last_query_user"] != $user) {
    $_SESSION[$OJ_NAME . '_' . "vcode"] = '';
}
$_SESSION[$OJ_NAME . '_' . "last_query_user"] = $user;
foreach ($contests_private as $i) {
    $cid = $i[0];
    $right = "c" . $cid;
    $sql = "SELECT count(*) from privilege where user_id=? and rightstr='$right' and defunct='N' and valuestr='true'";
    $sql2 = "SELECT count(*) from privilege_group where gid=? and rightstr='$right' and defunct='N' and valuestr='true'";
    $result = pdo_query($sql, $user)[0][0];
    $result2 = pdo_query($sql2, $gid)[0][0];
    if ($result + $result2) {
        array_push($contest, array($cid));
    }
}

$contest_array = array();
foreach ($contest as $i) {
    array_push($contest_array, $i[0]);
}
asort($contest_array);
$contests = array();
foreach ($contest_array as $i) {
    $basic = pdo_query("SELECT contest_id,title,end_time from contest where contest_id = ?", $i)[0];
    $problem_id = pdo_query("SELECT problem_id from contest_problem where contest_id=? order by problem_id asc", $i);
    $end_time = $basic[2];
    $problem = array();
    foreach ($problem_id as $p) {
        $problems = array();
        $pid = $p[0];
        array_push($problems, $pid);
        $before_ac = intval(pdo_query("SELECT count(`solution_id`) from `solution` where `user_id`=? and `problem_id`='$pid' and `contest_id`='$i' and `in_date`<='$end_time' and result = 4;", $user)[0][0]);
        array_push($problems, $before_ac);
        $before = intval(pdo_query("SELECT count(`solution_id`) from `solution` where `user_id`=? and `problem_id`='$pid' and `contest_id`='$i' and `in_date`<='$end_time';", $user)[0][0]);
        array_push($problems, $before);
        $after_ac = intval(pdo_query("SELECT count(*) from `solution` where `user_id`=? and `problem_id`='$pid' and result = 4;", $user)[0][0]);
        array_push($problems, $after_ac);
        $after = intval(pdo_query("SELECT count(*) from `solution` where `user_id`=? and `problem_id`='$pid';", $user)[0][0]);
        array_push($problems, $after);
        $count = intval(pdo_query("SELECT COUNT(*) FROM sim JOIN solution ON solution.solution_id = sim.s_id WHERE solution.user_id = ? AND solution.problem_id= ? and `solution`.`contest_id`='$i';", $user, $pid)[0][0]);
        array_push($problems, $count);
        array_push($problem, $problems);
    }
    array_push($basic, $problem);
    array_push($contests, $basic);
}

require_once("template/query.php");
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
