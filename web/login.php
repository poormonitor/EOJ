<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<title><?php echo $MSG_LOGIN ?></title>
</head>

<body>
	<style>
		@media (prefers-color-scheme: dark) {
			body {
				height: auto;
				background: #242424;
			}
		}
	</style>

	<?php require_once("./include/db_info.inc.php"); ?>

	<script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
	<script src="<?php echo $OJ_CDN_URL .  "include/" ?>sweetalert.min.js"></script>

	<?php
	require_once('./include/setlang.php');
	$use_cookie = false;
	$login = false;
	$cookie_user = $OJ_IDENTITY . "_user";
	$cookie_check = $OJ_IDENTITY . "_check";
	if ($OJ_COOKIE_LOGIN = true && isset($_COOKIE[$cookie_user]) && isset($_COOKIE[$cookie_check])) {
		$C_check = $_COOKIE[$cookie_check];
		$C_user = $_COOKIE[$cookie_user];
		$use_cookie = true;
		$C_num = strlen($C_check) - 1;
		$C_num = ($C_num * $C_num) % 7;
		if ($C_check[strlen($C_check) - 1] != $C_num) {
			setcookie($cookie_check, "", 0);
			setcookie($cookie_user, "", 0);
			echo "<script>\nswal('Cookie $MSG_ERROR (-1)').then((onConfirm)=>{history.go(-1);});\n</script>";
			exit(0);
		}
		$C_info = pdo_query("SELECT `password`,`accesstime` FROM `users` WHERE `user_id`=? and defunct='N'", $C_user)[0];
		$C_len = strlen($C_info[1]);
		for ($i = 0; $i < strlen($C_info[0]); $i++) {
			$tp = ord($C_info[0][$i]);
			$C_res .= chr(39 + ($tp * $tp + ord($C_info[1][$i % $C_len]) * $tp) % 88);
		}
		if (substr($C_check, 0, -1) == sha1($C_res))
			$login = $C_user;
		else {
			setcookie($cookie_check, "", 0);
			setcookie($cookie_user, "", 0);
			echo "<script>\nswal('Cookie $MSG_ERROR (-2)').then((onConfirm)=>{history.go(-1);});\n</script>";
			exit(0);
		}
	}
	$vcode = "";
	if (!$use_cookie) {
		if (isset($_POST['vcode'])) $vcode = trim($_POST['vcode']);
		if ($OJ_VCODE && ($vcode != $_SESSION[$OJ_NAME . '_' . "vcode"] || $vcode == "" || $vcode == null)) {
			echo "<script language='javascript'>\n";
			echo "swal('$MSG_ERROR','$MSG_VCODE_WRONG','error).then((onConfirm)=>{history.go(-1);});\n";
			echo "</script>";
			exit(0);
		}
		$view_errors = "";
		require_once("./include/login-" . $OJ_LOGIN_MOD . ".php");
		$user_id = $_POST['user_id'];
		$password = $_POST['password'];
		$user_id = stripslashes($user_id);
		$password = stripslashes($password);
		$login = check_login($user_id, $password);
	}
	if ($login) {
		$sql = "SELECT * FROM `privilege` WHERE `user_id`=?";
		$_SESSION[$OJ_NAME . '_' . 'user_id'] = $login;
		$result = pdo_query($sql, $login);

		foreach ($result as $row) {
			if ($row['valuestr'])
				$_SESSION[$OJ_NAME . '_' . $row['rightstr']] = $row['valuestr'];
			else
				$_SESSION[$OJ_NAME . '_' . $row['rightstr']] = true;
		}

		$_SESSION[$OJ_NAME . '_' . "allow_view"] = true;

		$sql = "SELECT `gid` FROM `users` WHERE `user_id`=?";
		$result = pdo_query($sql, $login);
		if (count($result)) {
			$_SESSION[$OJ_NAME . '_' . "gid"] = $result[0][0];

			$sql = "SELECT allow_view from `group` where gid=?";
			$allow = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . "gid"]);
			$_SESSION[$OJ_NAME . '_' . "allow_view"] = $allow[0][0] == "Y";

			$sql = "SELECT * FROM `privilege_group` WHERE `gid`=?";
			$group_privileges = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . "gid"]);

			foreach ($group_privileges as $row) {
				if ($row['valuestr'])
					$_SESSION[$OJ_NAME . '_' . $row['rightstr']] = $row['valuestr'];
				else
					$_SESSION[$OJ_NAME . '_' . $row['rightstr']] = true;
			}
		}

		if (isset($_SESSION[$OJ_NAME . '_vip'])) {  // VIP mark can access all [VIP] marked contest
			$sql = "select contest_id from contest where title like '%[VIP]%'";
			$result = pdo_query($sql);
			foreach ($result as $row) {
				$_SESSION[$OJ_NAME . '_c' . $row['contest_id']] = true;
			}
		};

		$sql = "UPDATE users SET accesstime = NOW() WHERE user_id=?";
		$result = pdo_query($sql, $login);

		if ($OJ_LONG_LOGIN) {
			$C_info = pdo_query("SELECT `password` , `accesstime` FROM `users` WHERE `user_id`=? and defunct='N'", $login)[0];
			$C_len = strlen($C_info[1]);
			$C_res = "";
			for ($i = 0; $i < strlen($C_info[0]); $i++) {
				$tp = ord($C_info[0][$i]);
				$C_res .= chr(39 + ($tp * $tp + ord($C_info[1][$i % $C_len]) * $tp) % 88);
			}
			$C_res = sha1($C_res);
			$C_time = time() + 86400 * $OJ_KEEP_TIME;
			setcookie($cookie_user, $login, time() + $C_time);
			setcookie($cookie_check, $C_res . (strlen($C_res) * strlen($C_res)) % 7, $C_time);
		}
		echo "<script language='javascript'>\n";
		if ($OJ_NEED_LOGIN)
			echo "window.location.href='index.php';\n";
		else
			echo "setTimeout('history.go(-2)',500);\n";
		echo "</script>";
	} else {
		if ($view_errors) {
			require("template/error.php");
		} else {
			echo "<script language='javascript'>\n";
			echo "swal('$MSG_ERROR','$MSG_UP_WRONG','error').then((onConfirm)=>{history.go(-1);});\n";
			echo "</script>";
		}
	}
	?>

</body>

</html>