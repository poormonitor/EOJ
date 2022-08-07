<?php
if (isset($_POST['do'])) {
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
		header("Location: " . $url);
	} else if (isset($_POST['rjsid'])) {
		if (strpos($_POST['rjsid'], ",")) {
			$rjsid = explode(",", $_POST['rjsid']);
			$sql = "DELETE FROM `sim` WHERE `s_id`>= ? AND `s_id` <= ?";
			pdo_query($sql, $rjsid[0], $rjsid[1]);
			$sql = "UPDATE `solution` SET `result`=1 WHERE `solution_id`>= ? AND `solution_id` <= ?  AND problem_id>0";
			pdo_query($sql, $rjsid[0], $rjsid[1]);
			$url = "../status.php?top=" . ($rjsid[1]);
			header("Location: " . $url);
		} else {
			$rjsid = intval($_POST['rjsid']);
			$sql = "delete from `sim` WHERE `s_id`=?";
			pdo_query($sql, $rjsid);
			$sql = "UPDATE `solution` SET `result`=1 WHERE `solution_id`=? and problem_id>0";
			pdo_query($sql, $rjsid);
			$sql = "SELECT contest_id FROM `solution` WHERE `solution_id`=? ";
			$data = pdo_query($sql, $rjsid);
			$row = $data[0];
			$cid = intval($row[0]);
			if ($cid > 0)
				$url = "../status.php?cid=" . $cid . "&top=" . ($rjsid);
			else
				$url = "../status.php?top=" . ($rjsid);
			header("Location: " . $url);
		}
	} else if (isset($_POST['result'])) {
		$result = intval($_POST['result']);
		$sql = "UPDATE `solution` SET `result`=1 WHERE `result`=? and problem_id>0";
		pdo_query($sql, $result);
		$url = "../status.php?jresult=1";
		header("Location: " . $url);
	} else if (isset($_POST['rjcid'])) {
		$rjcid = intval($_POST['rjcid']);
		$sql = "UPDATE `solution` SET `result`=1 WHERE `contest_id`=? and problem_id>0";
		pdo_query($sql, $rjcid);
		$url = "../status.php?cid=" . ($rjcid);
		header("Location: " . $url);
	} else if (isset($_POST['dlsid'])) {
		$dlsid = intval($_POST['dlsid']);
		$sql = "DELETE FROM `solution` where `solution_id` = ?";
		pdo_query($sql, $dlsid);
		$url = "rejudge.php?status=1";
		header("Location: " . $url);
	}
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
	exit(0);
}
$banner = isset($_GET["status"]);
?>
<?php require("admin-header.php"); ?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">
	<?php include("../template/css.php"); ?>
	<title><?php echo $OJ_NAME ?></title>
</head>

<body>
	<div class='container'>
		<?php include("../template/nav.php") ?>
		<div class='jumbotron'>
			<div class='row lg-container'>
				<?php require_once("sidebar.php") ?>
				<div class='col-md-10 p-0'>
					<div class="container">
						<h3 class='center'><b><?php echo $MSG_REJUDGE ?></b></h3>
						<?php if ($banner) { ?>
							<div class="row">
								<div class="col-sm-4"></div>
								<div class="alert alert-success center col-sm-4" role="alert"><?php echo $MSG_SUCCESS ?></div>
								<div class="col-sm-4"></div>
							</div>
						<?php  } ?>
						<br>
						<div>
							<div class='center form-horizontal'>
								<form action='rejudge.php' method=post class='form-group'>
									<label class='control-label col-sm-4'>
										<?php echo $MSG_PROBLEM ?>
									</label>
									<div class='col-sm-4'>
										<input type=input class='form-control' name='rjpid' placeholder="1001">
										<input type='hidden' name='do' value='do'>
										<?php include("../include/set_post_key.php") ?>
										<input type=submit class='form-control btn btn-default ud-margin' value='<?php echo $MSG_SUBMIT; ?>'>
									</div>
								</form>
								<br>
								<form action='rejudge.php' method=post class='form-group'>
									<label class='control-label col-sm-4'>
										<?php echo $MSG_SUBMIT ?>
									</label>
									<div class='col-sm-4'>
										<input type=input class='form-control' name='rjsid' style='%' placeholder="1001" value='<?php if (isset($_GET['sid'])) echo $_GET['sid'] ?>'>
										<input type='hidden' name='do' value='do'>
										<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
										<input type=submit class='form-control btn btn-default ud-margin' value='<?php echo $MSG_SUBMIT; ?>'>
									</div>
								</form>
								<br>
								<form action='rejudge.php' method=post class='form-group'>
									<label class='control-label col-sm-4'>
										<?php echo $MSG_STUCK_IN_RUNNING ?>
									</label>
									<div class='col-sm-4'>
										<input type=input class='form-control' name='result' placeholder="3" value="3">
										<input type='hidden' name='do' value='do'>
										<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
										<input type=submit class='form-control btn btn-default ud-margin' value='<?php echo $MSG_SUBMIT; ?>'>
									</div>
								</form>
								<br>
								<form action='rejudge.php' method=post class='form-group'>
									<label class='control-label col-sm-4'>
										<?php echo $MSG_CONTEST ?>
									</label>
									<div class='col-sm-4'>
										<input type=input class='form-control' name='rjcid' placeholder="1003">
										<input type='hidden' name='do' value='do'>
										<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
										<input type=submit class='form-control btn btn-default ud-margin' value='<?php echo $MSG_SUBMIT; ?>'>
									</div>
								</form>
								<form action='rejudge.php' method=post class='form-group'>
									<label class='control-label col-sm-4 red'>
										<?php echo $MSG_DELETE ?>
									</label>
									<div class='col-sm-4'>
										<input type=input class='form-control' name='dlsid' placeholder="1003" value="<?php if (isset($_GET['sid'])) echo $_GET['sid'] ?>">
										<input type='hidden' name='do' value='do'>
										<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
										<input type=submit class='form-control btn btn-default ud-margin' value='<?php echo $MSG_SUBMIT; ?>'>
									</div>
								</form>
							</div>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>
	</div>
	<?php require_once("../template/js.php"); ?>
</body>

</html>