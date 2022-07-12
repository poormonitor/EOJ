<div>
	<div id=footer class='center col-sm-2 col-sm-offset-1'>
		<?php if ($OJ_SPONSOR) { ?>
			Sponsored by <a id='footer' href='<?php echo $OJ_SPONSOR_URL ?>'><?php echo $OJ_SPONSOR ?></a>
		<?php } ?>
	</div>
	<div id=footer class='center col-sm-2'>
		<a id='footer' href='policy.html'><?php echo $MSG_POLICY ?></a>
	</div>
	<div id=footer class='center col-sm-2'>Powered by <a id='footer' href='https://git.oldmonitor.cn/poormonitor/hoj'>HOJ</a></div>
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
<br><br>
<script>
	var OJ_CDN = "<?php echo $OJ_CDN_URL ?>";
	var OJ_LANG = '<?php echo $OJ_LANG ?>';
</script>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>bootstrap.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>index.min.js?v=1.23"></script>
<script src="<?php echo $OJ_CDN_URL . "include/" ?>sweetalert.min.js"></script>
<?php if (isset($OJ_GOOGLE_ANALYTICS)) { ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $OJ_GOOGLE_ANALYTICS ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', '<?php echo $OJ_GOOGLE_ANALYTICS ?>');
	</script>
<?php
}
$endTime = microtime(true);
$runTime = ($endTime - $startTime) * 1000 . ' ms';
$prefix = isset($prefix) ? $prefix : "";
?>
<script>
	$(document).ready(function() {
		$("#csrf").load("<?php echo $prefix; ?>csrf.php");
		<?php
		if (isset($_SESSION[$OJ_NAME . "_" . "administrator"])) echo "admin_mod();";
		?>
	});
	console.log("Loading used <?php echo $runTime; ?>.")
	console.log("Thanks for choosing <?php echo $OJ_NAME; ?>.");
	<?php if (isset($OJ_FLOAT_NOTICE) && intval($OJ_FLOAT_NOTICE[0])) { ?>
		setAD("<?php echo $OJ_FLOAT_NOTICE[1] ?>",
			"<?php echo $OJ_FLOAT_NOTICE[2] ?>",
			<?php echo intval($OJ_FLOAT_NOTICE[3]) ? 'true' : 'false' ?>);
	<?php } ?>
</script>