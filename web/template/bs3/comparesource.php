<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">
	<link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/mergely/codemirror.css" />
	<link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/mergely/mergely.css" />

	<title><?php echo $OJ_NAME ?></title>
	<?php include("template/$OJ_TEMPLATE/css.php"); ?>


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<div class="container">
		<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">


			<!-- Requires jQuery -->

			<body style="width: 100%;">
				<table style="width: 100%;">
					<tr>
						<td style="width: 50%;"><input type="checkbox" id="ignorews">&nbsp;Ignore witespaces</td>
					</tr>
				</table>
				<br />

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


		</div> <!-- /container -->


		<!-- Bootstrap core JavaScript
    ================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<?php include("template/$OJ_TEMPLATE/js.php"); ?>

		<!-- Requires CodeMirror 2.16 -->
		<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/mergely/codemirror.min.js"></script>

		<!-- Requires Mergely -->
		<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/mergely/mergely.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#compare').mergely({
					width: 'auto',
					height: 'auto', // containing div must be given a height
					cmsettings: {
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