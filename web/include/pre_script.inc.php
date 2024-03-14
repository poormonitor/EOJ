<?php
$startTime = microtime(true);

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

ini_set("display_errors", $OJ_DEBUG ? "On" : "Off");
error_reporting(E_ALL);
ini_set('date.timezone', $OJ_TIMEZONE);
date_default_timezone_set($OJ_TIMEZONE);

@session_start();

$OJ_IDENTITY = str_replace(" ", "", $OJ_NAME);

if (isset($_SESSION[$OJ_NAME . '_' . 'OJ_LANG'])) {
	$OJ_LANG = $_SESSION[$OJ_NAME . '_' . 'OJ_LANG'];
} else if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array("zh", "en"))) {
	$OJ_LANG = $_COOKIE['lang'];
} else if (isset($_GET['lang']) && in_array($_GET['lang'], array("zh", "en"))) {
	$OJ_LANG = $_GET['lang'];
} else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], "zh-CN")) {
	$OJ_LANG = "zh";
}

require_once(dirname(__FILE__) . "/pdo.php");
require_once(dirname(__FILE__) . "/memcache.php");

try {
	$dbh = new PDO("mysql:host=" . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS, array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
} catch (PDOException $e) {
	$message = "Database account/password fail, check db_info.inc.php. 数据库账户密码错误，请检查配置文件db_info.inc.php。";
	$view_errors_js = "swal('Database Error', '$message', 'error').then(()=>{window.location.reload()})";
	$OJ_FAIL = true;
	require_once(dirname(__FILE__) . "/../template/error.php");
	exit(0);
}

$timezone_offset = function ($timezone) {
	$offset = new DateTimeZone($timezone);
	$offset = $offset->getOffset(new DateTime("now"));
	$offset_hours = $offset / 3600;
	$offset_sign = $offset_hours >= 0 ? '+' : '-';
	$offset_hours = abs($offset_hours);
	$offset_minutes = ($offset_hours - floor($offset_hours)) * 60;
	return sprintf("%s%02d:%02d", $offset_sign, floor($offset_hours), $offset_minutes);
};

$timezone_offset = $timezone_offset($OJ_TIMEZONE);
pdo_query("SET names utf8");
pdo_query("SET time_zone = '$timezone_offset'");

$OJ_LOG_FILE = "/var/log/hustoj/{$OJ_NAME}.log";
require_once(dirname(__FILE__) . "/logger.php");
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$logger = new Logger(
		$_SESSION[$OJ_NAME . '_' . 'user_id'],
		$OJ_LOG_FILE,
		$OJ_LOG_DATETIME_FORMAT,
		$OJ_LOG_ENABLED,
		$OJ_LOG_PID_ENABLED,
		$OJ_LOG_USER_ENABLED,
		$OJ_LOG_URL_ENABLED,
		$OJ_LOG_URL_HOST_ENABLED,
		$OJ_LOG_URL_PARAM_ENABLED,
		$OJ_LOG_TRACE_ENABLED
	);
} else {
	$logger = new Logger(
		"guest",
		$OJ_LOG_FILE,
		$OJ_LOG_DATETIME_FORMAT,
		$OJ_LOG_ENABLED,
		$OJ_LOG_PID_ENABLED,
		$OJ_LOG_USER_ENABLED,
		$OJ_LOG_URL_ENABLED,
		$OJ_LOG_URL_HOST_ENABLED,
		$OJ_LOG_URL_PARAM_ENABLED,
		$OJ_LOG_TRACE_ENABLED
	);
}

$logger->info();
