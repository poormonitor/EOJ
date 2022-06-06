<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keyword" content="<?php echo str_replace(" ", "", $row['source']) ?>">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">

	<title>
		<?php echo $OJ_NAME ?>
	</title>

	<?php include("template/css.php"); ?>

	<link rel="stylesheet" href="<?php echo $OJ_CDN_URL . "katex/" ?>katex.min.css">
	<script defer src="<?php echo $OJ_CDN_URL . "katex/" ?>katex.min.js"></script>
	<script defer src="<?php echo $OJ_CDN_URL . "katex/" ?>contrib/auto-render.min.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			renderMathInElement(document.body, {
				// customised options
				// • auto-render specific keys, e.g.:
				delimiters: [{
						left: '$$',
						right: '$$',
						display: true
					},
					{
						left: '$',
						right: '$',
						display: false
					},
					{
						left: '\\(',
						right: '\\)',
						display: false
					},
					{
						left: '\\[',
						right: '\\]',
						display: true
					}
				],
				// • rendering keys, e.g.:
				throwOnError: false
			});
		});
	</script>
	<style>
		.jumbotron1 {
			font-size: 18px;
		}
	</style>
</head>


<body>
	<div class="container">
		<?php include("template/nav.php"); ?>

		<!-- Main component for a primary marketing message or call to action -->
		<!-- <div class="jumbotron"></div> -->
		<div class='jumbotron'>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php
					if ($pr_flag) {
						echo "<title>$MSG_PROBLEM" . $row['problem_id'] . "--" . $row['title'] . "</title>";
						echo "<center><h3>$id: " . $row['title'] . "</h3></center>";
						echo "<div align=right><sub>[$MSG_Creator : <span id='creator'><a href='userinfo.php?user=" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "'>" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "</a></span>]</sub></div>";
					} else {
						//$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
						$id = $row['problem_id'];
						echo "<title>$MSG_PROBLEM " . $PID[$pid] . ": " . $row['title'] . " </title>";
						echo "<center><h3>$MSG_PROBLEM " . $PID[$pid] . ": " . $row['title'] . "</h3><center>";
						echo "<div align=right><sub>[$MSG_Creator : <span id='creator'><a href='userinfo.php?user=" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "'>" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "</a></span>]</sub></div>";
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
						echo "<a class='btn btn-success btn-sm' role='button' href=admin/problem_edit.php?id=$id&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">$MSG_EDIT</a>";
						echo "<a class='btn btn-success btn-sm' role='button' href=javascript:phpfm(" . $row['problem_id'] . ")>$MSG_TESTDATA</a>";
					}
					echo "</div>";
					echo "</center>";
					# end of head
					echo "</div>";
					echo "<!--StartMarkForVirtualJudge-->";
					?>
					<div class="panel-body with-footer">
						<div class="row">
							<div class="col-md-9">
								<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4>
											<?php echo $MSG_Description ?>&nbsp;
											<a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('.describe').text())"><?php echo $MSG_COPY ?></a>
										</h4>
									</div>
									<div class='panel-body content'>
										<div class='describe'><?php echo $row['description'] ?></div>
									</div>
								</div>
								<div class="row">
									<?php
									if ($row['input']) { ?>
										<div class="col-md-6">
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
										</div>
									<?php
									}
									if ($row['output']) { ?>
										<div class="col-md-6">
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
										</div>
									<?php } ?>
								</div>
								<div class="row">
									<?php
									$sinput = str_replace("<", "<", $row['sample_input']);
									$sinput = str_replace(">", ">", $sinput);
									$soutput = str_replace("<", "<", $row['sample_output']);
									$soutput = str_replace(">", ">", $soutput);
									if (strlen($sinput)) { ?>
										<div class="col-md-6">
											<div class='panel panel-default'>
												<div class='panel-heading'>
													<h4>
														<?php echo $MSG_Sample_Input ?>&nbsp;
														<a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('#sampleinput').text())"><?php echo $MSG_COPY ?></a>
													</h4>
												</div>
												<div class='panel-body'>
													<pre class=content><span id="sampleinput" class=sampledata><?php echo $sinput ?></span></pre>
												</div>
											</div>
										</div>
									<?php
									}
									if (strlen($soutput)) { ?>
										<div class="col-md-6">
											<div class='panel panel-default'>
												<div class='panel-heading'>
													<h4>
														<?php echo $MSG_Sample_Output ?>&nbsp;
														<a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('#sampleoutput').text())"><?php echo $MSG_COPY ?></a>
													</h4>
												</div>
												<div class='panel-body'>
													<pre class=content><span id='sampleoutput' class=sampledata><?php echo $soutput ?></span></pre>
												</div>
											</div>
										</div>
									<?php
									} ?>
								</div>
								<?php
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
								<?php } ?>
								<?php if ($row['blank']) { ?>
									<div class='panel panel-default'>
										<div class='panel-heading'>
											<h4>
												<?php echo $MSG_BLANK_FILLING ?>
												<a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('.blank-code').text())"><?php echo $MSG_COPY; ?></a>
											</h4>
										</div>
										<div class='panel-body content' style='padding:10px;'>
											<pre id='code' class="blank-code" style='padding:15px!important;'><?php echo $blank; ?></pre>
										</div>
									</div>
								<?php } ?>
							</div>
							<div class="col-md-3">

								<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4>
											<?php echo $MSG_AB_KEYWORD ?>
										</h4>
									</div>
									<div class='panel-body content'>
										<?php if ($row['allow'] || $row['block']) { ?>
											<?php if ($row['block']) { ?>
												<div style='margin-top:10px;'><?php echo $MSG_ALLOW_KEYWORD ?>: <span class='label label-danger'><?php echo $block; ?></span></div>
											<?php }
											if ($row['allow']) { ?>
												<div style='margin-top:10px;'><?php echo $MSG_REQUIRED_KEYWORD ?>: <span class='label label-success'><?php echo $allow; ?></span></div>
											<?php } ?>
										<?php } else {
											echo $MSG_EMPTY;
										} ?>
									</div>
								</div>
								<?php if ($pr_flag) { ?>
									<div class='panel panel-default'>
										<div class='panel-heading'>
											<h4>
												<?php echo $MSG_SOURCE ?>
											</h4>
										</div>

										<div fd="source" style='word-wrap:break-word;' pid=<?php echo $row['problem_id'] ?> class='panel-body content'>
											<?php if ($row['source']) { ?>
												<?php
												$cats = explode(" ", $row['source']);
												foreach ($cats as $cat) {
													if ($cat == "") continue;
													$hash_num = hexdec(substr(md5($cat), 0, 7));
													$label_theme = $color_theme[$hash_num % count($color_theme)];
													if ($label_theme == "") $label_theme = "default";
													echo "<a class='label label-$label_theme' style='display: inline-block;' href='problemset.php?search=" . urlencode(htmlentities($cat, ENT_QUOTES, 'utf-8')) . "'>" . htmlentities($cat, ENT_QUOTES, 'utf-8') . "</a>&nbsp;";
												} ?>
											<?php } else {
												echo $MSG_EMPTY;
											} ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<!--EndMarkForVirtualJudge-->
					<div class='panel-footer'>
						<center>
							<?php
							if ($pr_flag) {
								echo "<a class='btn btn-info btn-sm' href='submitpage.php?id=$id' role='button'>$MSG_SUBMIT</a>";
							} else {
								echo "<a class='btn btn-info btn-sm' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' role='button'>$MSG_SUBMIT</a>";
							}
							?>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include("template/js.php"); ?>
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
	</script>
</body>

</html>