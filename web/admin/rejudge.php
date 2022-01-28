<?php require("admin-header.php");
?>
<?php if (isset($_POST['do'])) {
	require_once("../include/check_post_key.php");
	if (isset($_POST['rjpid'])) {
		$rjpid = intval($_POST['rjpid']);
		if ($rjpid == 0) {
			echo "Rejudge Problem ID should not equal to 0";
			exit(1);
		}
		$sql = "UPDATE `solution` SET `result`=1 WHERE `problem_id`=? and problem_id>0";
		pdo_query($sql, $rjpid);
		$sql = "delete from `sim` WHERE `s_id` in (select solution_id from solution where `problem_id`=?)";
		pdo_query($sql, $rjpid);
		$url = "../status.php?problem_id=" . $rjpid;
		echo "Rejudged Problem " . $rjpid;
		echo "<script>location.href='$url';</script>";
	} else if (isset($_POST['rjsid'])) {
		if (strpos($_POST['rjsid'], ",")) {
			$rjsid = explode(",", $_POST['rjsid']);
			$sql = "delete from `sim` WHERE `s_id`>= ? AND `s_id` <= ?";
			pdo_query($sql, $rjsid[0], $rjsid[1]);
			$sql = "UPDATE `solution` SET `result`=1 WHERE `solution_id`>= ? AND `solution_id` <= ?  AND problem_id>0";
			pdo_query($sql, $rjsid[0], $rjsid[1]);
			$url = "../status.php?top=" . ($rjsid[1]);
			echo "Rejudged Runid " . $rjsid[0] . " - " . $rjsid[1];
			echo "<script>location.href='$url';</script>";
		} else {
			$rjsid = intval($_POST['rjsid']);
			$sql = "delete from `sim` WHERE `s_id`=?";
			pdo_query($sql, $rjsid);
			$sql = "UPDATE `solution` SET `result`=1 WHERE `solution_id`=? and problem_id>0";
			pdo_query($sql, $rjsid);
			$sql = "select contest_id from `solution` WHERE `solution_id`=? ";
			$data = pdo_query($sql, $rjsid);
			$row = $data[0];
			$cid = intval($row[0]);
			if ($cid > 0)
				$url = "../status.php?cid=" . $cid . "&top=" . ($rjsid);
			else
				$url = "../status.php?top=" . ($rjsid);
			echo "Rejudged Runid " . $rjsid;
			echo "<script>location.href='$url';</script>";
		}
	} else if (isset($_POST['result'])) {
		$result = intval($_POST['result']);
		$sql = "UPDATE `solution` SET `result`=1 WHERE `result`=? and problem_id>0";
		pdo_query($sql, $result);
		$url = "../status.php?jresult=1";
		echo "<script>location.href='$url';</script>";
	} else if (isset($_POST['rjcid'])) {
		$rjcid = intval($_POST['rjcid']);
		$sql = "UPDATE `solution` SET `result`=1 WHERE `contest_id`=? and problem_id>0";
		pdo_query($sql, $rjcid);
		$url = "../status.php?cid=" . ($rjcid);
		echo "Rejudged Contest id :" . $rjcid;
		echo "<script>location.href='$url';</script>";
	}
	echo str_repeat(" ", 4096);
	flush();
	if ($OJ_REDIS) {
		$redis = new Redis();
		$redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
		if (isset($OJ_REDISAUTH)) $redis->auth($OJ_REDISAUTH);
		$sql = "select solution_id from solution where result=1 and problem_id>0";
		$result = pdo_query($sql);
		foreach ($result as $row) {
			echo $row['solution_id'] . "\n";
			$redis->lpush($OJ_REDISQNAME, $row['solution_id']);
		}
		$redis->close();
	}
}
?>
<?php require_once("../include/set_post_key.php"); ?>
<div class="container">
	<br />
	<h3 class='center'><b>重判</b></h3>
	<ol>
		<br />
		<div class='center form-horizontal'>
			<form action='rejudge.php' method=post class='form-group'>
				<label class='control-label col-sm-4'>
					<?php echo $MSG_PROBLEM ?>
				</label>
				<div class='col-sm-4'>
					<input type=input class='form-control' name='rjpid' placeholder="1001">
					<input type='hidden' name='do' value='do'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<br />
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
			<br />
			<form action='rejudge.php' method=post class='form-group'>
				<label class='control-label col-sm-4'>
					<?php echo $MSG_SUBMIT ?>
				</label>
				<div class='col-sm-4'>
					<input type=input class='form-control' name='rjsid' style='%' placeholder="1001" value='<?php if (isset($_GET['sid'])) echo $_GET['sid'] ?>'>
					<input type='hidden' name='do' value='do'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<br />
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
			<br />
			<form action='rejudge.php' method=post class='form-group'>
				<label class='control-label col-sm-4'>
					<?php echo "卡在运行中" ?>
				</label>
				<div class='col-sm-4'>
					<input type=input class='form-control' name='result' placeholder="3" value="3">
					<input type='hidden' name='do' value='do'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<br />
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
			<br />
			<form action='rejudge.php' method=post class='form-group'>
				<label class='control-label col-sm-4'>
					<?php echo $MSG_CONTEST ?>
				</label>
				<div class='col-sm-4'>
					<input type=input class='form-control' name='rjcid' placeholder="1003">
					<input type='hidden' name='do' value='do'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<br />
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
		</div>
</div>
<?php
require_once("admin-footer.php");
?>