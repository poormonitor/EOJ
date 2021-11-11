<?php
$OJ_CACHE_SHARE = false;
$cache_time = 0;

require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
//require_once('./include/cache_start.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');

$view_title = "Problem Set";
$first = 1000;
//if($OJ_SAE) $first=1;
$sql = "select max(`problem_id`) as upid FROM `problem`";
$page_cnt = 50;  //50 prlblems per page
$result = mysql_query_cache($sql);
$row = $result[0];
$cnt = $row['upid'] - $first;
$cnt = $cnt / $page_cnt;

//remember page
$page = 1;
if (isset($_GET['page'])) {
	$page = intval($_GET['page']);

	if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
		$sql = "update users set volume=? where user_id=?";
		pdo_query($sql, $page, $_SESSION[$OJ_NAME . '_' . 'user_id']);
	}
} else {
	if (isset($_GET['search']) && trim($_GET['search']) != "") {
		$page = 1;
	} elseif (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
		$sql = "select volume from users where user_id=?";
		$result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);
		$row = $result[0];
		$page = intval($row[0]);
	} else {
		$page = 1;
	}

	if (!is_numeric($page) || $page <= 0)
		$page = 1;
}
//end of remember page
$pstart = $first + $page_cnt * intval($page) - $page_cnt;
$pend = $pstart + $page_cnt;

//all submit
$sub_arr = array();
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$sql = "SELECT `problem_id` FROM `solution` WHERE `user_id`=? GROUP BY `problem_id`";
	$result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);

	foreach ($result as $row)
		$sub_arr[$row[0]] = true;
}

//all ac
$acc_arr = array();
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$sql = "SELECT `problem_id` FROM `solution` WHERE `user_id`=? AND `result`=4 GROUP BY `problem_id`";
	$result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);

	foreach ($result as $row)
		$acc_arr[$row[0]] = true;
}

if (isset($_GET['search']) && trim($_GET['search']) != "") {
	$search = "%" . ($_GET['search']) . "%";
	$filter_sql = " ( title like ? or source like ?)";
	$pstart = 0;
	$pend = 100;
} else {
	$filter_sql = " `problem_id`>='" . strval($pstart) . "' AND `problem_id`<'" . strval($pend) . "' ";
}

if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {  //all problems
	$sql = "SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `problem` WHERE $filter_sql ";
} else {  //page problems (not include in contests period)
	$sql = "SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `problem` " .
		"WHERE `defunct`='N' and $filter_sql AND `problem_id` NOT IN (
		SELECT  `problem_id` 
		FROM contest c
			INNER JOIN  `contest_problem` cp ON c.`contest_id` = cp.`contest_id` " .
		" AND (c.`defunct` = 'N' AND c.`start_time`<=NOW() AND NOW()<c.`end_time`)" .    // option style show all non-running contest
		//"and (c.`end_time` >  NOW()  OR c.private =1)" .    // original style , hidden all private contest problems
		")";
}

$sql .= " ORDER BY `problem_id`";
//echo htmlentities( $sql);
if (isset($_GET['search']) && trim($_GET['search']) != "") {
	$result = pdo_query($sql, $search, $search);
} else {
	$result = mysql_query_cache($sql);
}

if (isset($_GET['search']) && trim($_GET['search']) != "") {
	$sql = "SELECT count(*) FROM `problem` WHERE $filter_sql";
	$num = pdo_query($sql, $search, $search)[0][0];
} else {
	$sql = "SELECT count(*) FROM `problem`";
	$num = mysql_query_cache($sql)[0][0];
}

$view_total_page = intval($num / $page_cnt) + 1;

$cnt = 0;
$view_problemset = array();
$i = 0;
foreach ($result as $row) {
	$view_problemset[$i] = array();

	if (isset($sub_arr[$row['problem_id']])) {
		if (isset($acc_arr[$row['problem_id']]))
			$view_problemset[$i][0] = "<div class='label label-success'>Y</div>";
		else
			$view_problemset[$i][0] = "<div class='label label-danger'>N</div>";
	} else {
		$view_problemset[$i][0] = "<div class=none> </div>";
	}

	$category = array();
	$cate = explode(" ", $row['source']);
	foreach ($cate as $cat) {
		array_push($category, trim($cat));
	}

	$view_problemset[$i][1] = "<div fd='problem_id' class='center'>" . $row['problem_id'] . "</div>";
	$view_problemset[$i][2] = "<div class='left'><a href='problem.php?id=" . $row['problem_id'] . "'>" . $row['title'] . "</a></div>";;
	$view_problemset[$i][3] = "<div pid='" . $row['problem_id'] . "' fd='source' class='center'>";

	foreach ($category as $cat) {
		if (trim($cat) == "")
			continue;

		$hash_num = hexdec(substr(md5($cat), 0, 7));
		$label_theme = $color_theme[$hash_num % count($color_theme)];

		if ($label_theme == "")
			$label_theme = "default";

		$view_problemset[$i][3] .= "<a title='" . htmlentities($cat, ENT_QUOTES, 'UTF-8') . "' class='label label-$label_theme' style='display: inline-block;' href='problemset.php?search=" . htmlentities($cat, ENT_QUOTES, 'UTF-8') . "'>" . htmlentities($cat, ENT_QUOTES, 'UTF-8') . "</a>&nbsp;";
	}

	$view_problemset[$i][3] .= "</div >";
	$view_problemset[$i][4] = "<div class='center'><a href='status.php?problem_id=" . $row['problem_id'] . "&jresult=4'>" . $row['accepted'] . "</a></div>";
	$view_problemset[$i][5] = "<div class='center'><a href='status.php?problem_id=" . $row['problem_id'] . "'>" . $row['submit'] . "</a></div>";
	$i++;
}

require("template/" . $OJ_TEMPLATE . "/problemset.php");

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
