<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">

	<title><?php echo $MSG_LOGIN . " - " . $OJ_NAME ?></title>
	<?php include("template/css.php"); ?>

</head>

<body>
	<div class="container">
		<?php include("template/nav.php"); ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<div>
				<h3 align='center' style="margin-bottom: 20px;"><?php echo $MSG_LOGIN ?></h3>
				<form id="login" action="login.php" method="post" role="form" class="form-horizontal" onSubmit="return jsMd5();">
					<div class="form-group">
						<label class="col-sm-4 control-label"><?php echo $MSG_USER_ID ?></label>
						<div class="col-sm-4"><input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID ?>" type="text" required></div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><?php echo $MSG_PASSWORD ?></label>
						<div class="col-sm-4">
							<input id="passwd" class="form-control" placeholder="<?php echo $MSG_PASSWORD ?>" type="password" required>
						</div>
					</div>
					<input name='password' type='hidden' class='form-control'>
					<?php if ($OJ_VCODE) { ?>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo $MSG_VCODE ?></label>
							<div class="col-sm-4">
								<div class="col-xs-8" style='padding:0px;'><input name="vcode" class="form-control" type="text"></div>
								<div class="col-xs-4"><img id="vcode-img" alt="click to change" style='float:right;' onclick="this.src='vcode.php?'+Math.random()"></div>
							</div>
						</div>
					<?php } ?>
					<div style='text-align:center;'>
						<span id='footer'>
							<?php echo $MSG_AGREE_POLICY ?>
						</span>
						<br><br>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-2">
							<button name="submit_btn" type="submit" class="btn btn-default btn-block"><?php echo $MSG_LOGIN; ?></button>
						</div>
						<div class="col-sm-2">
							<a class="btn btn-default btn-block" href="lostpassword.php"><?php echo $MSG_LOST_PASSWORD; ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include("template/js.php"); ?>
	<script src="<?php echo $OJ_CDN_URL ?>include/md5-min.js"></script>
	<script>
		function jsMd5() {
			if ($("input[id=passwd]").val() == "") return false;
			$("input[name=password]").val(hex_md5($("input[id=passwd]").val()));
			return true;
		}
		<?php if ($OJ_VCODE) { ?>
			$(document).ready(function() {
				$("#vcode-img").attr("src", "vcode.php?" + Math.random());
			})
		<?php } ?>
	</script>

</body>

</html>
