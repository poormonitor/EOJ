<?php

	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
if(isset($OJ_REGISTER)&&!$OJ_REGISTER) exit(0);
	require_once('./include/setlang.php');
	$view_title= "Registe a new account";
	
///////////////////////////MAIN	
	

require("template/registerpage.php");

if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
