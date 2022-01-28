<?php require("admin-header.php"); ?>
<?php if (isset($_POST['prefix'])) {
	require_once("../include/check_post_key.php");
	$prefix = $_POST['prefix'];
	require_once("../include/my_func.inc.php");
	if (!is_valid_user_name($prefix)) {
		echo "Prefix is not valid.";
		exit(0);
	}
	$teamnumber = intval($_POST['teamnumber']);
	$pieces = explode("\n", trim($_POST['ulist']));

	if ($teamnumber > 0) {
		echo "<table border=1>";
		echo "<tr><td colspan=3>Copy these accounts to distribute</td></tr>";
		echo "<tr><td>team_name<td>login_id</td><td>password</td></tr>";
		$max_length = 20;
		for ($i = 1; $i <= $teamnumber; $i++) {

			$user_id = $prefix . ($i < 10 ? ('0' . $i) : $i);
			$password = strtoupper(substr(MD5($user_id . rand(0, 9999999)), 0, 10));
			if (isset($pieces[$i - 1]))
				$nick = $pieces[$i - 1];
			else
				$nick = "your_own_nick";
			if ($teamnumber == 1) $user_id = $prefix;

			echo "<tr><td>$nick<td>$user_id</td><td>$password</td></tr>";

			$password = pwGen($password);
			$email = "your_own_email@internet";

			$school = "your_own_school";
			$ip = ($_SERVER['REMOTE_ADDR']);
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
				$tmp_ip = explode(',', $REMOTE_ADDR);
				$ip = (htmlentities($tmp_ip[0], ENT_QUOTES, "UTF-8"));
			}

			if (mb_strlen($nick, 'utf-8') > 20) {
				$new_len = mb_strlen($nick, 'utf-8');
				if ($new_len > $max_length) {
					$max_length = $new_len;
					$longer = "ALTER TABLE `users` MODIFY COLUMN `nick` varchar($max_length) NULL DEFAULT '' ";
					pdo_query($longer);
				}
			}
			$sql = "INSERT INTO `users`(" . "`user_id`,`email`,`ip`,`accesstime`,`password`,`reg_time`,`nick`,`school`)" .
				"VALUES(?,?,?,NOW(),?,NOW(),?,?)on DUPLICATE KEY UPDATE `email`=?,`ip`=?,`accesstime`=NOW(),`password`=?,`reg_time`=now(),nick=?,`school`=?";
			pdo_query($sql, $user_id, $email, $ip, $password, $nick, $school, $email, $ip, $password, $nick, $school);
		}
		echo  "</table>";
	}
}
?>
<div class="container">
	<h3 class='center'><?php echo $MSG_TEAMGENERATOR ?></h3>
	<p style="color:red;font-weight:bold" class='center'><?php echo $MSG_HELP_TEAMGENERATOR ?></p>
	<br />
	<form action='team_generate.php' method=post class='form-horizontal'>
		<div class='form-group'>
			<label class="col-sm-4 control-label"><?php echo $MSG_PREFIX ?></label>
			<div class='col-sm-4'>
				<input class='form-control' name='prefix' placeholder="<?php echo $MSG_PREFIX ?>" size=10>
			</div>
		</div>
		<div class='form-group'>
			<label class="col-sm-4 control-label"><?php echo $MSG_TEAM_NUMBER ?></label>
			<div class='col-sm-4'>
				<input class='form-control' name='teamnumber' placeholder="<?php echo $MSG_TEAM_NUMBER ?>" size=10>
			</div>
		</div>
		<div class='form-group'>
			<label class="col-sm-4 control-label"><?php echo $MSG_USER_LIST ?></label>
			<div class='col-sm-4'>
				<textarea class='form-control' name='ulist' placeholder="<?php echo $MSG_USER_LIST ?>" rows=10 cols=50></textarea>
			</div>
		</div>
		<div class='form-group'>
			<div class='col-sm-offset-5 col-sm-4'>
				<input type='submit' class='btn btn-primary' value='Generate'>
			</div>
		</div>
	</form>
</div>
<?php
require_once("admin-footer.php");
?>