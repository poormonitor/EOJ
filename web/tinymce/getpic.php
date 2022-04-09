<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");


if (!isset($_GET["file"])) {
    header("HTTP/1.1 400 Bad Request.");
    exit(0);
};

$file = "../upload/images/" . str_replace("_", "/", $_GET["file"]);

if (!(isset($_SESSION[$OJ_NAME . '_' . 'user_id'])
    || isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
    || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
    header("HTTP/1.1 401 Unauthorized.");
    exit(0);
};

if (!file_exists($file)) {
    header("HTTP/1.1 404 Not Found.");
    exit(0);
};

$type = filetype($file);

header("Content-type: $type");

set_time_limit(0);
readfile($file);
