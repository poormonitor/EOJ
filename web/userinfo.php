<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
require_once("./include/iplocation.php");
if (isset($OJ_OI_MODE) && $OJ_OI_MODE) {
	header("location:index.php");
	exit();
}
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	header("location:loginpage.php");
	exit(0);
}
// check user
$user = $_GET['user'];
if (!is_valid_user_name($user)) {
	echo "No such User!";
	exit(0);
}

//检查用户当前是否在参加NOIP模式比赛，如果是则不显示用户信息以防看到提交结果 2020.7.25
$now = date("Y-m-d H:i");
$sql = "select 1 from `solution` where  `user_id`=? and  problem_id>0 and `contest_id` IN (select `contest_id` from `contest` where `start_time` < ? and `end_time` > ? and `title` like ?)";
$rrs = pdo_query($sql, $user, $now, $now, "%$OJ_NOIP_KEYWORD%");
$flag = count($rrs) > 0;
if ($flag) {
	$view_errors =  "<h2> $MSG_NOIP_WARNING </h2>";
	require("template/error.php");
	exit(0);
}

$view_title = $user . "@" . $OJ_NAME;
$sql = "SELECT `school`,`email`,`nick` FROM `users` WHERE `user_id`=?";
$result = pdo_query($sql, $user);
$row_cnt = count($result);
if ($row_cnt == 0) {
	$view_swal = $MSG_NOT_EXISTED;
	require("template/error.php");
	exit(0);
}

$row = $result[0];
$school = $row['school'];
$email = $row['email'];
$nick = $row['nick'];

// count solved
$sql = "SELECT count(DISTINCT problem_id) as `ac` FROM `solution` WHERE `user_id`=? AND `result`=4";
$result = pdo_query($sql, $user);
$row = $result[0];
$AC = $row['ac'];

// count submission
$sql = "SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`=? and  problem_id>0";
$result = pdo_query($sql, $user);
$row = $result[0];
$Submit = $row['Submit'];

// update solved 
$sql = "UPDATE `users` SET `solved`='" . strval($AC) . "',`submit`='" . strval($Submit) . "' WHERE `user_id`=?";
$result = pdo_query($sql, $user);
$sql = "SELECT count(*) as `Rank` FROM `users` WHERE `solved`>?";
$result = pdo_query($sql, $AC);
$row = $result[0];
$Rank = intval($row[0]) + 1;

if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$sql = "SELECT user_id,password,ip,`time` FROM `loginlog` WHERE `user_id`=? order by `time` desc LIMIT 0,10";
	$view_userinfo = pdo_query($sql, $user);

	$sql = "SELECT `name` FROM `users` JOIN `group` ON `users`.`gid`=`group`.`gid` WHERE `users`.`user_id`=?;";
	$group_info = pdo_query($sql, $user);
	if (count($group_info)) $group_name = $group_info[0]["name"];
	else $group_info = "";
}

$sql = "SELECT result,count(1) FROM solution WHERE `user_id`=? AND result>=4 group by result order by result";
$result = pdo_query($sql, $user);
$view_userstat = array();
$i = 0;
foreach ($result as $row) {
	$view_userstat[$i++] = $row;
}

$sql =	"SELECT date(in_date) md,count(1) c FROM `solution` where date_sub(curdate(), INTERVAL 1 year) <= DATE(`in_date`) and `user_id`=? group by md order by md asc";
$result = pdo_query($sql, $user); //mysql_escape_string($sql));
$chart_data_all = array();
//echo $sql;

foreach ($result as $row) {
	array_push($chart_data_all, array($row['md'], intval($row['c'])));
}

$sql =	"SELECT date(in_date) md,count(1) c FROM `solution` where date_sub(curdate(), INTERVAL 1 year) <= DATE(`in_date`) and `user_id`=? and result=4 group by md order by md asc";
$result = pdo_query($sql, $user); //mysql_escape_string($sql));
$chart_data_ac = array();
//echo $sql;

foreach ($result as $row) {
	array_push($chart_data_ac, array($row['md'], intval($row['c'])));
}

$sql = "SELECT `problem_id`,count(1) from solution where `user_id`=? and result=4 group by `problem_id` ORDER BY `problem_id` ASC";
$user_ac = pdo_query($sql, $user);

require("template/userinfo.php");

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
