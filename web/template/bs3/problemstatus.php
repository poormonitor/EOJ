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

</head>

<body>

	<div class="container">
		<?php include("template/$OJ_TEMPLATE/nav.php"); ?>

		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">

			<center>
				<h3><?php echo $MSG_PROBLEM . " : " . $id . " " . $MSG_STATISTICS ?></h3><br />
				<div class='row'>
					<div class='col-md-4'>
						<table id="statics" class="table-hover table-striped" width=90%>
							<?php
							$cnt = 0;
							foreach ($view_problem as $row) {
								if ($cnt)
									echo "<tr class='oddrow'>";
								else
									echo "<tr class='evenrow'>";
								$i = 1;
								foreach ($row as $table_cell) {
									echo "<td style='text-align:center;width:50%;'>";
									echo $table_cell;
									echo "</td>";
									$i++;
								}
								echo "</tr>";
								$cnt = 1 - $cnt;
							}
							?>

							<tr id=pie bgcolor=white>
								<td colspan=2>
									<center>
										<div id='container_pie' style='position:relative;height:200px;width:auto;margin:20px 0px 10px;'></div>
									</center>
								</td>
							</tr>
						</table>

						<br />

						<?php if (isset($view_recommand)) { ?>
							<table id=recommand class="table-hover table-striped" align=center width=90%>
								<tr>
									<td class='text-center'>
										Recommended Next Problem
										<br />
										<?php
										$cnt = 1;
										foreach ($view_recommand as $row) {
											echo "<a href=problem.php?id=$row[0]>$row[0]</a>&nbsp;";
											if ($cnt % 5 == 0)
												echo "<br />";
											$cnt++;
										}
										?>
									</td>
								</tr>
							</table>
							<br />
						<?php } ?>
					</div>
					<div class='col-md-8'>
						<div class='table-responsive'>
							<table id=problemstatus class="table-hover table-striped" align=center width=95%>
								<thead>
									<tr class=toprow>
										<th style="cursor:hand" onclick="sortTable('problemstatus', 0, 'int');" class="text-center" width=10%>
											<?php echo $MSG_Number ?>
										</th>
										<th class="text-center" width=10%>
											<?php echo $MSG_RUNID ?>
										</th>
										<th class="text-center" width=15%>
											<?php echo $MSG_USER ?>
										</th>
										<th class="text-center" width=10%>
											<?php echo $MSG_MEMORY ?>
										</th>
										<th class="text-center" width=10%>
											<?php echo $MSG_TIME ?>
										</th>
										<th class="text-center" width=10%>
											<?php echo $MSG_LANG ?>
										</th>
										<th class="text-center" width=10%>
											<?php echo $MSG_CODE_LENGTH ?>
										</th>
										<th class="text-center" width=20%>
											<?php echo $MSG_SUBMIT_TIME ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$cnt = 0;
									foreach ($view_solution as $row) {
										if ($cnt)
											echo "<tr class='oddrow'>";
										else
											echo "<tr class='evenrow'>";

										$i = 1;
										foreach ($row as $table_cell) {
											if ($i == 1 || $i == 8)
												echo "<td class='text-center'>";
											else if ($i == 2 || $i == 4 || $i == 5 || $i == 6  || $i == 7)
												echo "<td class='text-center'>";
											else
												echo "<td>";

											echo $table_cell;
											echo "&nbsp";
											echo "</td>";
											$i++;
										}

										echo "</tr>";
										$cnt = 1 - $cnt;
									}
									?>
								</tbody>
							</table>
						</div>

						<br />

						<center>
							<ul class='pagination'>
								<?php
								echo "<li class='page-item'><a href='problemstatus.php?id=$id'><< Top</a></li>";
								//echo "&nbsp;&nbsp;<a href='status.php?problem_id=$id'>[STATUS]</a>";

								if ($page > $pagemin) {
									$page--;
									echo "<li class='page-item'><a href='problemstatus.php?id=$id&page=$page'>< Prev</a></li>";
									$page++;
								}

								if ($page < $pagemax) {
									$page++;
									echo "<li class='page-item'><a href='problemstatus.php?id=$id&page=$page'>Next ></a></li>";
									$page--;
								}
								?>
							</ul>
						</center>
					</div>
				</div>
			</center>
		</div>
	</div>

	<?php include("template/$OJ_TEMPLATE/js.php"); ?>
	<script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/jquery.tablesorter.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#problemstatus").tablesorter();
		});
	</script>
	<script src="<?php echo $OJ_CDN_URL . "template/$OJ_TEMPLATE/" ?>echarts.min.js"></script>
	<script type="text/javascript">
		var info = new Array();
		var dt = document.getElementById("statics");
		var data = dt.rows;
		var n;
		var m;
		var rate;
		var total = parseInt(dt.rows[0].cells[1].innerText);
		for (var i = 3; dt.rows[i].id != "pie"; i++) {
			m = dt.rows[i].cells[0].innerHTML
			n = dt.rows[i].cells[1];
			n = n.innerText || n.textContent;
			n = parseInt(n);
			rate = Math.round(n / total * 1000) / 10;
			info.push({
				name: m + ` (${rate}%)`,
				value: n
			});
		}
		var pieChart = echarts.init(document.getElementById('container_pie'));
		var pieOption = {
			grid: {
				left: '1%',
				right: '1%',
				bottom: '1%',
				containLabel: true
			},
			tooltip: {
				trigger: 'item'
			},
			textStyle: {
				fontFamily: "SourceHanSansCN-Medium"
			},
			series: [{
				radius: ["40%", "80%"],
				itemStyle: {
					borderRadius: 10,
					borderColor: '#fff',
					borderWidth: 2
				},
				type: 'pie',
				data: info
			}]
		};
		pieChart.setOption(pieOption);
		window.onresize = function() {
			pieChart.resize();
		};
	</script>
</body>

</html>