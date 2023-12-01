<?php
$cache_time = 60;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
$view_title = $OJ_NAME;
$result = false;

$view_category = "";
$sql =	"select distinct source "
	. "FROM `problem` where defunct='N'"
	. "LIMIT 500";
$result = mysql_query_cache($sql); //mysql_escape_string($sql));
$category = array();
foreach ($result as $row) {
	$cate = explode(" ", $row['source']);
	foreach ($cate as $cat) {
		array_push($category, trim($cat));
	}
}
$category = array_unique($category);
if (!$result) {
	$view_category = "<h3 class='mx-4'>No Category Now!</h3>";
} else {
	$view_category .= "<div style='word-wrap:break-word;'>";
	foreach ($category as $cat) {
		if (trim($cat) == "") continue;
		$hash_num = hexdec(substr(md5($cat), 0, 7));
		$label_theme = $color_theme[$hash_num % count($color_theme)];
		if ($label_theme == "") $label_theme = "default";
		$view_category .= "<a class='label label-$label_theme label-inline' href='problemset.php?search=" . (htmlentities($cat, ENT_QUOTES, 'UTF-8')) . "'>" . htmlentities($cat, ENT_QUOTES, 'utf-8') . "</a>&nbsp;";
	}

	$view_category .= "</div>";
}


require("template/category.php");

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
