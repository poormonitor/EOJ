<?php
require_once("../include/db_info.inc.php");
if (!isset($_SESSION[$OJ_NAME.'_'.'postkey'])||!isset($_POST['postkey'])||$_SESSION[$OJ_NAME.'_'.'postkey']!=$_POST['postkey'])
	exit(1);
?>
