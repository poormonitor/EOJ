<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="<?php echo $OJ_NAME ?>">
	<link rel="shortcut icon" href="/favicon.ico">

	<title><?php echo $MSG_SIMILARITY . " - " . $OJ_NAME ?></title>
	<?php include("template/css.php"); ?>

	<style>
		.highlight-red {
			background-color: rgba(255, 0, 0, 0.15);
		}
	</style>

</head>

<body>
	<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
		<div class="modal__overlay" tabindex="-1" data-micromodal-close>
			<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
				<main class="modal__content" id="modal-1-content">
					<div id="center" class="table-responsive">
						<table class="table table-striped content-box-header">
							<thead>
								<tr>
									<th><?php echo $MSG_SIM_PAS ?></th>
									<th></th>
									<th><?php echo $MSG_SIMILARITY ?></th>
									<th><?php echo $MSG_CODE ?></th>
								</tr>
							</thead>
							<tbody id="content"></tbody>
						</table>
						<div class="fs-1 text-center mt-5" id="loading"><?php echo $MSG_LOADING ?></div>
					</div>
				</main>
			</div>
		</div>
	</div>
	<div class="container">
		<?php include("template/nav.php"); ?>
		<div class="jumbotron">
			<div id="content" style="width:95%;margin:1em auto 0;">
				<div class="mb-3">
					<?php if (isset($sim)) { ?>
						<span class="fs-3 me-3"><?php echo $MSG_SIMILARITY ?>:</span>
						<span class="fs-2 fw-bold text-danger"><?php echo $sim ?> %</span>
					<?php } ?>
					<a class='btn btn-sm btn-info ms-3' href="javascript:go_render_ana('<?php echo $rid ?>')"><?php echo $MSG_ANALYSIS ?></a>
				</div>

				<table style="width:100%;margin-bottom:5px;">
					<tr>
						<td style="width:50%;">
							<?php if (isset($sim)) { ?>
								<span class="fw-bold"><?php echo $MSG_SIM_PAS ?></span>&nbsp;&nbsp;
							<?php } ?>
							<span id="path-lhs">getsource.php?id=<?php echo intval($_GET['left']) ?></span> &nbsp;
							<a id="save-lhs" class="save-link" href="#">save</a>
						</td>
						<td style="width:50%;">
							<?php if (isset($sim)) { ?>
								<span class="fw-bold"><?php echo $MSG_SIM_POS ?></span>&nbsp;&nbsp;
							<?php } ?>
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
										<th>IP</th>
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
										<td class="<?php echo $suspected ?>"><?php echo $sip ?></td>
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
										<th>IP</th>
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
										<td class="<?php echo $suspected ?>"><?php echo $rip ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include("template/js.php"); ?>
		<script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL ?>template/micromodal.min.js"></script>
		<script>
			MicroModal.init();
			var rendered = false;

			function go_render_ana(rid) {
				if (!rendered)
					$.ajax({
						url: 'simsource.php?sid=<?php echo $rid ?>',
						method: 'GET',
						dataType: 'json',
						success: function(response) {
							$("#loading").hide()
							rendered = true;
							$.each(response, function(index, item) {
								var tr = $("<tr>");

								var sHref = $("<a>").text(item[1])
								sHref.attr("href", "comparesource.php?left=" + item[1] + "&right=" + rid)
								sHref.attr("target", "_blank")
								var sTd = $("<td>");
								sTd.append(sHref)
								var nameTd = $("<td>").text(item[3][0] + " " + item[3][1])
								var simTd = $("<td>").text((item[0] * 100).toFixed(2) + "%");
								var codeTd = $("<td>").html("<pre>" + item[2] + "</pre>");

								tr.append(sTd, nameTd, simTd, codeTd);
								$("tbody#content").append(tr);
							});
						},
					});

				MicroModal.show("modal-1");
			}
		</script>

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