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
		<?php echo $OJ_NAME ?> <?php echo $OJ_BBS ?>
	</title>

	<?php include("template/$OJ_TEMPLATE/css.php"); ?>


	<?php if (isset($OJ_MATHJAX) && $OJ_MATHJAX) { ?>
		<!--以下为了加载公式的使用而既加入-->
		<script>
			MathJax = {
				tex: {
					inlineMath: [
						['$', '$'],
						['\\(', '\\)']
					]
				}
			};
		</script>
		<script id="MathJax-script" async src="<?php echo $OJ_CDN_URL . "template/$OJ_TEMPLATE/" ?>tex-chtml.js"></script>
		<style>
			.jumbotron1 {
				font-size: 18px;
			}
		</style>

	<?php
	} ?>

	<!--数学公式js加载完毕-->
</head>


<body>
	<div class="container">
		<?php include("template/$OJ_TEMPLATE/nav.php"); ?>

		<!-- Main component for a primary marketing message or call to action -->
		<!-- <div class="jumbotron"></div> -->
		<div class='jumbotron'>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php
					if ($pr_flag) {
						echo "<title>$MSG_PROBLEM" . $row['problem_id'] . "--" . $row['title'] . "</title>";
						echo "<center><h3>$id: " . $row['title'] . "</h3></center>";
						echo "<div align=right><sub>[$MSG_Creator : <span id='creator'></span>]</sub></div>";
					} else {
						//$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
						$id = $row['problem_id'];
						echo "<title>$MSG_PROBLEM " . $PID[$pid] . ": " . $row['title'] . " </title>";
						echo "<center><h3>$MSG_PROBLEM " . $PID[$pid] . ": " . $row['title'] . "</h3><center>";
						echo "<div align=right><sub>[$MSG_Creator : <span id='creator'></span>]</sub></div>";
					}
					echo "<center>";
					echo "<span class=green>$MSG_Time_Limit : </span><span><span fd='time_limit' pid='" . $row['problem_id'] . "'  >" . $row['time_limit'] . "</span></span> sec&nbsp;&nbsp;";
					echo "<span class=green>$MSG_Memory_Limit : </span>" . $row['memory_limit'] . " MB";
					if ($row['spj']) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
					echo "<br><br>";
					echo "<div class='btn-group' role='group'>";
					if ($pr_flag) {
						echo "<a class='btn btn-info btn-sm' href='submitpage.php?id=$id' role='button'>$MSG_SUBMIT</a>";
					} else {
						echo "<a class='btn btn-info btn-sm' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' role='button'>$MSG_SUBMIT</a>";
						if (isset($s_id)) {
							echo "<a class='btn btn-info btn-sm' href='submitpage.php?cid=$cid&pid=$pid&sid=$s_id&langmask=$langmask' role='button'>同步历史提交</a>";
						}
						echo "<a class='btn btn-primary btn-sm' role='button' href='contest.php?cid=$cid'>$MSG_CONTEST-$MSG_LIST</a>";
						if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
							echo "<a class='btn btn-primary btn-sm' role='button' href=problem.php?id=$id>离开作业</a>";
						}
					}
					if (isset($OJ_OI_MODE) && $OJ_OI_MODE) {
					} else {
						if ($pr_flag) {
							echo "<a class='btn btn-primary btn-sm' role='button' href=status.php?problem_id=" . $row['problem_id'] . "&jresult=4>$MSG_SOVLED: " . $row['accepted'] . "</a>";
							echo "<a class='btn btn-primary btn-sm' role='button' href=status.php?problem_id=" . $row['problem_id'] . ">$MSG_SUBMIT_NUM: " . $row['submit'] . "</a>";
							echo "<a class='btn btn-primary btn-sm' role='button' href=problemstatus.php?id=" . $row['problem_id'] . ">$MSG_STATISTICS</a>";
						} else {
							echo "<a class='btn btn-primary btn-sm' role='button' href=status.php?cid=$cid&problem_id=" . $PID[$pid] . "&jresult=4>$MSG_SOVLED: " . $row['accepted'] . "</a>";
							echo "<a class='btn btn-primary btn-sm' role='button' href=status.php?cid=$cid&problem_id=" . $PID[$pid] . ">$MSG_SUBMIT_NUM: " . $row['submit'] . "</a>";
							echo "<a class='btn btn-primary btn-sm' role='button' href=problemstatus.php?cid=$cid&id=" . $PID[$pid] . ">$MSG_STATISTICS</a>";
						}
					}
					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
						require_once("include/set_get_key.php");
						echo "<a class='btn btn-success btn-sm' role='button' href=admin/problem_edit.php?id=$id&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">编辑</a>";
						echo "<a class='btn btn-success btn-sm' role='button' href=javascript:phpfm(" . $row['problem_id'] . ")>测试数据</a>";
					}
					echo "</div>";
					echo "</center>";
					# end of head
					echo "</div>";
					echo "<!--StartMarkForVirtualJudge-->";
					?>

					<div class="panel panel-body">
						<div class='panel panel-default'>
							<div class='panel-heading'>
								<h4>
									<?php echo $MSG_Description ?>&nbsp;
									<a class='btn btn-sm btn-info' href="javascript:CopyToClipboard($('.describe').text())" style='padding:3px 5px;'>复制</a>
								</h4>
							</div>
							<div class='panel-body content'>
								<div class='describe'><?php echo $row['description'] ?></div>
							</div>
						</div>

						<?php
						if ($row['input']) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										<?php echo $MSG_Input ?>
									</h4>
								</div>
								<div class='panel-body content'>
									<?php echo $row['input'] ?>
								</div>
							</div>
						<?php
						}
						if ($row['output']) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										<?php echo $MSG_Output ?>
									</h4>
								</div>
								<div class='panel-body content'>
									<?php echo $row['output'] ?>
								</div>
							</div>
						<?php
						}
						$sinput = str_replace("<", "<", $row['sample_input']);
						$sinput = str_replace(">", ">", $sinput);
						$soutput = str_replace("<", "<", $row['sample_output']);
						$soutput = str_replace(">", ">", $soutput);
						if (strlen($sinput)) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										<?php echo $MSG_Sample_Input ?>&nbsp;
										<a class='btn btn-sm btn-info' href="javascript:CopyToClipboard($('#sampleinput').text())" style='padding:3px 5px;'>复制</a>
									</h4>
								</div>
								<div class='panel-body'>
									<pre class=content><span id="sampleinput" class=sampledata><?php echo $sinput ?></span></pre>
								</div>
							</div>
						<?php
						}
						if (strlen($soutput)) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										<?php echo $MSG_Sample_Output ?>&nbsp;
										<a class='btn btn-sm btn-info' href="javascript:CopyToClipboard($('#sampleoutput').text())" style='padding:3px 5px;'>复制</a>
									</h4>
								</div>
								<div class='panel-body'>
									<pre class=content><span id='sampleoutput' class=sampledata><?php echo $soutput ?></span></pre>
								</div>
							</div>
						<?php
						}
						if ($row['hint']) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										<?php echo $MSG_HINT ?>
									</h4>
								</div>
								<div class='panel-body content'>
									<?php echo $row['hint'] ?>
								</div>
							</div>
						<?php
						}
						if ($row['blank'] != NULL) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										代码填空&nbsp;
										<a class='btn btn-sm btn-info' href="javascript:CopyToClipboard($('.blank-code').text())" style='padding:3px 5px;'>复制</a>
									</h4>
								</div>
								<div class='panel-body content' style='padding:10px;'>
									<pre id='code' class="blank-code" style='padding:15px!important;'><?php echo str_replace("%*%", "__________", str_replace("*%*\r\n", "...\r\n...\r\n", htmlentities($row["blank"], ENT_QUOTES, "UTF-8"))) ?></pre>
								</div>
							</div>
						<?php
						}
						if ($row['allow'] != NULL or $row['block'] != NULL) { ?>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<h4>
										禁用/必用关键词
									</h4>
								</div>
								<div class='panel-body content'>
									<?php if ($row['block'] != NULL) { ?>
										<div style='margin-top:10px;'>禁用: <span style='margin:0px 2px 0px 2px' class='label label-danger'><?php echo str_replace(" ", "</span>&nbsp;<span style='margin:0px 2px 0px 2px' class='label label-danger'>", $row['block']); ?></span></div>
									<?php }
									if ($row['allow'] != NULL) { ?>
										<div style='margin-top:10px;'>必用: <span style='margin:0px 2px 0px 2px' class='label label-success'><?php echo str_replace(" ", "</span><span style='margin:0px 2px 0px 2px' class='label label-success'>", $row['allow']); ?></span></div>
									<?php } ?>
								</div>
							</div>
						<?php
						}
						if ($pr_flag) { ?>
							<?php if ($row['source'] != '') { ?>
								<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4>
											<?php echo $MSG_SOURCE ?>
										</h4>
									</div>

									<div fd="source" style='word-wrap:break-word;' pid=<?php echo $row['problem_id'] ?> class='panel-body content'>
										<?php
										$cats = explode(" ", $row['source']);
										foreach ($cats as $cat) {
											if ($cat == "") continue;
											$hash_num = hexdec(substr(md5($cat), 0, 7));
											$label_theme = $color_theme[$hash_num % count($color_theme)];
											if ($label_theme == "") $label_theme = "default";
											echo "<a class='label label-$label_theme' style='display: inline-block;' href='problemset.php?search=" . urlencode(htmlentities($cat, ENT_QUOTES, 'utf-8')) . "'>" . htmlentities($cat, ENT_QUOTES, 'utf-8') . "</a>&nbsp;";
										} ?>
									</div>
								<?php } ?>

								</div>
							<?php
						} ?>

					</div>
					<center>
						<!--EndMarkForVirtualJudge-->
						<div class='panel-footer'>
							<?php
							if ($pr_flag) {
								echo "<a class='btn btn-info btn-sm' href='submitpage.php?id=$id' role='button'>$MSG_SUBMIT</a>";
							} else {
								echo "<a class='btn btn-info btn-sm' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' role='button'>$MSG_SUBMIT</a>";
							}
							if ($OJ_BBS) echo "<a class='btn btn-warning btn-sm' href='bbs.php?pid=" . $row['problem_id'] . "$ucid'>$MSG_BBS</a>";
							?>
						</div>
					</center>
				</div>
			</div>
		</div>
		<?php include("template/$OJ_TEMPLATE/js.php"); ?>
		<script>
			function phpfm(pid) {
				$.post("admin/phpfm.php", {
					'frame': 3,
					'pid': pid,
					'pass': '',
					'csrf': '<?php if (isset($token)) echo $token; ?>'
				}, function(data, status) {
					if (status == "success") {
						document.location.href = "admin/phpfm.php?frame=3&pid=" + pid;
					}
				});
			}

			$(document).ready(function() {
				$("#creator").load("problem-ajax.php?pid=<?php echo $id ?>");
			});
		</script>
</body>

</html>