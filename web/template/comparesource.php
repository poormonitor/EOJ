<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">
	<link type="text/css" rel="stylesheet" href="<?php echo $OJ_CDN_URL . "mergely/" ?>codemirror.min.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo $OJ_CDN_URL . "mergely/" ?>mergely.min.css" />

	<title><?php echo $OJ_NAME ?></title>
	<?php include("template/css.php"); ?>

</head>

<body>

	<div class="container">
		<?php include("template/nav.php"); ?>
		<div class="jumbotron">

			<body style="width: 100%;">
				<table style="width: 100%;">
					<tr>
						<td style="width: 50%;"><input type="checkbox" id="ignorews">&nbsp;Ignore witespaces</td>
					</tr>
				</table>
				<br>

				<table style="width: 100%;">
					<tr>
						<td style="width: 50%;"><tt id="path-lhs"></tt> &nbsp; <a id="save-lhs" class="save-link" href="#">save</a></td>
						<td style="width: 50%;"><tt id="path-rhs"></tt> &nbsp; <a id="save-rhs" class="save-link" href="#">save</a></td>
					</tr>
				</table>

				<div id="mergely-resizer" style="height: 450px;">
					<div id="compare">
					</div>
				</div>

			</body>


		</div>
		<?php include("template/js.php"); ?>
		<script type="text/javascript" src="<?php echo $OJ_CDN_URL?>mergely/codemirror.min.js"></script>
		<script type="text/javascript" src="<?php echo $OJ_CDN_URL?>mergely/mergely.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#compare').mergely({
					width: 'auto',
					height: 'auto',
					cmsettings: {
						mode: 'text/plain',
						readOnly: false
					},
				});
				var lhs_url = 'getsource.php?id=<?php echo intval($_GET['left']) ?>';
				var rhs_url = 'getsource.php?id=<?php echo intval($_GET['right']) ?>';
				$.ajax({
					type: 'GET',
					async: true,
					dataType: 'text',
					url: lhs_url,
					success: function(response) {
						$('#path-lhs').text(lhs_url);
						$('#compare').mergely('lhs', response);
					}
				});
				$.ajax({
					type: 'GET',
					async: true,
					dataType: 'text',
					url: rhs_url,
					success: function(response) {
						$('#path-rhs').text(rhs_url);
						$('#compare').mergely('rhs', response);
					}
				});
				var dropZone = document.getElementById('drop_zone');
				document.body.addEventListener('dragover', handleDragOver, false);
				document.body.addEventListener('drop', handleFileSelect, false);
				document.getElementById('save-lhs').addEventListener('mouseover', function() {
					download_content(this, "lhs");
				}, false);
				document.getElementById('save-rhs').addEventListener('mouseover', function() {
					download_content(this, "lhs");
				}, false);
				document.getElementById('ignorews').addEventListener('change', function() {
					$('#compare').mergely('options', {
						ignorews: this.checked
					});
				}, false);


			});
		</script>

</body>

</html>