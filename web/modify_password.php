<?php require_once("./include/db_info.inc.php"); ?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<title><?php echo $OJ_NAME; ?></title>
	<?php include("./template/css.php"); ?>
</head>

<body>
	<script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
	<script src="<?php echo $OJ_CDN_URL .  "include/" ?>vendor.min.js"></script>
	<?php
	$cache_time = 10;
	$OJ_CACHE_SHARE = false;
	require_once('./include/cache_start.php');
	require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title = $OJ_NAME;
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
	$password = $_POST['old-password'];
	$sql = "SELECT `user_id`,`password` FROM `users` WHERE `user_id`=?";
	$result = pdo_query($sql, $user_id);
	$row = $result[0];
	if ($row && pwCheck($password, $row['password'])) $rows_cnt = 1;
	else $rows_cnt = 0;
	if ($rows_cnt == 0) {
		$err_str = $err_str . "Old password incorrect!";
		$err_cnt++;
	}
	$len = strlen($_POST['new1-password']);
	if ($len < 6 && $len > 0) {
		$err_cnt++;
		$err_str = $err_str . "Password at least six characters!\\n";
	} else if (strcmp($_POST['new1-password'], $_POST['new2-password']) != 0) {
		$err_str = $err_str . "Passwords do not match!";
		$err_cnt++;
	}
	if ($err_cnt > 0) {
		print "<script language='javascript'>\n";
		echo "swal('";
		echo $err_str;
		print "').then((onConfirm)=>{history.go(-1);});\n</script>";
		exit(0);
	}
	if (strlen($_POST['new1-password']) == 0) $password = pwGen($_POST['old-password']);
	else $password = pwGen($_POST['new1-password']);
	$sql = "UPDATE `users` SET"
		. "`password`=?";
	$sql .= "WHERE `user_id`=?";
	//echo $sql;
	//exit(0);
	pdo_query($sql, $password, $user_id);
	?>
	<script>
		swal("<?php echo $MSG_SUCCESS ?>").then((onConfirm) => {
			history.go(-1);
		});
	</script>
	<?php
	?>
</body>

</html>