<footer class="footer">
	<div class="container">
		<div class="footer-container">
			<span class="text-nowrap pe-3">Powered by <a id='footer' href='https://git.techo.cool/poormonitor/EOJ'>EOJ</a></span>
			<span class="text-nowrap pe-3">
				<a id='footer' href='/policy.html'><?php echo $MSG_POLICY ?></a>
			</span>
			<span class="text-nowrap pe-3">
				<?php if ($OJ_SPONSOR) { ?>
					Sponsored by <a id='footer' href='<?php echo $OJ_SPONSOR_URL ?>'><?php echo $OJ_SPONSOR ?></a>
				<?php } ?>
			</span>
			<span class="text-nowrap pe-3">
				<?php if ($OJ_BEIAN) { ?>
					<a id='footer' href='https://beian.miit.gov.cn/'><?php echo $OJ_BEIAN ?></a>
				<?php } ?>
			</span>
			<span class="text-nowrap pe-3">
				<?php if ($OJ_MPS_BEIAN) { ?>
					<a id='footer' target="_blank" href="<?php echo $OJ_MPS_BEIAN_URL ?>"><?php echo $OJ_MPS_BEIAN ?></a>
				<?php } ?>
			</span>
		</div>
	</div>
</footer>
<script>
	var OJ_CDN = "<?php echo $OJ_CDN_URL ?>";
	var OJ_LANG = '<?php echo $OJ_LANG ?>';
</script>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>bootstrap.min.js"></script>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>index.min.js?v=1.32"></script>
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
	});
	console.log("Loading used <?php echo $runTime; ?>.")
	console.log("Thanks for choosing <?php echo $OJ_NAME; ?>.");
	<?php
	$sql = "SELECT * FROM news WHERE news_id = -1 AND defunct = 'N'";
	$result = pdo_query($sql);
	if ($result) {
		$message = json_decode($result[0]["content"], true);
	?>
		setAD("<?php echo $message["image"] ?>",
			"<?php echo $message["href"] ?>",
			<?php echo $message["float"] ?>);
	<?php } ?>
</script>