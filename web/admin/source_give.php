<?php require_once("admin-header.php"); ?>
<?php if (isset($_POST['do'])) {
	require_once("../include/check_post_key.php");
	$from = $_POST['from'];
	$to = $_POST['to'];
	$start = intval($_POST['start']);
	$end = intval($_POST['end']);
	$sql = "update `solution` set `user_id`=? where `user_id`=? and problem_id>=? and problem_id<=? and result=4";
	//echo $sql;
	echo pdo_query($sql, $to, $from, $start, $end) . " source file given!";
}
?>
<div class="container">
	<h3 class='center'><?php echo $MSG_GIVESOURCE ?></h3>
	<br>
	<form action='source_give.php' method=post class='form-horizontal'>
		<div class='form-group'>
			<label class='control-label col-sm-2 col-sm-offset-2'>
				<?php echo $MSG_FROM . " " . $MSG_USER ?>
			</label>
			<div class='col-sm-4'>
				<input type=input class='form-control' name='from' placeholder="1001">
			</div>
		</div>
		<div class='form-group'>
			<label class='control-label col-sm-2 col-sm-offset-2'>
				<?php echo $MSG_TO . " " . $MSG_USER ?>
			</label>
			<div class='col-sm-4'>
				<input type=input class='form-control' name='to' placeholder="1002">
			</div>
		</div>
		<div class='form-group'>
			<label class='control-label col-sm-2 col-sm-offset-2'>
				<?php echo $MSG_FROM . " " . $MSG_PROBLEM_ID ?>
			</label>
			<div class='col-sm-4'>
				<input type=input class='form-control' name='start' placeholder="1001">
			</div>
		</div>
		<div class='form-group'>
			<label class='control-label col-sm-2 col-sm-offset-2'>
				<?php echo $MSG_TO . " " . $MSG_PROBLEM_ID ?>
			</label>
			<div class='col-sm-4'>
				<input type=input class='form-control' name='end' placeholder="1002">
			</div>
		</div>
		<div class='form-group'>
			<div class='col-sm-offset-4 col-sm-4'>
				<input type='hidden' name='do' value='do'>
				<?php require_once("../include/set_post_key.php"); ?>
				<input type=submit class='form-control' name='do' value='<?php echo $MSG_GIVESOURCE ?>'>
			</div>
		</div>
	</form>
</div>
<?php
require_once("admin-footer.php");
?>