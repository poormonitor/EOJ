<?php require_once("admin-header.php"); ?>
<?php require_once("../include/check_get_key.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

if (isset($_GET['uid'])) {
	$user_id = $_GET['uid'];
	$rightstr = $_GET['rightstr'];
	$sql = "delete from `privilege` where user_id=? and rightstr=?";
	$rows = pdo_query($sql, $user_id, $rightstr);

	$ip = getRealIP();
	$sql = "INSERT INTO `loginlog` VALUES (?,?,?,NOW())";
	pdo_query($sql, $user_id, "$rightstr deleted by " . $_SESSION[$OJ_NAME . "_" . "user_id"], $ip);
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
					<br>
				</div>
			</div>
		</div>
	</div>
	<?php require_once("../template/js.php"); ?>
	<script>
		swal({
			title: "<?php echo $MSG_SUCCESS ?>",
			text: "<?php echo $MSG_DELETE . " " . $user_id . " " . $rightstr ?>",
			icon: "success",
		}).then(() => {
			history.go(-1)
		})
	</script>
</body>

</html>