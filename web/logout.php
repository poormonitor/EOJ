<?php
include("./include/db_info.inc.php");
session_start();
unset($_SESSION[$OJ_NAME . '_' . 'user_id']);
setcookie($OJ_IDENTITY . "_user", "");
setcookie($OJ_IDENTITY . "_check", "");
session_destroy();
header("Location:index.php");
