<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">

	<title>
		<?php echo $MSG_STATISTICS . " - " . $OJ_NAME ?>
	</title>

	<?php include("template/css.php"); ?>

	<style>
		td,
		th {
			padding: 0px 2px;
		}
	</style>
</head>

<body>
	<div class="container">
		<?php include("template/nav.php"); ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">

			<?php
			if (isset($_GET['cid'])) {
				$cid = intval($_GET['cid']);
				$view_cid = $cid;
				//print $cid;

				//check contest valid
				$sql = "SELECT * FROM `contest` WHERE `contest_id`=?";
				$result = pdo_query($sql, $cid);

				$rows_cnt = count($result);
				$contest_ok = true;
				$password = "";

				if (isset($_POST['password']))
					$password = $_POST['password'];

				$password = stripslashes($password);

				if ($rows_cnt == 0) {
					$view_title = "比赛已经关闭!";
				} else {
					$row = $result[0];
					$view_private = $row['private'];

					if ($password != "" && $password == $row['password'])
						$_SESSION[$OJ_NAME . '_' . 'c' . $cid] = true;

					if ($row['private'] && !isset($_SESSION[$OJ_NAME . '_' . 'c' . $cid]))
						$contest_ok = false;

					if ($row['defunct'] == 'Y')
						$contest_ok = false;

					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']))
						$contest_ok = true;

					$now = time();
					$start_time = strtotime($row['start_time']);
					$end_time = strtotime($row['end_time']);
					$view_description = $row['description'];
					$view_title = $row['title'];
					$view_start_time = $row['start_time'];
					$view_end_time = $row['end_time'];
				}
			}
			?>

			<?php if (isset($_GET['cid'])) { ?>
				<center>
					<div>
						<h3><?php echo $MSG_CONTEST_ID ?> : <?php echo $view_cid ?> - <?php echo $view_title ?></h3>
						<p>
							<?php echo $view_description ?>
						</p>
						<br>
						<?php echo $MSG_SERVER_TIME ?> : <span id=nowdate> <?php echo date("Y-m-d H:i:s") ?></span>
						<br>

						<?php if (isset($OJ_RANK_LOCK_PERCENT) && $OJ_RANK_LOCK_PERCENT != 0) { ?>
							Lock Board Time: <?php echo date("Y-m-d H:i:s", $view_lock_time) ?><br>
						<?php } ?>

						<?php if ($now > $end_time) {
							echo "<span class=text-muted>$MSG_Ended</span>";
						} else if ($now < $start_time) {
							echo "<span class=text-success>$MSG_Start&nbsp;</span>";
							echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
						} else {
							echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
							echo "<span class='text-danger'>$MSG_LeftTime</span> <span class='time-left'>" . formatTimeLength($end_time - $now) . "</span>";
						}
						?>

						<br><br>

						<?php echo $MSG_CONTEST_STATUS ?> :

						<?php
						if ($now > $end_time)
							echo "<span class=text-muted>" . $MSG_End . "</span>";
						else if ($now < $start_time)
							echo "<span class=text-success>" . $MSG_Start . "</span>";
						else
							echo "<span class=text-danger>" . $MSG_Running . "</span>";
						?>
						&nbsp;&nbsp;

						<?php echo $MSG_CONTEST_OPEN ?> :

						<?php if ($view_private == '0')
							echo "<span class=text-primary>" . $MSG_Public . "</span>";
						else
							echo "<span class=text-danger>" . $MSG_Private . "</span>";
						?>

						<br>

						<?php echo $MSG_START_TIME ?> : <?php echo $view_start_time ?>
						<br>
						<?php echo $MSG_END_TIME ?> : <?php echo $view_end_time ?>
						<br><br>

						<div class="btn-group">
							<a href="contest.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_PROBLEMS ?></a>
							<a href="status.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_SUBMIT ?></a>
							<a href="contestrank.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STANDING ?></a>
							<a href="contestrank-oi.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo "OI" . $MSG_STANDING ?></a>
							<a href="conteststatistics.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STATISTICS ?></a>
							<a href="suspect_list.php?cid=<?php echo $view_cid ?>" class="btn btn-warning btn-sm"><?php echo $MSG_IP_VERIFICATION ?></a>
							<?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
								<a href="user_set_ip.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_SET_LOGIN_IP ?></a>
								<a target="_blank" href="admin/contest_edit.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_EDIT ?></a>
							<?php } ?>
						</div>
					</div>
				</center>
			<?php } ?>
			<br>
			<center>
				<h4><?php if (isset($locked_msg)) echo $locked_msg; ?></h4>
				<div class='table-responsive'>
					<table id=cs class="table-hover table-striped" align=center width=90% border=0>
						<thead>
							<tr class=toprow>
								<th class='text-center'></th>
								<th class='text-center'>AC</th>
								<th class='text-center'>PE</th>
								<th class='text-center'>WA</th>
								<th class='text-center'>TLE</th>
								<th class='text-center'>MLE</th>
								<th class='text-center'>OLE</th>
								<th class='text-center'>RE</th>
								<th class='text-center'>CE</th>
								<th class='text-center'>TR</th>
								<th class='text-center'></th>
								<th class='text-center'>Total</th>
								<?php
								$i = 0;
								foreach ($language_name as $lang) {
									if (isset($R[$pid_cnt][$i + 11]))
										echo "<th class='text-center'>$language_name[$i]</th>";
									else
										echo "<th class='text-center'></th>";
									$i++;
								}
								?>
							</tr>
						</thead>

						<tbody>
							<?php
							for ($i = 0; $i < $pid_cnt; $i++) {
								if (!isset($PID[$i]))
									$PID[$i] = "";

								if ($i & 1)
									echo "<tr class='oddrow'>";
								else
									echo "<tr class='evenrow'>";



								if (
									isset($_SESSION[$OJ_NAME . '_' . 'administrator']) ||
									isset($_SESSION[$OJ_NAME . '_' . 'source_browser']) ||
									time() < $end_time
								) {  //during contest/exam time
									echo "<td class='text-center'><a href=problemstatus.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
								} else {  //over contest/exam time
									//check the problem will be use remained contest/exam
									$sql = "SELECT `problem_id` FROM `contest_problem` WHERE (`contest_id`=? AND `num`=?)";
									$tresult = pdo_query($sql, $cid, $i);

									$tpid = $tresult[0][0];
									$sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id`=? AND `problem_id` IN 
											(SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN 
												(SELECT `contest_id` FROM `contest` WHERE `defunct`='N' AND now()<`end_time`)
											)";
									$tresult = pdo_query($sql, $tpid);

									if (intval($tresult) != 0)   //if the problem will be use remained contes/exam */
										echo "<td class='text-center'>$PID[$i]</td>";
									else
										echo "<td class='text-center'><a href='problemstatus.php?id=" . $tpid . "'>" . $PID[$i] . "</a></td>";
								}

								for ($j = 0; $j < count($language_name) + 11; $j++) {
									if (!isset($R[$i][$j]))
										$R[$i][$j] = "";

									echo "<td class='text-center'>" . $R[$i][$j] . "</td>";
								}
								echo "</tr>";
							}

							echo "<tr class='evenrow'>";
							echo "<td class='text-center'>Total</td>";

							for ($j = 0; $j < count($language_name) + 11; $j++) {
								if (!isset($R[$i][$j]))
									$R[$i][$j] = "";

								echo "<td class='text-center'>" . $R[$i][$j] . "</td>";
							}
							echo "</tr>";

							?>
						</tbody>
					</table>
				</div>

				<br><br>

				<table>
					<div id='container_status' style="width:80%;height:300px;"></div>
				</table>

			</center>

		</div>

	</div>
	<?php include("template/js.php"); ?>
	<script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/jquery.tablesorter.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#cs").tablesorter();
		});
	</script>
	<script src="<?php echo $OJ_CDN_URL . "template/" ?>echarts.min.js"></script>
	<script type="text/javascript">
		var statusChart = echarts.init(document.getElementById('container_status'), null, {
			renderer: "svg"
		});
		var statusOption = {
			title: {
				text: "<?php echo $MSG_RECENT_SUBMISSION ?>",
			},
			legend: [{
				data: ['<?php echo $MSG_TOTAL ?>', '<?php echo $MSG_ACCEPTED ?>'],
				top: "10%",
			}],
			grid: {
				left: '1%',
				right: '1%',
				bottom: '10%',
				containLabel: true
			},
			tooltip: {
				trigger: 'axis',
				formatter: function(params) {
					var text = '--'
					if (params && params.length) {
						text = params[0].data[0]
						params.forEach(item => {
							var dotHtml = item.marker
							text += `<div style='text-align:left'>${dotHtml}${item.seriesName} : ${item.data[1] ? item.data[1] : '-'}</div>`
						})
					}
					return text
				}
			},
			xAxis: {
				type: 'time',
			},
			yAxis: {
				type: 'value'
			},
			textStyle: {
				fontFamily: "HarmonySans",
			},
			series: [{
				data: <?php echo json_encode($chart_data_all) ?>,
				type: 'line',
				name: '<?php echo $MSG_TOTAL ?>',
				color: '#4B4B4B',
				smooth: true
			}, {
				data: <?php echo json_encode($chart_data_ac) ?>,
				type: 'line',
				name: '<?php echo $MSG_ACCEPTED ?>',
				color: '#22D35E',
				smooth: true
			}]
		};
		statusChart.setOption(statusOption);
		window.onresize = function() {
			statusChart.resize();
		};
	</script>

	<script>
		var diff = new Number("<?php echo round(microtime(true) * 1000) ?>") - new Date().getTime();
		//swal(diff);
		function clock() {
			var x, h, m, s, n, xingqi, y, mon, d;
			var x = new Date(new Date().getTime() + diff);
			y = x.getYear() + 1900;

			if (y > 3000)
				y -= 1900;

			mon = x.getMonth() + 1;
			d = x.getDate();
			xingqi = x.getDay();
			h = x.getHours();
			m = x.getMinutes();
			s = x.getSeconds();
			n = y + "-" + (mon >= 10 ? mon : "0" + mon) + "-" + (d >= 10 ? d : "0" + d) + " " + (h >= 10 ? h : "0" + h) + ":" + (m >= 10 ? m : "0" + m) + ":" + (s >= 10 ? s : "0" + s);

			//swal(n);
			document.getElementById('nowdate').innerHTML = n;
			setTimeout("clock()", 1000);
		}
		setTimeout("clock()", diff > 0 ? diff % 1000 : 1000 + diff % 1000);
	</script>

</body>

</html>