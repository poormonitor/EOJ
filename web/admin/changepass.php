<?php require_once("admin-header.php");

?>

<hr>
<center><h3><?php echo $MSG_USER."-".$MSG_SETPASSWORD?></h3></center>

<div class='container'>

<?php
if(isset($_POST['do'])){
	//echo $_POST['user_id'];
	require_once("../include/check_post_key.php");
	//echo $_POST['passwd'];
	require_once("../include/my_func.inc.php");
	
	$user_id = $_POST['user_id'];
  $passwd = $_POST['passwd'];

		$user_id = stripslashes($user_id);
		$passwd = stripslashes($passwd);

	$passwd = pwGen($passwd);
	$sql = "update `users` set `password`=? where `user_id`=?  and user_id not in( select user_id from privilege where rightstr='administrator')";
	
	if(pdo_query($sql,$passwd,$user_id) == 1)
		echo "<center><h4 class='text-danger'>User ".$_POST['user_id']."'s Password Changed!</h4></center>";
  else
  	echo "<center><h4 class='text-danger'>There is No such User ".$_POST['user_id']."! or User ".$_POST['user_id']." is administrator!</h4></center>";
}
?>
<br>
<form action=changepass.php method=post class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo $MSG_USER_ID?></label>
		<?php if(isset($_GET['uid'])) { ?>
		<div class="col-sm-4"><input name="user_id" class="form-control" value="<?php echo $_GET['uid']?>" type="text" required ></div>
  	<?php } else if(isset($_POST['user_id'])) { ?>
		<div class="col-sm-4"><input name="user_id" class="form-control" value="<?php echo $_POST['user_id']?>" type="text" required ></div>
		<?php } else { ?>
		<div class="col-sm-4"><input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID."*"?>" type="text" required ></div>
		<?php } ?>
	</div>

	<div class="form-group">
		<label class="col-sm-offset-1 col-sm-3 control-label"><?php echo $MSG_PASSWORD?></label>
		<div class="col-sm-4"><input name="passwd" class="form-control" placeholder="<?php echo $MSG_PASSWORD."*"?>" type="password"  autocomplete="off" required ></div>
	</div>

	<div class="form-group">
		<?php require_once("../include/set_post_key.php");?>
		<div class="col-sm-offset-4 col-sm-2">
			<button name="do" type="hidden" value="do" class="btn btn-default btn-block" ><?php echo $MSG_SAVE?></button>
		</div>
		<div class="col-sm-2">
			<button name="submit" type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET?></button>
		</div>
	</div>
</form>

</div>

<?php
require_once("admin-footer.php");
?>



