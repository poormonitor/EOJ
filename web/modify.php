<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><?php echo $OJ_NAME; ?></title>
</head>

<body></body>

</html>
<script src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/template/bs3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = "Welcome To Online Judge";
require_once("./include/check_post_key.php");
require_once("./include/my_func.inc.php");
if (
	(isset($OJ_EXAM_CONTEST_ID) && $OJ_EXAM_CONTEST_ID > 0) ||
	(isset($OJ_ON_SITE_CONTEST_ID) && $OJ_ON_SITE_CONTEST_ID > 0)
) {
	$view_errors = $MSG_MODIFY_NOT_ALLOWED_FOR_EXAM;
	require("template/" . $OJ_TEMPLATE . "/error.php");
	exit();
}
$err_str = "";
$err_cnt = 0;
$len;
$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
$email = trim($_POST['email']);
$len = strlen($nick);
if ($len > 100) {
	$err_str = $err_str . "Nick Name Too Long!";
	$err_cnt++;
} else if ($len == 0) $nick = $user_id;
$password = $_POST['opassword'];
$sql = "SELECT `user_id`,`password` FROM `users` WHERE `user_id`=?";
$result = pdo_query($sql, $user_id);
$row = $result[0];
if ($row && pwCheck($password, $row['password'])) $rows_cnt = 1;
else $rows_cnt = 0;

if ($rows_cnt == 0) {
	$err_str = $err_str . "原密码错误！";
	$err_cnt++;
}
$len = strlen($_POST['npassword']);
if ($len < 6 && $len > 0) {
	$err_cnt++;
	$err_str = $err_str . "密码应大于六位！\n";
} else if (strcmp($_POST['npassword'], $_POST['rptpassword']) != 0) {
	$err_str = $err_str . "密码不一致！";
	$err_cnt++;
}
if ($err_cnt > 0) {
	print "<script language='javascript'>\n";
	echo "swal('";
	echo $err_str;
	print "').then((onConfirm)=>{history.go(-1);});\n</script>";
	exit(0);
}
if (strlen($_POST['npassword']) == 0) $password = pwGen($_POST['opassword']);
else $password = pwGen($_POST['npassword']);
$email = (htmlentities($email, ENT_QUOTES, "UTF-8"));
$sql = "UPDATE `users` SET"
	. "`password`=?,"
	. "`email`=?"
	. "WHERE `user_id`=?";
pdo_query($sql, $password, $email, $user_id);
$sql = "update solution set nick=? where user_id=?";
pdo_query($sql, $nick, $user_id);
header("Location: ./");
?>