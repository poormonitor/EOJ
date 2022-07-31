<?php
$debug = false;
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once('./include/online.php');


if (isset($_POST["add"])) {
	require("./include/check_post_key.php");
	$id = $_POST["add"];
	pdo_query("INSERT INTO `ip` (`ip`, `type`) VALUES (?, 'safe')", $id);
	header("Location: online.php");
	exit(0);
}

if (isset($_POST["del"])) {
	require("./include/check_post_key.php");
	$id = $_POST["del"];
	pdo_query("DELETE FROM `ip` WHERE `ip` = ?", $id);
	echo "success";
	exit(0);
}

$ips = pdo_query("SELECT * FROM `ip`");

$on = new online();
$view_title = $OJ_NAME;
require_once('./include/iplocation.php');
$ip = new IpLocation();
$users = $on->getAll();
$view_online = array();

if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
	$sql = "SELECT * FROM `loginlog`";
	$search = isset($_GET['search']) ? $_GET['search'] : '';
	if ($search != '') {
		$sql = $sql . " WHERE ip like ? ";
		$search = "%$search%";
	} else {
		$sql = $sql . " where user_id<>? ";
		$search = $_SESSION[$OJ_NAME . '_' . 'user_id'];
	}
	$sql = $sql . "  order by `time` desc LIMIT 0,50";

	$result = pdo_query($sql, $search);
	$i = 0;
} else {
	$sql = "SELECT * FROM `loginlog`";
	$result = pdo_query($sql);
}

foreach ($result as $row) {

	$view_online[$i][0] = "<a href='userinfo.php?user=" . htmlentities($row[0], ENT_QUOTES, "UTF-8") . "'>" . htmlentities($row[0], ENT_QUOTES, "UTF-8") . "</a>";
	$view_online[$i][1] = htmlentities($row[1], ENT_QUOTES, "UTF-8");
	$view_online[$i][2] = htmlentities($row[2], ENT_QUOTES, "UTF-8");
	$view_online[$i][3] = htmlentities($row[3], ENT_QUOTES, "UTF-8");

	$i++;
}


require("template/online.php");

