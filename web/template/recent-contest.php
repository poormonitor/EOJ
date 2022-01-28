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
	<?php include("template/css.php"); ?>



</head>

<body>

	<div class="container">
		<?php include("template/nav.php"); ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<div id="main" style="margin: 10px;">
				<!--
	<table width=80% align=center>
		<thead class=toprow>
			<tr>
				<th class="column-1">OJ</th><th class="column-2">Name</th><th class="column-3">Start Time</th><th class="column-4">Week</th><th class="column-5">Access</th>
			</tr>
		</thead>
		<tbody class="row-hover">
		<?php
		$odd = true;
		foreach ($rows as $row) {
			$odd = !$odd;
		?>
		<tr class="<?php echo $odd ? "oddrow" : "evenrow"  ?>">
			<td class="column-1"><?php echo $row['oj'] ?></td><td class="column-2"><a id="name_<?php echo $row['id'] ?>" href="<?php echo $row['link'] ?>" target="_blank"><?php echo $row['name'] ?></a></td><td class="column-3"><?php echo $row['start_time'] ?></td><td class="column-4"><?php echo $row['week'] ?></td><td class="column-5"><?php echo $row['access'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>
-->
				<table class="table table-striped" align="center">
					<thead>
						<tr>
							<th class="column-1">OJ</th>
							<th class="column-2">Name</th>
							<th class="column-3">Start Time</th>
							<th class="column-4">Week</th>
							<th class="column-5">Access</th>
						</tr>
					</thead>
					<tbody id="contest-list"></tbody>
				</table>

			</div>
			<div align=center>DataSource:https://algcontest.rainng.com/contests.json Spider Author:<a href="https://github.com/Azure99/AlgContestInfo">Azure99</a></div>

		</div>

	</div>
	<?php include("template/js.php"); ?>
	<script>
		var contestList = $("#contest-list");
		$.get("https://algcontest.rainng.com/contests.json", function(response) {
			response.map(function(val) {
				var item = "<tr><td class='column-1'>" + val.oj + "</td>" +
					"<td class='column-2'><a target='_blank' href='" + val.link + "'>" + val.name + "</a></td>" +
					"<td class='column-3'>" + val.start_time + "</td>" +
					"<td class='column-4'>" + val.week + "</td>" +
					"<td class='column-5'>" + val.access + "</td></tr>"
				contestList.append(item);
			});
		});
	</script>
</body>

</html>