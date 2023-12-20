<?php
require_once("./include/my_func.inc.php");

function check_login($user_id, $password)
{
	global $view_errors, $OJ_EXAM_CONTEST_ID, $MSG_WARNING_DURING_EXAM_NOT_ALLOWED, $MSG_WARNING_LOGIN_FROM_DIFF_IP;
	$pass2 = 'No Saved';
	session_destroy();
	session_start();
	$sql = "SELECT `user_id`,`password` FROM `users` WHERE `user_id`=? and defunct='N' ";
	$result = pdo_query($sql, $user_id);
	if (count($result) == 1) {
		$row = $result[0];
		if (pwCheck($password, $row['password'])) {
			$user_id = $row['user_id'];
			$ip = getRealIP();
			if (isset($OJ_EXAM_CONTEST_ID) && intval($OJ_EXAM_CONTEST_ID) > 0) {  //考试模式
				$ccid = $OJ_EXAM_CONTEST_ID;
				$sql = "select min(start_time) from contest where start_time<=now() and end_time>=now() and contest_id>=?";
				$rows = pdo_query($sql, $ccid);
				$start_time = $rows[0][0];
				$sql = "select ip from loginlog where user_id=? and time>? order by time desc";
				$rows = pdo_query($sql, $user_id, $start_time);
				$lastip = $rows[0][0];
				$sql = "select count(1) from `privilege` where `user_id`=? and `rightstr`='administrator' limit 1";
				$rows = pdo_query($sql, $user_id);
				$isAdministrator = ($rows[0][0] > 0);
				if ((!empty($lastip)) && $lastip != $ip && !($isAdministrator)) { //如果考试开后曾经登陆过，则之后登陆所用ip必须保持一致。
					$view_errors = "$MSG_WARNING_LOGIN_FROM_DIFF_IP($lastip/$ip) $MSG_WARNING_DURING_EXAM_NOT_ALLOWED!";
					return false;
				} //如遇机器故障，可经管理员后台指定新的ip来允许登陆。
			}
			$sql = "INSERT INTO `loginlog` VALUES(?,?,?,NOW())";
			// get the first 7 digits of session id
			$sess_id = substr(session_id(), 0, 7);
			pdo_query($sql, $user_id, "login ok " . $sess_id, $ip);
			$sql = "UPDATE users set accesstime=now() where user_id=?";
			pdo_query($sql, $user_id);
			return $user_id;
		}
	}
	return false;
}
