<?php
require_once("./include/db_info.inc.php");
//cache head start
if (!isset($cache_time)) $cache_time = 10;
$sid = $OJ_NAME . $_SERVER["HTTP_HOST"];
$OJ_CACHE_SHARE = (isset($OJ_CACHE_SHARE) && $OJ_CACHE_SHARE) && !isset($_SESSION[$OJ_NAME . '_' . 'administrator']);
if (!$OJ_CACHE_SHARE && isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    $ip = ($_SERVER['REMOTE_ADDR']);
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $tmp_ip = explode(',', $REMOTE_ADDR);
        $ip = (htmlentities($tmp_ip[0], ENT_QUOTES, "UTF-8"));
    }
    $sid .= session_id() . $ip;
}
if (isset($_SERVER["REQUEST_URI"])) {
    $sid .= $_SERVER["REQUEST_URI"];
}
if (isset($OJ_LANG)) {
    $sid .= $OJ_LANG;
}

$sid = md5($sid);
$pageCacheKey = "cache_$sid.html";

if ($OJ_MEMCACHE) {
    $content = getCache($pageCacheKey);
    if ($content !== false) {
        echo $content;
        exit(0);
    } else {
        $use_cache = false;
        $write_cache = true;
    }
}
ob_start();
