<!DOCTYPE html><html><head><meta charset="utf-8"><title><?php echo $OJ_NAME;?></title>
<style>
    @media (prefers-color-scheme: dark) {
        body {
            height: auto;
            background:#242424;
        }
</style>
</head><body></body></html>
<script src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/template/bs3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
<?php 
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";
	require_once("./include/check_post_key.php");
	require_once("./include/my_func.inc.php");
if(
		(isset($OJ_EXAM_CONTEST_ID)&&$OJ_EXAM_CONTEST_ID>0)||
		(isset($OJ_ON_SITE_CONTEST_ID)&&$OJ_ON_SITE_CONTEST_ID>0)
   ){
		$view_errors= $MSG_MODIFY_NOT_ALLOWED_FOR_EXAM;
		require("template/".$OJ_TEMPLATE."/error.php");
		exit ();
}
$err_str="";
$err_cnt=0;
$len;
if (!$_SESSION[$OJ_NAME.'_'.'user_id']) {
    die();
}
$user_id=$_SESSION[$OJ_NAME.'_'.'user_id'];
$email=trim($_POST['email']);
$len=strlen($_POST['email']);
if ($len>100){
	$err_str=$err_str."Email Too Long!";
	$err_cnt++;
}
if ($err_cnt>0){
	print "<script language='javascript'>\n";
	echo "swal('";
	echo $err_str;
	print "').then((onConfirm)=>{history.go(-1);});\n</script>";
	exit(0);
	
}
$email=(htmlentities ($email,ENT_QUOTES,"UTF-8"));
$sql="UPDATE `users` SET"
."`email`=?";
$sql.="WHERE `user_id`=?";
//echo $sql;
//exit(0);
pdo_query($sql,$email,$user_id);
?>
<script>
    swal("修改成功！").then((onConfirm)=>{location.href="userinfo.php?user=<?php echo $user_id;?>";});
</script>
<?php
?>