<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME?>">
	<link rel="shortcut icon" href="/favicon.ico">

	<title><?php echo $OJ_NAME ?></title>
	<?php include("template/css.php"); ?>

</head>

<body>

	<div class="container">
		<?php include("template/nav.php"); ?>
		<div class="jumbotron">
			<center>
				<h3>当前在线用户: <?php echo $on->get_num() ?></h3>
				<div class='table-responsive'>
					<table style="margin:auto;width:95%" class='table table-condensed'>
						<thead>
							<tr class=toprow>
								<th>IP</th>
								<th>URI</th>
								<th>Refer</th>
								<th>Stay time</th>
								<th>User ID</th>
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
											<?php $l = $ip->getlocation($u['ip']);
											// echo $u->ip.'<br>';
											echo $u['ip'] . "<br>";
											if (strlen(trim($l['area'])) == 0)
												echo $l['country'];
											else
												echo $l['area'] . ' ' . $l['country'];
											?></td>
										<td><?php echo $u["uri"] ?></td>
										<td><?php echo $u['refer'] ?></td>
										<td class="time"><?php echo sprintf("%dmin %dsec", ($u['lastmove'] - $u['firsttime']) / 60, ($u['lastmove'] - $u['firsttime']) % 60) ?></td>
										<td>
											<?php if ($ua[0] != "guest") { ?>
												<a target="view_window" href="userinfo.php?user=<?php echo $ua[0] ?>">
													<?php echo $ua[0] ?>
												</a>
											<?php } else { ?>
												<?php echo $ua[0] ?>
											<?php } ?>
										</td>
										<td><?php echo $ua[1] ?></td>
									</tr>
							<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<div style='margin:8px;' class="form-inline">
					<?php
					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
					?>
						<form>IP
							<input type='text' class="form-control" name='search' style='margin:5px;'>
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
								$ipinfo = $ip->getlocation($row[2]);
								echo '<td style="text-align:center;">' . $ipinfo['area'] . ' ' . $ipinfo['country'] . '</td>';
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
				<div style='margin:8px;' class="form-inline">
					<form method="POST"><?php echo $MSG_IP_MNGT ?>
						<input type='text' class="form-control" name='add' style='margin:5px;'>
						<?php require_once("./include/set_post_key.php") ?>
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
			</center>
		</div>
	</div>
	<script>
		function deleteIP(ip) {
			$.post("online.php", {
				del: ip,
				postkey: "<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>"
			}, function(data, status) {
				if (data == "success") {
					$("tr[data-ip='" + ip + "']").fadeOut("slow", function() {
						$("tr[data-ip='" + ip + "']").remove()
					})
				}
			})
		}
	</script>
	<?php include("template/js.php"); ?>
</body>

</html>