<?php 
require_once("admin-header.php"); 

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
    || isset($_SESSION[$OJ_NAME . '_' . 'source_browswer']))) {
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
				<div class='col-md-10 p-0'>
					<?php if (isset($_POST['do'])) {
						require_once("../include/check_post_key.php");
						$from = $_POST['from'];
						$to = $_POST['to'];
						$start = intval($_POST['start']);
						$end = intval($_POST['end']);
						$sql = "update `solution` set `user_id`=? where `user_id`=? and problem_id>=? and problem_id<=? and result=4";
						//echo $sql;
						echo pdo_query($sql, $to, $from, $start, $end) . " source file given!";
					}
					?>
					<div class="container">
						<h3 class='center'><?php echo $MSG_GIVESOURCE ?></h3>
						<br>
						<form action='source_give.php' method=post class='form-horizontal'>
							<div class='form-group'>
								<label class='control-label col-sm-2 col-sm-offset-2'>
									<?php echo $MSG_FROM . " " . $MSG_USER ?>
								</label>
								<div class='col-sm-4'>
									<input type=input class='form-control' name='from' placeholder="1001">
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-2 col-sm-offset-2'>
									<?php echo $MSG_TO . " " . $MSG_USER ?>
								</label>
								<div class='col-sm-4'>
									<input type=input class='form-control' name='to' placeholder="1002">
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-2 col-sm-offset-2'>
									<?php echo $MSG_FROM . " " . $MSG_PROBLEM_ID ?>
								</label>
								<div class='col-sm-4'>
									<input type=input class='form-control' name='start' placeholder="1001">
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-2 col-sm-offset-2'>
									<?php echo $MSG_TO . " " . $MSG_PROBLEM_ID ?>
								</label>
								<div class='col-sm-4'>
									<input type=input class='form-control' name='end' placeholder="1002">
								</div>
							</div>
							<div class='form-group'>
								<div class='col-sm-offset-4 col-sm-4'>
									<input type='hidden' name='do' value='do'>
									<?php require_once("../include/set_post_key.php"); ?>
									<input type=submit class='form-control' name='do' value='<?php echo $MSG_GIVESOURCE ?>'>
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