<?php require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
    $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
    $error_location = "../index.php";
    require("../template/error.php");
    exit(0);
}

?>
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
				<div class='col-md-9 col-lg-10 p-0'>
					<center>
						<h3><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_ADD ?></h3>
					</center>

					<div class="container">

						<?php
						if (isset($_POST['do'])) {
							require_once("../include/check_post_key.php");

							$user_id = $_POST['user_id'];
							$rightstr = $_POST['rightstr'];
							$valuestr = "true";
							if (isset($_POST['valuestr']))
								$valuestr = $_POST['valuestr'];

							if (isset($_POST['contest']))
								$rightstr = "c$rightstr";

							if (isset($_POST['psv']))
								$rightstr = "s$rightstr";

							$sql = "insert into `privilege`(user_id,rightstr,valuestr,defunct) values(?,?,?,'N')";
							$rows = pdo_query($sql, $user_id, $rightstr, $valuestr);
							echo "<center><h4 class='text-danger'>User " . $_POST['user_id'] . "'s Privilege Added!</h4></center>";
						}
						?>

						<div>
							<form method="post" class="form-horizontal">
								<?php require_once("../include/set_post_key.php"); ?>
								<center><label class="text-info"><?php echo $MSG_HELP_ADD_PRIVILEGE ?></label></center>
								<br>
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
									<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo $MSG_PRIVILEGE_TYPE ?></label>
									<div class='col-sm-4'>
										<select class="form-control" name="rightstr" onchange="show_value_input(this.value)">

											<?php
											$rightarray = array("administrator", "problem_editor", "source_browser", "contest_creator", "http_judge", "password_setter", 'problem_start', 'problem_end');
											foreach ($rightarray as $val) {
												if (isset($rightstr) && ($rightstr == $val)) {
													echo '<option value="' . $val . '" selected>' . $val . '</option>';
												} else {
													echo '<option value="' . $val . '">' . $val . '</option>';
												}
											}
											?>
										</select>
									</div>
									<br>
									<div class="col-sm-offset-9"><input id='value_input' type="hidden" class="form-control" name="valuestr" value="true"></div>
								</div>
								<script>
									function show_value_input(new_value) {
										if (new_value == 'problem_start' || new_value == 'problem_end') {
											$("#value_input").val("1000");
											$("#value_input").show();
										} else {
											$("#value_input").val("true");
											$("#value_input").hide();
										}
									}
									$(document).ready(function() {
										$("#value_input").hide();
									});
								</script>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-2">
										<input type='hidden' name='do' value='do'>
										<button type="submit" name="do" value="do" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
									</div>
									<div class="col-sm-2">
										<button type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET ?></button>
									</div>
								</div>
							</form>
						</div>

						<br>

						<div>
							<form method="post" class="form-horizontal">
								<?php require_once("../include/set_post_key.php"); ?>
								<center><label class="text-info"><?php echo $MSG_HELP_ADD_CONTEST_USER ?></label></center>
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
									<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo $MSG_CONTEST_ID ?></label>
									<div class="col-sm-4"><input name="rightstr" class="form-control" placeholder="<?php echo $MSG_CONTEST_ID . "*" ?>" type="text"></div>
								</div>

								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-2">
										<input type='hidden' name='do' value='do'>
										<button type="submit" name="contest" value="do" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
										<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
									</div>
									<div class="col-sm-2">
										<button type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET ?></button>
									</div>
								</div>
							</form>
						</div>

						<br>

						<div>
							<form method="post" class="form-horizontal">
								<?php require_once("../include/set_post_key.php"); ?>
								<center><label class="text-info"><?php echo $MSG_HELP_ADD_SOLUTION_VIEW ?></label></center>
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
									<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo $MSG_PROBLEM_ID ?></label>
									<div class="col-sm-4"><input name="rightstr" class="form-control" placeholder="<?php echo $MSG_PROBLEM_ID . "*" ?>" type="text"></div>
								</div>

								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-2">
										<input type='hidden' name='do' value='do'>
										<button type="submit" name="psv" value="do" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
										<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
									</div>
									<div class="col-sm-2">
										<button type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET ?></button>
									</div>
								</div>
							</form>
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