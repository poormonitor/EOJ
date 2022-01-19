<div>
	<div id=footer class='center col-sm-2 col-sm-offset-1'>
		<?php if ($OJ_SPONSOR) { ?>
			Sponsored by <a id='footer' href='<?php echo $OJ_SPONSOR_URL ?>'><?php echo $OJ_SPONSOR ?></a>
		<?php } ?>
	</div>
	<div id=footer class='center col-sm-2'>
		<a id='footer' href='policy.html'><?php echo $MSG_POLICY ?></a>
	</div>
	<div id=footer class='center col-sm-2'>Powered by <a id='footer' href='https://git.oldmonitor.cn/poormonitor/hustoj'>HUSTOJ</a></div>
	<div id=footer class='center col-sm-2'>
		<?php if ($OJ_BEIAN) { ?>
			<a id='footer' href='https://beian.miit.gov.cn/'><?php echo $OJ_BEIAN ?></a>
		<?php } ?>
	</div>
	<div id=footer class='center col-sm-2'>
		<?php if ($OJ_MPS_BEIAN) { ?>
			<a id='footer' target="_blank" href="<?php echo $OJ_MPS_BEIAN_URL ?>"><?php echo $OJ_MPS_BEIAN ?></a>
		<?php } ?>
	</div>

</div>
<br /><br />
<script src="<?php echo $OJ_CDN_URL . "template/$OJ_TEMPLATE/" ?>jquery.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "template/$OJ_TEMPLATE/" ?>bootstrap.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "template/$OJ_TEMPLATE/" ?>index.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "include/" ?>sweetalert.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-175545655-2"></script>
<?php
$endTime = microtime(true);
$runTime = ($endTime - $startTime) * 1000 . ' ms';
?>
<script>
	var OJ_CDN = "<?php echo $OJ_CDN_URL ?>";
	$(document).ready(function() {
		$("#csrf").load("csrf.php");
		<?php
		if (isset($_SESSION[$OJ_NAME . "_administrator"])) echo "admin_mod();";
		?>
	});
	console.log("Loading used <?php echo $runTime; ?>.")
	console.log("Thanks for choosing <?php echo $OJ_NAME; ?>.");
</script>