<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
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
			<center>
				<nav class="center">
					<small>
						<ul class="pagination">
							<?php
							$section = 2;
							$href_url = "problemset.php"
							?>
							<?php if ($page > $section + 1) { ?>
								<li class="page-item"><a href="$href_url?page=1">1</a></li>
							<?php } ?>
							<?php if ($page > $section + 3) { ?>
								<li class="page-item disabled"><span class="page-link">...</span></li>
							<?php } ?>
							<?php
							if (!isset($page)) $page = 1;
							$page = intval($page);
							$start = $page > $section ? $page - $section : 1;
							$end = $page + $section > $view_total_page ? $view_total_page : $page + $section;
							for ($i = $start; $i <= $end; $i++) {
								echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='Go to page' href='$href_url?page=$i'>$i</a></li>";
							}
							?>
							<?php if ($page < $view_total_page - $section - 1) { ?>
								<li class="page-item disabled"><span class="page-link">...</span></li>
							<?php } ?>
							<?php if ($page < $view_total_page - $section) { ?>
								<li class="page-item"><a href="<?php echo $href_url ?>?page=<?php echo $view_total_page ?>"><?php echo $view_total_page ?></a></li>
							<?php } ?>
						</ul>
					</small>
				</nav>

				<table>
					<tr align='center' class='evenrow'>
						<td width='5'></td>
						<td colspan='1'>
							<form class=form-inline action=problem.php>
								<input class="form-control search-query" type='text' name='id' placeholder="<?php echo $MSG_PROBLEM_ID ?>">
								<button class="form-control" type='submit'><?php echo $MSG_SEARCH ?></button>&nbsp;&nbsp;
							</form>
						</td>
						<td colspan='1'>
							<form class="form-search form-inline">
								<input type="text" name=search class="form-control search-query" placeholder="<?php echo $MSG_TITLE . ', ' . $MSG_SOURCE ?>">
								<button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
							</form>
						</td>
					</tr>
				</table>

				<table id='problemset' width='90%' class='table table-striped'>
					<thead>
						<tr class='toprow'>
							<th class='center'></th>
							<th class='hidden-xs center'>
								<?php echo $MSG_PROBLEM_ID ?>
							</th>
							<th>
								<?php echo $MSG_TITLE ?>
							</th>
							<th class='hidden-xs center'>
								<?php echo $MSG_SOURCE ?>
							</th>
							<th style="cursor:hand" class='center'>
								<?php echo $MSG_SOVLED ?>
							</th>
							<th style="cursor:hand" class='center'>
								<?php echo $MSG_SUBMIT ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$cnt = 0;
						foreach ($view_problemset as $row) {
							if ($cnt)
								echo "<tr class='oddrow'>";
							else
								echo "<tr class='evenrow'>";
							$i = 0;
							foreach ($row as $table_cell) {
								if ($i == 1 || $i == 3) echo "<td  class='hidden-xs'>";
								else echo "<td>";
								echo "\t" . $table_cell;
								echo "</td>";
								$i++;
							}
							echo "</tr>";
							$cnt = 1 - $cnt;
						}
						?>
					</tbody>
				</table>
			</center>
			<nav class="center">
				<small>
					<ul class="pagination">
						<?php
						$section = 2;
						$href_url = "problemset.php"
						?>
						<?php if ($page > $section + 1) { ?>
							<li class="page-item"><a href="<?php echo $href_url ?>?page=1">1</a></li>
						<?php } ?>
						<?php if ($page > $section + 2) { ?>
							<li class="page-item disabled"><span class="page-link">...</span></li>
						<?php } ?>
						<?php
						if (!isset($page)) $page = 1;
						$page = intval($page);
						$start = $page > $section ? $page - $section : 1;
						$end = $page + $section > $view_total_page ? $view_total_page : $page + $section;
						for ($i = $start; $i <= $end; $i++) {
							echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='Go to page' href='$href_url?page=$i'>$i</a></li>";
						}
						?>
						<?php if ($page < $view_total_page - $section - 1) { ?>
							<li class="page-item disabled"><span class="page-link">...</span></li>
						<?php } ?>
						<?php if ($page < $view_total_page - $section) { ?>
							<li class="page-item"><a href="<?php echo $href_url ?>?page=<?php echo $view_total_page ?>"><?php echo $view_total_page ?></a></li>
						<?php } ?>
					</ul>
				</small>
			</nav>
		</div>

	</div>

	<?php include("template/js.php"); ?>
	<script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/jquery.tablesorter.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#problemset").tablesorter();
			$("#problemset").after($("#page").prop("outerHTML"));
		});
	</script>
</body>

</html>