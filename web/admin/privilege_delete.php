<?php require_once("admin-header.php"); ?>
<?php require_once("../include/check_get_key.php");
if (isset($_GET['uid'])) {
	$user_id = $_GET['uid'];
	$rightstr = $_GET['rightstr'];
	$sql = "delete from `privilege` where user_id=? and rightstr=?";
	$rows = pdo_query($sql, $user_id, $rightstr);
	echo "$user_id $rightstr deleted!";
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
					<script language=javascript>
						window.setTimeOut(1000, "history.go(-1)");
					</script>
					<br>
				</div>
			</div>
		</div>
	</div>
	<?php require_once("../template/js.php"); ?>
</body>

</html>