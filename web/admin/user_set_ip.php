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
				<div class='col-md-10'>
					<center>
						<h3><?php echo $MSG_SET_LOGIN_IP ?></h3>
					</center>

					<div class='container'>

						<?php
						require_once("../include/set_get_key.php");
						$sql = "";
						if (isset($_POST['do'])) {
							require_once("../include/check_post_key.php");

							$user_id = $_POST['user_id'];
							$ip = $_POST['ip'];

							$user_id = stripslashes($user_id);
							$ip = stripslashes($ip);

							$sql = "insert into loginlog (user_id,password,ip,time) value(?,?,?,now())";
							$result = pdo_query($sql, $user_id, "set ip by " . $_SESSION[$OJ_NAME . "_user_id"], $ip);
							echo "<center><h4 class='text-danger'>User " . $_POST['user_id'] . "'s Login IP Changed!</h4></center>";
						}
						?>
						<br>
						<form action=user_set_ip.php method=post class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo $MSG_USER_ID ?></label>
								<?php if (isset($_GET['uid'])) { ?>
									<div class="col-sm-4"><input name="user_id" class="form-control" value="<?php echo $_GET['uid'] ?>" type="text" required></div>
								<?php } else if (isset($_POST['user_id'])) { ?>
									<div class="col-sm-4"><input name="user_id" class="form-control" value="<?php echo $_POST['user_id'] ?>" type="text" required></div>
								<?php } else { ?>
									<div class="col-sm-4"><input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID . "*" ?>" type="text" required></div>
								<?php } ?>
							</div>

							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo "New IP" ?></label>
								<?php if (isset($_POST['ip'])) { ?>
									<div class="col-sm-4"><input name="ip" class="form-control" value="<?php echo $_POST['ip'] ?>" type="text" autocomplete="off" required></div>
								<?php } else { ?>
									<div class="col-sm-4"><input name="ip" class="form-control" placeholder="<?php echo "?.?.?.?*" ?>" type="text" autocomplete="off" required></div>
								<?php } ?>
							</div>

							<div class="form-group">
								<?php require_once("../include/set_post_key.php"); ?>
								<div class="col-sm-offset-4 col-sm-2">
									<button name="do" type="hidden" value="do" class="btn btn-default btn-block"><?php echo $MSG_SAVE; ?></button>
								</div>
								<div class="col-sm-2">
									<button name="submit" type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET ?></button>
								</div>
							</div>
						</form>

					</div>
					<br>
				</div>
			</div>
		</div>
	</div>
	<?php require_once("../template/js.php"); ?>
</body>

</html>