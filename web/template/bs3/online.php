<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">

	<title><?php echo $OJ_NAME ?></title>
	<?php include("template/$OJ_TEMPLATE/css.php"); ?>



</head>

<body>

	<div class="container">
		<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<center>
				<h3>当前在线用户: <?php echo $on->get_num() ?></h3>
				<div class='table-responsive'>
					<table style="margin:auto;width:95%" class='table table-condensed'>
						<thead>
							<tr class=toprow>
								<th style="width: 50px">IP</th>
								<th>URI</th>
								<th>Refer</th>
								<th style="width:100px">Stay time</th>
								<th>User Agent</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($users as $u) {
								if (is_array($u)) {
							?>
									<tr>
										<td class="ip">
											<?php $l = $ip->getlocation($u['ip']);
											// echo $u->ip.'<br />';
											echo $u['ip'] . "<br>";
											if (strlen(trim($l['area'])) == 0)
												echo $l['country'];
											else
												echo $l['area'] . ' ' . $l['country'];
											?></td>
										<td><?php echo $u["uri"] ?></td>
										<td><?php echo $u['refer'] ?></td>
										<td class="time"><?php echo sprintf("%dmin %dsec", ($u['lastmove'] - $u['firsttime']) / 60, ($u['lastmove'] - $u['firsttime']) % 60) ?></td>
										<td><?php echo $u['ua'] ?></td>
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
								<th>UserID</th>
								<th>Password</th>
								<th>IP</th>
								<th>Time</th>
								<th>IP info</th>
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
			</center>

			<!-- Bootstrap core JavaScript
    ================================================== -->
			<!-- Placed at the end of the document so the pages load faster -->
			<?php include("template/$OJ_TEMPLATE/js.php"); ?>
</body>

</html>