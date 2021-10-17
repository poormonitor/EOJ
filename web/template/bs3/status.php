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
			<br><br>
			<div align=center class="input-append">
				<form id=simform class=form-inline action="status.php" method="get">
					<?php echo $MSG_PROBLEM_ID ?>
					<input class="form-control" type=text size=4 name=problem_id value='<?php echo  htmlspecialchars($problem_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;

					<?php echo $MSG_USER ?>
					<input class="form-control" type=text size=8 name=user_id value='<?php echo  htmlspecialchars($user_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;
					<?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>"; ?>

					<?php echo $MSG_LANG ?>
					<select class="form-control" size="1" name="language">
						<option value="-1">All</option>
						<?php
						if (isset($_GET['language'])) {
							$selectedLang = intval($_GET['language']);
						} else {
							$selectedLang = -1;
						}

						$lang_count = count($language_ext);
						$langmask = $OJ_LANGMASK;
						$lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);
						for ($i = 0; $i < $lang_count; $i++) {
							if ($lang & (1 << $i))
								echo "<option value=$i " . ($selectedLang == $i ? "selected" : "") . ">" . $language_name[$i] . "</option>";
						}
						?>
					</select>&nbsp;&nbsp;

					<?php echo $MSG_RESULT ?>
					<select class="form-control" size="1" name="jresult">
						<?php
						if (isset($_GET['jresult']))
							$jresult_get = intval($_GET['jresult']);
						else
							$jresult_get = -1;

						if ($jresult_get >= 12 || $jresult_get < 0)
							$jresult_get = -1;
						/*if ($jresult_get!=-1){
					$sql=$sql."AND `result`='".strval($jresult_get)."' ";
					$str2=$str2."&jresult=".strval($jresult_get);
					}*/
						if ($jresult_get == -1)
							echo "<option value='-1' selected>All</option>";
						else
							echo "<option value='-1'>All</option>";

						for ($j = 0; $j < 12; $j++) {
							$i = ($j + 4) % 12;
							if ($i == $jresult_get)
								echo "<option value='" . strval($jresult_get) . "' selected>" . $jresult[$i] . "</option>";
							else
								echo "<option value='" . strval($i) . "'>" . $jresult[$i] . "</option>";
						}
						?>
					</select>&nbsp;&nbsp;

					<?php
					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
						if (isset($_GET['showsim']))
							$showsim = intval($_GET['showsim']);
						else
							$showsim = 0;

						echo "SIM 
					<select id=\"appendedInputButton\" class=\"form-control\" name=showsim onchange=\"document.getElementById('simform').submit();\">
						<option value=0 " . ($showsim == 0 ? 'selected' : '') . ">All</option>
						<option value=50 " . ($showsim == 50 ? 'selected' : '') . ">50</option>
						<option value=60 " . ($showsim == 60 ? 'selected' : '') . ">60</option>
						<option value=70 " . ($showsim == 70 ? 'selected' : '') . ">70</option>
						<option value=80 " . ($showsim == 80 ? 'selected' : '') . ">80</option>
						<option value=90 " . ($showsim == 90 ? 'selected' : '') . ">90</option>
						<option value=100 " . ($showsim == 100 ? 'selected' : '') . ">100</option>
					</select>&nbsp;&nbsp;&nbsp;&nbsp;";

						/* if (isset($_GET['cid']))
					echo "<input type=hidden name=cid value='".$_GET['cid']."'>";
					if (isset($_GET['language']))
						echo "<input type=hidden name=language value='".$_GET['language']."'>";
					if (isset($_GET['user_id']))
						echo "<input type=hidden name=user_id value='".$_GET['user_id']."'>";
					if (isset($_GET['problem_id']))
						echo "<input type=hidden name=problem_id value='".$_GET['problem_id']."'>";
					//echo "<input type=submit>";
					*/
					}
					echo "<input type=submit class='form-control' value='$MSG_SEARCH'>";
					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
						echo "<a class='btn btn-default' href='statusadmin.php' role='button'style='margin:5px;' > Admin $MSG_SEARCH</a>";
					}
					?>

				</form>
			</div>
			<br>

			<div id=center class="table-responsive">
				<table id=result-tab class="table table-striped content-box-header" align=center>
					<thead>
						<tr class='toprow'>
							<td class="text-right">
								<?php echo $MSG_RUNID ?>
							</td>
							<td class="text-left">
								<?php echo $MSG_USER ?>
							</td>
							<td class="text-center">
								<?php echo $MSG_PROBLEM_ID ?>
							</td>
							<td class="text-left">
								<?php echo $MSG_RESULT ?>
							</td>
							<td class="text-right">
								<?php echo $MSG_MEMORY ?>
							</td>
							<td class="text-right">
								<?php echo $MSG_TIME ?>
							</td>
							<td class="text-right">
								<?php echo $MSG_LANG ?>
							</td>
							<td class="text-right">
								<?php echo $MSG_CODE_LENGTH ?>
							</td>
							<td class="text-center">
								<?php echo $MSG_SUBMIT_TIME ?>
							</td>
							<?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
								echo "<td class='text-left'>";
								echo $MSG_JUDGER;
								echo "</td>";
							} ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$cnt = 0;
						foreach ($view_status as $row) {
							if ($cnt)
								echo "<tr class='oddrow'>";
							else
								echo "<tr class='evenrow'>";

							$i = 0;
							foreach ($row as $table_cell) {
								if ($i == 2 || $i == 8)
									echo "<td class='text-center'>";
								else if ($i == 0 || $i == 4 || $i == 5 || $i == 6 || $i == 7)
									echo "<td class='text-right'>";
								else
									echo "<td>";

								echo $table_cell;
								echo "</td>";
								$i++;
							}

							echo "</tr>\n";
							$cnt = 1 - $cnt;
						}
						?>
					</tbody>
				</table>
			</div>

			<div align=center id=center>
				<nav id="page" class="center">
					<small>
						<ul class="pagination">
							<?php
							echo "<li class='page-item'> <a href=status.php?" . $str2 . ">&lt;&lt;&nbsp;首页</a></li>";
							if (isset($_GET['prevtop']))
								echo "<li class='page-item'> <a href=status.php?" . $str2 . "&top=" . intval($_GET['prevtop']) . ">&lt&nbsp;上一页</a></li>";
							else
								echo "<li class='page-item'> <a href=status.php?" . $str2 . "&top=" . ($top + 50) . ">&lt&nbsp;上一页</a></li>";

							echo "<li class='page-item'> <a href=status.php?" . $str2 . "&top=" . $bottom . "&prevtop=$top>下一页&nbsp;&gt;</a></li>";
							?>
						</ul>
					</small>
				</nav>
			</div>

		</div>

	</div>


	<?php include("template/$OJ_TEMPLATE/js.php"); ?>

	<script>
		var i = 0;
		var judge_result = [<?php
							foreach ($judge_result as $result) {
								echo "'$result',";
							} ?> ''];

		var judge_color = [<?php
							foreach ($judge_color as $result) {
								echo "'$result',";
							} ?> ''];
							
		var i = 0;
		var interval = 800;
		var hj_ss = "<select class='http_judge form-control' length='2' name='result'>";
		for (var i = 0; i < 10; i++) {
			hj_ss += "	<option value='" + i + "'>" + judge_result[i] + " </option>";
		}
		hj_ss += "</select>";
		hj_ss += "<input name='manual' type='hidden'>";
		hj_ss += "<input class='http_judge form-control' size=5 title='输入判定原因与提示' name='explain' type='text'>";
		hj_ss += "<input type='button' class='http_judge' name='manual' value='确定' onclick='http_judge(this)' >";
		$(".http_judge_form").append(hj_ss);
		auto_refresh();
		$(".http_judge_form").hide();
	</script>
</body>

</html>