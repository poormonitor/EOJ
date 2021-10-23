<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">

	<title>
		<?php echo $OJ_NAME ?>
	</title>

	<?php include("template/$OJ_TEMPLATE/css.php"); ?>

	<?php
	function formatTimeLength($length)
	{
		$hour = 0;
		$minute = 0;
		$second = 0;
		$result = '';

		global $MSG_SECONDS, $MSG_MINUTES, $MSG_HOURS, $MSG_DAYS;

		if ($length >= 60) {
			$second = $length % 60;

			if ($second > 0 && $second < 10) {
				$result = '0' . $second . ' ' . $MSG_SECONDS;
			} else if ($second > 0) {
				$result = $second . ' ' . $MSG_SECONDS;
			}

			$length = floor($length / 60);
			if ($length >= 60) {
				$minute = $length % 60;

				if ($minute == 0) {
					if ($result != '') {
						$result = '00' . ' ' . $MSG_MINUTES . ' ' . $result;
					}
				} else if ($minute > 0 && $minute < 10) {
					if ($result != '') {
						$result = '0' . $minute . ' ' . $MSG_MINUTES . ' ' . $result;
					}
				} else {
					$result = $minute . ' ' . $MSG_MINUTES . ' ' . $result;
				}

				$length = floor($length / 60);

				if ($length >= 24) {
					$hour = $length % 24;

					if ($hour == 0) {
						if ($result != '') {
							$result = '00' . ' ' . $MSG_HOURS . ' ' . $result;
						}
					} else if ($hour > 0 && $hour < 10) {
						if ($result != '') {
							$result = '0' . $hour . ' ' . $MSG_HOURS . ' ' . $result;
						}
					} else {
						$result = $hour . ' ' . $MSG_HOURS . ' ' . $result;
					}

					$length = floor($length / 24);
					$result = $length . $MSG_DAYS . ' ' . $result;
				} else {
					$result = $length . ' ' . $MSG_HOURS . ' ' . $result;
				}
			} else {
				$result = $length . ' ' . $MSG_MINUTES . ' ' . $result;
			}
		} else {
			$result = $length . ' ' . $MSG_SECONDS;
		}
		return $result;
	}
	?>

</head>

<body>
	<div class="container">
		<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
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
							Lock Board Time: <?php echo date("Y-m-d H:i:s", $view_lock_time) ?><br />
						<?php } ?>

						<?php if ($now > $end_time) {
							echo "<span class=text-muted>$MSG_Ended</span>";
						} else if ($now < $start_time) {
							echo "<span class=text-success>$MSG_Start&nbsp;</span>";
							echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
						} else {
							echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
							echo "<span class=text-danger>$MSG_LeftTime</span>" . " " . formatTimeLength($end_time - $now);
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
								<a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo "编辑" ?></a>
							<?php } ?>
						</div>
					</div>
				</center>
			<?php } ?>

			<br>
			<?php
			$rank = 1;
			?>
			<center>
				<a href="contestrank.xls.php?cid=<?php echo $cid ?>">Download</a>
				<h4><?php if (isset($locked_msg)) echo $locked_msg; ?></h4>
				<?php
				if ($OJ_MEMCACHE) {
					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
						echo '<a href="contestrank3.php?cid=' . $cid . '">滚榜</a>';
						echo '&nbsp;<a href="contestrank2.php?cid=' . $cid . '">Replay</a><h4></h4>';
					}
				}
				?>
			</center>
			<div class='table-responsive'>
				<table id="rank" class="table-hover table-striped" align=center width=80%>
					<thead>
						<tr class='toprow'>
							<td class="{sorter:'false'} text-center"><?php echo $MSG_STANDING ?></td>
							<td class='text-center'><?php echo $MSG_USER ?></td>
							<td class='text-center'><?php echo $MSG_NICK ?></td>
							<td class='text-center'><?php echo $MSG_SOVLED ?></td>
							<td class='text-center'><?php echo $MSG_CONTEST_PENALTY ?></td>
							<td class='text-center'><?php echo "Mark" ?></td>
							<?php
							for ($i = 0; $i < $pid_cnt; $i++) {
								if (time() < $end_time) {  //during contest/exam time
									echo "<td class='text-center'><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
								} else {  //over contest/exam time

									//check the problem will be use remained contest/exam
									$sql = "SELECT `problem_id` FROM `contest_problem` WHERE (`contest_id`=? AND `num`=?)";
									$tresult = pdo_query($sql, $cid, $i);

									$tpid = $tresult[0][0];
									$sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id`=? AND `problem_id` IN (
				          SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
				            SELECT `contest_id` FROM `contest` WHERE (`defunct`='N' AND now()<`end_time`)
				          )
				        )";
									$tresult = pdo_query($sql, $tpid);

									if (intval($tresult) != 0)   //if the problem will be use remained contes/exam */
										echo "<td class='text-center'>$PID[$i]</td>";
									else
										echo "<td class='text-center'><a href='problem.php?id=" . $tpid . "'>" . $PID[$i] . "</a></td>";
								}
							}
							?>
						</tr>
					</thead>

					<tbody>
						<?php
						$cnt = 0;
						for ($i = 0; $i < $user_cnt; $i++) {
							if ($i & 1)
								echo "<tr class='oddrow'>";
							else
								echo "<tr class='evenrow'>";

							$nick = $U[$i]->nick;
							echo "<td class='text-center'>";
							if ($nick[0] != "*")
								echo $rank++;
							else
								echo "*";
							echo "</td>";

							$uuid = $U[$i]->user_id;
							if (isset($_GET['user_id']) && $uuid == $_GET['user_id'])
								echo "<td class='text-center'bgcolor=#ffff77>";
							else
								echo "<td class='text-center'>";
							echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
							echo "</td>";

							echo "<td class='text-center'><a href=userinfo.php?user=$uuid>" . htmlentities($U[$i]->nick, ENT_QUOTES, "UTF-8") . "</a></td>";

							$usolved = $U[$i]->solved;
							echo "<td class='text-center'><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a></td>";

							echo "<td class='text-center'>" . sec2str($U[$i]->time) . "</td>";
							echo "<td class='text-center'>" . ($U[$i]->total) . "</td>";


							for ($j = 0; $j < $pid_cnt; $j++) {
								$bg_color = "eeeeee";
								if (isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j] > 0) {
									$aa = 0x33 + $U[$i]->p_wa_num[$j] * 32;
									$aa = $aa > 0xaa ? 0xaa : $aa;
									$aa = dechex($aa);
									$bg_color = "$aa" . "ff" . "$aa";
									//$bg_color="aaffaa";
									if ($uuid == $first_blood[$j]) {
										$bg_color = "aaaaff";
									}
								} else if (isset($U[$i]->p_wa_num[$j]) && $U[$i]->p_wa_num[$j] > 0) {
									$aa = 0xaa - $U[$i]->p_wa_num[$j] * 10;
									$aa = $aa > 16 ? $aa : 16;
									$aa = dechex($aa);
									$bg_color = "ff$aa$aa";
								}
								echo "<td class='well' style='background-color:#$bg_color'>";
								if (isset($U[$i])) {
									if (isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j] > 0)
										echo sec2str($U[$i]->p_ac_sec[$j]);
									else if (isset($U[$i]->p_wa_num[$j]) && intval($U[$i]->p_wa_num[$j]) > 0 && isset($U[$i]->p_pass_rate[$j]))
										echo "(+" . (floatval($U[intval($i)]->p_pass_rate[intval($j)]) * 100) . ")";
									else
										echo "无提交";
								}
							}
							echo "</tr>\n";
						}
						?>
					</tbody>

				</table>
			</div>
		</div>
	</div>


	<?php include("template/$OJ_TEMPLATE/js.php"); ?>
	<script type="text/javascript" src="<?php echo $OJ_CDN_URL . $path_fix ?>/include/jquery.tablesorter.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$.tablesorter.addParser({
				//set a unique id
				id: 'punish',
				is: function(s) {
					//return false so this parser is not auto detected
					return false;
				},
				format: function(s) {
					//format your data for normalization
					var v = s.toLowerCase().replace(/\:/, '').replace(/\:/, '').replace(/\(-/, '.').replace(/\)/, '');
					//swal(v);
					v = parseFloat('0' + v);
					return v > 1 ? v : v + Number.MAX_VALUE - 1;
				},
				// set type, either numeric or text
				type: 'numeric'
			});

			$("#rank").tablesorter({
				headers: {
					4: {
						sorter: 'punish'
					}
					<?php
					for ($i = 0; $i < $pid_cnt; $i++) {
						echo "," . ($i + 5) . ": { sorter:'punish'}";
					}
					?>,
				}
			});

			<?php if ($OJ_SHOW_METAL) { ?>
				metal();
			<?php } ?>

			setTimeout(function() {
				document.location.href = '/contestrank-oi.php?cid=<?php echo $cid ?>'
			}, 60000);
		});
	</script>

	<script>
		function metal() {
			var tb = window.document.getElementById('rank');
			var rows = tb.rows;
			try {
				<?php
				//若有队伍从未进行过任何提交，数据库solution表里不会有数据，榜单上该队伍不存在，总rows数量不等于报名参赛队伍数量，奖牌比例的计算会出错
				//解决办法：可以为现场赛采用人为设定有效参赛队伍数$OJ_ON_SITE_TEAM_TOTAL，值为0时则采用榜单计算。详情见db_info.inc.php
				if ($OJ_ON_SITE_TEAM_TOTAL != 0)
					echo "var total=" . $OJ_ON_SITE_TEAM_TOTAL . ";";
				else
					echo "var total=getTotal(rows);";
				?>

				//swal(total);
				for (var i = 1; i < rows.length; i++) {
					var cell = rows[i].cells[0];
					var acc = rows[i].cells[3];
					var ac = parseInt(acc.innerText);

					if (isNaN(ac))
						ac = parseInt(acc.textContent);

					if (cell.innerHTML != "*" && ac > 0) {
						var r = parseInt(cell.innerHTML);
						if (r == 1) {
							cell.innerHTML = "Winner";

							//cell.style.cssText="background-color:gold;color:red";
							cell.className = "badge btn-warning center-block";
						}

						if (r > 1 && r <= total * .05 + 1)
							cell.className = "badge btn-warning center-block";

						if (r > total * .05 + 1 && r <= total * .20 + 1)
							cell.className = "badge center-block";

						if (r > total * .20 + 1 && r <= total * .45 + 1)
							cell.className = "badge btn-danger center-block";

						if (r > total * .45 + 1 && ac > 0)
							cell.className = "badge badge-info center-block";
					}
				}
			} catch (e) {
				//swal(e);
			}
		}

		<?php if ($OJ_SHOW_METAL) { ?>
			metal();
		<?php } ?>
		var diff = new Date("<?php echo date("Y/m/d H:i:s") ?>").getTime() - new Date().getTime();
		clock(diff);
	</script>

	<style>
		.well {
			background-image: none;
			padding: 1px;
			text-align: center;
		}

		td {
			white-space: nowrap;
		}
	</style>

</body>

</html>