<?php
require("admin-header.php");
require_once('../include/online.php');

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

if (isset($_POST["add"])) {
	require("../include/check_post_key.php");
	$id = $_POST["add"];
	pdo_query("INSERT INTO `ip` (`ip`, `type`) VALUES (?, 'safe')", $id);
	header("Location: online.php");
	exit(0);
}

if (isset($_POST["del"])) {
	require("../include/check_post_key.php");
	$id = $_POST["del"];
	pdo_query("DELETE FROM `ip` WHERE `ip` = ?", $id);
	echo "success";
	exit(0);
}


$ips = pdo_query("SELECT * FROM `ip`");

$on = new online();
$view_title = $OJ_NAME;
require_once('../include/iplocation.php');
$users = $on->getAll();
$view_online = array();


$sql = "SELECT * FROM `loginlog`";
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_2 = isset($_GET['search_2']) ? $_GET['search_2'] : '';

$params = array();
if ($search != '') {
	$sql = $sql . " WHERE ip like ? ";
	$params[] = "%$search%";
}
if ($search_2 != '') {
	$sql = $sql . " WHERE password like ? ";
	$params[] = "%$search_2%";
}
if (!count($params)) {
	$sql = $sql . " where user_id<>? ";
	$params[] = $_SESSION[$OJ_NAME . '_' . 'user_id'];
}

$sql = $sql . "  order by `time` desc LIMIT 0,50";
$result = pdo_query($sql, ...$params);

$i = 0;

foreach ($result as $row) {

	$view_online[$i][0] = "<a href='../userinfo.php?user=" . htmlentities($row[0], ENT_QUOTES, "UTF-8") . "'>" . htmlentities($row[0], ENT_QUOTES, "UTF-8") . "</a>";
	$view_online[$i][1] = htmlentities($row[1], ENT_QUOTES, "UTF-8");
	$view_online[$i][2] = htmlentities($row[2], ENT_QUOTES, "UTF-8");
	$view_online[$i][3] = htmlentities($row[3], ENT_QUOTES, "UTF-8");

	$i++;
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

	<title><?php echo $OJ_NAME ?></title>
	<?php include("../template/css.php"); ?>

</head>

<body>

	<div class="container">
		<?php include("../template/nav.php"); ?>
		<div class='jumbotron'>
			<div class='row lg-container'>
				<?php require_once("sidebar.php") ?>
				<div class='col-md-9 col-lg-10 p-0'>
					<center>
						<h3><?php echo $MSG_CURRENT_ONLINE ?>: <?php echo $on->get_num() ?></h3>
						<div class="container">
							<div class='table-responsive mt-4'>
								<table style="margin:auto;width:95%" class='table table-condensed'>
									<thead>
										<tr class=toprow>
											<th>IP</th>
											<th>IP info</th>
											<th>User ID</th>
											<th>URI</th>
											<th>Refer</th>
											<th>Stay time</th>
											<th>User Agent</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($users as $u) {
											if (is_array($u)) {
												$ua = explode("@", $u['ua'], 2);
										?>
												<tr>
													<td class="ip">
														<span><?php echo $u['ip']; ?></span>
													</td>
													<td>
														<?php echo getLocationFull($u['ip']); ?>
													</td>
													<td>
														<?php if ($ua[0] != "guest") { ?>
															<a target="view_window" href="../userinfo.php?user=<?php echo $ua[0] ?>">
																<?php echo $ua[0] ?>
															</a>
														<?php } else { ?>
															<?php echo $ua[0] ?>
														<?php } ?>
													</td>
													<td><?php echo $u["uri"] ?></td>
													<td><?php echo $u['refer'] ?></td>
													<td class="time"><?php echo sprintf("%dmin %dsec", ($u['lastmove'] - $u['firsttime']) / 60, ($u['lastmove'] - $u['firsttime']) % 60) ?></td>
													<td><?php echo $ua[1] ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
								</table>
							</div>
							<div class="form-inline m-3 mt-5">
								<?php
								if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
								?>
									<form>
										<label class="m-2" for="search"> IP </label>
										<input type='text' class="form-control" name='search' value="<?php echo $search ?>">
										<label class="m-2" for="search_2"> Session ID </label>
										<input type='text' class="form-control" name='search_2' value="<?php echo $search_2 ?>">
										<input type='submit' class="form-control" value='<?php echo $MSG_SEARCH ?>'>
									</form>
							</div>
							<div class='table-responsive'>
								<table class='table table-condensed' style='width:auto;'>
									<thead>
										<tr class='toprow center'>
											<th class='center'>UserID</th>
											<th class='center'>Password</th>
											<th class='center'>IP</th>
											<th class='center'>Time</th>
											<th class='center'>IP info</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$cnt = 0;
										foreach ($view_online as $row) {
											if ($cnt)
												echo "<tr class='oddrow'>";
											else
												echo "<tr class='evenrow'>";
											foreach ($row as $table_cell) {
												echo "<td align=center>";
												echo "\t" . $table_cell . "&nbsp;";
												echo "</td>";
											}
											$ipinfo = getLocationFull($row[2]);
											echo '<td style="text-align:center;">' . $ipinfo . '</td>';
											echo "</tr>";
											$cnt = 1 - $cnt;
										}
										?>
									</tbody>
								<?php
								}
								?>
								</table>
							</div>
							<div class="form-inline m-3 mt-5">
								<form method="POST"><?php echo $MSG_IP_MNGT ?>
									<input type='text' class="form-control" name='add' style='margin:5px;'>
									<?php require_once("../include/set_post_key.php") ?>
									<input type='submit' class="form-control" value='<?php echo $MSG_ADD ?>'>
								</form>
							</div>
							<div class="table-responsive">
								<table class="table" style='width:auto;'>
									<thead>
										<th>IP</th>
										<th><?php echo $MSG_DELETE ?></th>
									</thead>
									<tbody>
										<?php foreach ($ips as $ip) { ?>
											<tr data-ip="<?php echo $ip["ip"] ?>">
												<td><?php echo $ip["ip"] ?></td>
												<td>
													<a href="javascript:deleteIP('<?php echo $ip["ip"]; ?>')">
														<?php echo $MSG_DELETE ?>
													</a>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</center>
				</div>
			</div>
		</div>
	</div>
	<?php include("../template/js.php"); ?>
	<script>
		function deleteIP(ip) {
			$.post("online.php", {
				del: ip,
				postkey: "<?php echo end($_SESSION[$OJ_NAME . '_' . 'postkey']) ?>"
			}, function(data, status) {
				if (data == "success") {
					$("tr[data-ip='" + ip + "']").fadeOut("slow", function() {
						$("tr[data-ip='" + ip + "']").remove()
					})
				}
			})
		}
	</script>
</body>

</html>