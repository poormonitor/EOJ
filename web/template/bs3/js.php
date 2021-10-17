<div class=col-sm-1></div>
<div id=footer class='center col-sm-2'>GPLv2 licensed by HUSTOJ <?php echo date('Y'); ?></div>
<div id=footer class='center col-sm-2'><a id='footer' href='https://git.oldmonitor.cn/poormonitor/hustoj'>Modified under GPLv2</a></div>
<div id=footer class='center col-sm-2'>Sponsored by <a id='footer' href='https://oldmonitor.cn'>Johnson Sun</a></div>
<div id=footer class='center col-sm-2'><a id='footer' href='https://beian.miit.gov.cn/'>浙ICP备2021022182号</a></div>
<div id=footer class='center col-sm-2'><a id='footer' target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010802011750">浙公网安备 33010802011750号</a></div>
<div class=col-sm-1></div>
</br></br>
<script src="<?php echo $OJ_CDN_URL . $path_fix . "template/$OJ_TEMPLATE/" ?>jquery.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . $path_fix . "template/$OJ_TEMPLATE/" ?>bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-175545655-2"></script>
<script src="https://cdn.jsdelivr.net/gh/poormonitor/hustoj@master/web/template/bs3/index.min.js"></script>
<?php
$endTime = microtime(true);
$runTime = ($endTime - $startTime) * 1000 . ' ms';
?>
<script>
	$(document).ready(function() {
		$("#csrf").load("<?php echo $path_fix ?>csrf.php");
		<?php
		if (isset($_SESSION[$OJ_NAME . "_administrator"])) echo "admin_mod();";
		?>
	});

	console.log("Loading used <?php echo $runTime; ?>.")
</script>