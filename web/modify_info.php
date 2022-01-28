<?php require_once("./include/db_info.inc.php"); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><?php echo $OJ_NAME; ?></title>
	<style>
		@media (prefers-color-scheme: dark) {
			body {
				height: auto;
				background: #242424;
			}
		}
	</style>
</head>

<body>
	<script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
	<script src="<?php echo $OJ_CDN_URL .  "include/" ?>sweetalert.min.js"></script>
	<?php
	$cache_time = 10;
	$OJ_CACHE_SHARE = false;
	require_once('./include/cache_start.php');
	require_once('./include/setlang.php');
	$view_title = "Welcome To Online Judge";
	require_once("./include/check_post_key.php");
	require_once("./include/my_func.inc.php");
	if (
		(isset($OJ_EXAM_CONTEST_ID) && $OJ_EXAM_CONTEST_ID > 0) ||
		(isset($OJ_ON_SITE_CONTEST_ID) && $OJ_ON_SITE_CONTEST_ID > 0)
	) {
		$view_errors = $MSG_MODIFY_NOT_ALLOWED_FOR_EXAM;
		require("template/error.php");
		exit();
	}
	$err_str = "";
	$err_cnt = 0;
	$len;

	if (!$_SESSION[$OJ_NAME . '_' . 'user_id']) {
		die();
	}
	$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
	$school = trim($_POST['school']);
	$nick = trim($_POST['nick']);
	$len = strlen($nick);
	if ($len > 100) {
		$err_str = $err_str . $MSG_NICK_TOO_LONG . " ";
		$err_cnt++;
	} else if ($len == 0) $nick = $user_id;
	$len = strlen($_POST['school']);
	if ($len > 100) {
		$err_str = $err_str . $MSG_SCHOOL_TOO_LONG . " ";
		$err_cnt++;
	}
	if ($err_cnt > 0) {
		print "<script language='javascript'>\n";
		echo "swal('";
		echo $err_str;
		print "').then((onConfirm)=>{history.go(-1);});\n</script>";
		exit(0);
	}
	$nick = htmlentities($nick, ENT_QUOTES, "UTF-8");
	$school = (htmlentities($school, ENT_QUOTES, "UTF-8"));
	$sql = "UPDATE `users` SET"
		. "`nick`=?,"
		. "`school`=? ";
	$sql .= "WHERE `user_id`=?";
	//echo $sql;
	//exit(0);
	pdo_query($sql, $nick, $school, $user_id);
	?>
	<script>
		swal(<?php echo $MSG_SUCCESS ?>).then((onConfirm) => {
			history.go(-1);
		})
	</script>
	<?php
	?>
</body>

</html>