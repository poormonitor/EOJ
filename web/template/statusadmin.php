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
		<?php echo $OJ_NAME ?>
	</title>

	<?php include("template/css.php"); ?>
</head>

<body>
	<div class="container">
		<?php include("template/nav.php"); ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<br><br>
			<div align=center class="input-append">
				<form id=simform class=form-inline action="statusadmin.php" method="get">

					<?php echo $MSG_RUNID ?>&nbsp;
					<input class="form-control" type=text size=5 name=solution_id value='<?php echo htmlspecialchars($solution_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;

					<?php echo $MSG_PROBLEM_ID ?>&nbsp;
					<input class="form-control" type=text size=5 name=problem_id value='<?php echo htmlspecialchars($problem_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;

					<?php echo $MSG_GROUP ?>&nbsp;
					<select class="form-control" size="1" name="gid">
						<option value="-1">All</option>
						<?php
						if (isset($_GET['gid'])) {
							$gid = intval($_GET['gid']);
						} else {
							$gid = -1;
						}
						foreach ($all_group as $i) {
							$show_id = $i["gid"];
							$show_name = $i["name"];
							if ($show_id == $gid) {
								echo "<option value=$show_id selected>$show_name</option>";
							} else {
								echo "<option value=$show_id >$show_name</option>";
							}
						}
						?>
					</select>&nbsp;

					<?php echo $MSG_USER ?>&nbsp;
					<input class="form-control" type=text size=8 name=user_id value='<?php echo htmlspecialchars($_GET['user_id'], ENT_QUOTES); ?>'>&nbsp;

					<?php
					if (isset($cid) and $cid != 0) {
						echo $MSG_CONTEST_ID . "&nbsp;<input type='text' class='form-control' size='4' name='cid' value='$cid'>&nbsp;";
					} else {
						echo $MSG_CONTEST_ID . "&nbsp;<input type='text' class='form-control' size='4' name='cid' value=''>&nbsp;";
					}
					?>

					<?php echo $MSG_LANG ?>&nbsp;
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
					</select>&nbsp;

					<?php echo $MSG_RESULT ?>&nbsp;
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
					</select>&nbsp;

					<?php
					if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
						if (isset($_GET['showsim']))
							$showsim = intval($_GET['showsim']);
						else
							$showsim = 0;

						echo "SIM&nbsp;
					<select id=\"appendedInputButton\" class=\"form-control\" name=showsim size='1'>
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
					?>
				</form>
			</div>
			<br>

			<div id=center class="table-responsive">
				<table id=result-tab class="table table-striped content-box-header" align=center width=80%>
					<thead>
						<tr class='toprow'>
							<th class="text-right">
								<?php echo $MSG_RUNID ?>
							</th>
							<th class="text-left">
								<?php echo $MSG_USER ?>
							</th>
							<th class="text-center">
								<?php echo $MSG_PROBLEM_ID ?>
							</th>
							<th class="text-left">
								<?php echo $MSG_RESULT ?>
							</th>
							<th class="text-left">
								SIM
							</th>
							<th class="text-left">
								SIM to
							</th>
							<th class="text-right">
								<?php echo $MSG_MEMORY ?>
							</th>
							<th class="text-right">
								<?php echo $MSG_TIME ?>
							</th>
							<th class="text-right">
								<?php echo $MSG_LANG ?>
							</th>
							<th class="text-right">
								<?php echo $MSG_CODE_LENGTH ?>
							</th>
							<th class="text-center">
								<?php echo $MSG_SUBMIT_TIME ?>
							</th>
							<?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
								echo "<th class='text-center'>";
								echo $MSG_JUDGER;
								echo "</th>";
								if (isset($gid)) {
									echo "<th class='text-center'>";
									echo $MSG_GROUP;
									echo "</th>";
								}
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
								if ($i == 2 || $i == 10 || $i == 12 || $i == 11)
									echo "<td class='text-center'>";
								else if ($i == 0 || $i == 6 || $i == 7 || $i == 8 || $i == 9)
									echo "<td class='text-right'>";
								else if ($i == 5)
									echo "<td class='text-left td_result'>";
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
						<?php if (isset($view_errors)) {
							echo $view_errors;
						} ?>
					</tbody>
				</table>
			</div>
		</div>

	</div>


	<?php include("template/js.php"); ?>

	<script>
		var i = 0;
		var judge_result = [<?php
							foreach ($judge_result as $result) {
								echo "'$result',";
							} ?>];

		var judge_color = [<?php
							foreach ($judge_color as $result) {
								echo "'$result',";
							} ?>];
	</script>
</body>

</html>