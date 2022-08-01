<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">

	<title><?php echo $OJ_NAME ?></title>
	<?php include("template/css.php"); ?>

</head>

<body>

	<div class="container">
		<?php include("template/nav.php"); ?>
		<div class="jumbotron">
			<div id="content" style="width:95%;margin:1em auto 0;">

				<table style="width:100%;margin-bottom:5px;">
					<tr>
						<td style="width:50%;">
							<span id="path-lhs">getsource.php?id=<?php echo intval($_GET['left']) ?></span> &nbsp;
							<a id="save-lhs" class="save-link" href="#">save</a>
						</td>
						<td style="width:50%;">
							<span id="path-rhs">getsource.php?id=<?php echo intval($_GET['right']) ?></span> &nbsp;
							<a id="save-rhs" class="save-link" href="#">save</a>
						</td>
					</tr>
				</table>

				<div id="container" style="height:450px;width:100%;font-size:20px;"></div>

				<div class="row" style="margin-top:10px;white-space:nowrap">
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-condensed px-2">
								<thead>
									<tr>
										<th><?php echo $MSG_RUNID ?></th>
										<th><?php echo $MSG_PROBLEM ?></th>
										<th><?php echo $MSG_USER ?></th>
										<th><?php echo $MSG_NICK ?></th>
										<th><?php echo $MSG_LANG ?></th>
										<th><?php echo $MSG_RESULT ?></th>
										<?php if ($sresult == 4) { ?>
											<th><?php echo $MSG_TIME ?></th>
											<th><?php echo $MSG_MEMORY ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $sid ?></td>
										<td><?php echo $sproblem_id ?></td>
										<td><?php echo $suser_id ?></td>
										<td><?php echo $snick ?></td>
										<td><?php echo $language_name[$slanguage] ?></td>
										<td><?php echo $judge_result[$sresult] ?></td>
										<?php if ($sresult == 4) { ?>
											<td><?php echo $stime ?> ms</td>
											<td><?php echo $smemory ?> KB</td>
										<?php } ?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-condensed lr-padding">
								<thead>
									<tr>
										<th><?php echo $MSG_RUNID ?></th>
										<th><?php echo $MSG_PROBLEM ?></th>
										<th><?php echo $MSG_USER ?></th>
										<th><?php echo $MSG_NICK ?></th>
										<th><?php echo $MSG_LANG ?></th>
										<th><?php echo $MSG_RESULT ?></th>
										<?php if ($rresult == 4) { ?>
											<th><?php echo $MSG_TIME ?></th>
											<th><?php echo $MSG_MEMORY ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $rid ?></td>
										<td><?php echo $rproblem_id ?></td>
										<td><?php echo $ruser_id ?></td>
										<td><?php echo $rnick ?></td>
										<td><?php echo $language_name[$rlanguage] ?></td>
										<td><?php echo $judge_result[$rresult] ?></td>
										<?php if ($rresult == 4) { ?>
											<td><?php echo $rtime ?> ms</td>
											<td><?php echo $rmemory ?> KB</td>
										<?php } ?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include("template/js.php"); ?>
		<script src="<?php echo $OJ_CDN_URL . "monaco/min/vs/" ?>loader.js"></script>

		<script type="text/javascript">
			var config = {};

			require.config({
				paths: {
					vs: 'monaco/min/vs'
				}
			});

			require(['vs/editor/editor.main'], function() {
				window.diffEditor = monaco.editor.createDiffEditor(document.getElementById('container'), {
					readOnly: true,
					fontSize: "18px",
					scrollbar: {
						alwaysConsumeMouseWheel: false
					}
				});

				diffEditor.setModel({
					original: monaco.editor.createModel(`<?php echo $sview_source ?>`, '<?php echo $language_monaco[$slanguage] ?>'),
					modified: monaco.editor.createModel(`<?php echo $rview_source ?>`, '<?php echo $language_monaco[$rlanguage] ?>')
				});
				window.onresize = function() {
					window.diffEditor.layout();
				};
			});

			document.getElementById('save-lhs').addEventListener('mouseover', function() {
				download_content(this, "lhs", "<?php echo intval($_GET['left']) ?>");
			}, false);
			document.getElementById('save-rhs').addEventListener('mouseover', function() {
				download_content(this, "rhs", "<?php echo intval($_GET['right']) ?>");
			}, false);
		</script>

</body>

</html>