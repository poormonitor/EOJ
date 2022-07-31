<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = $OJ_NAME;
if (file_exists("template/faqs.$OJ_LANG.php")) {
    require("template/faqs.$OJ_LANG.php");
} else {
    require("template/faqs.php");
}
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
