<?php
include("./include/db_info.inc.php");
session_start();
unset($_SESSION[$OJ_NAME . '_' . 'user_id']);
setcookie(str_replace($OJ_NAME, " ", "") . "_user", "");
setcookie(str_replace($OJ_NAME, " ", "") . "_check", "");
session_destroy();
header("Location:index.php");
