<?php
$cache_time = 90;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/const.inc.php");

if (!isset($_GET['sid'])) {
	$view_errors = "$MSG_NOT_EXISTED";
	require("template/error.php");
	exit(0);
}

$ok = false;
$sid = intval($_GET['sid']);

$sql = "SELECT * FROM `solution` WHERE `solution_id`=?";
$result = pdo_query($sql, $sid);
$row = $result[0];
$slanguage = $row['language'];
$snick = $row['nick'];
$sresult = $row['result'];
$stime = $row['time'];
$smemory = $row['memory'];
$sproblem_id = $row['problem_id'];
$view_user_id = $suser_id = $row['user_id'];
$sip = $row["ip"];

$sql = "SELECT * FROM `source_code` WHERE `solution_id` = ?";
$result = pdo_query($sql, $sid);
$source = $result[0]["source"];

$temp = tempnam(sys_get_temp_dir(), $sid . "_");
$temp_path = $temp . "." . $language_ext[$slanguage];
rename($temp, $temp_path);
file_put_contents($temp_path, $source);

$json = shell_exec("/usr/bin/sim.py $temp_path /home/judge/data/$sproblem_id/ac/ debug json");
$ob = json_decode($json);
$rs = array();

foreach ($ob as $o) {
	$sql = "SELECT * FROM solution WHERE solution_id = ?";
	$result = pdo_query($sql, $o[1]);

	array_push($o, array($result[0]["user_id"], $result[0]["nick"]));
	array_push($rs, $o);
}

header('Content-Type: application/json');
echo json_encode($rs);

unlink($file_path);

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
