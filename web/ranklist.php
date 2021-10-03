<?php
$OJ_CACHE_SHARE = false;
$cache_time = 30;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once("./include/my_func.inc.php");
require_once('./include/setlang.php');
require_once('./include/memcache.php');
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
        header("location:loginpage.php");
        exit(0);
}
if (isset($OJ_NOIP_KEYWORD) && $OJ_NOIP_KEYWORD) {
        $now = strftime("%Y-%m-%d %H:%M", time());
        $sql = "select count(contest_id) from contest where start_time<'$now' and end_time>'$now' and title like '%$OJ_NOIP_KEYWORD%'";
        $row = pdo_query($sql);
        $cols = $row[0];
        //echo $sql;
        //echo $cols[0];
        if ($cols[0] > 0) {

                $view_errors =  "<h2> $MSG_NOIP_WARNING </h2>";
                require("template/" . $OJ_TEMPLATE . "/error.php");
                exit(0);
        }
}
$view_title = $MSG_RANKLIST;

$scope = "";
if (isset($_GET['scope']))
        $scope = $_GET['scope'];
if ($scope != "" && $scope != 'd' && $scope != 'w' && $scope != 'm')
        $scope = 'y';
$where = "";
if (isset($_GET['prefix'])) {
        $prefix = $_GET['prefix'];
        $where = "where user_id like ?";
} else {
        $where = "where defunct='N' ";
}
$rank = 0;
$start = 0;
if (isset($_GET['start'])) {
        $rank = intval($_GET['start']);
        $start = intval($_GET['start']);
}
if (isset($OJ_LANG)) {
        require_once("./lang/$OJ_LANG.php");
}
$page_size = 50;
//$rank = intval ( $_GET ['start'] );
if ($rank < 0)
        $rank = 0;

$sql = "SELECT `user_id`,`nick`,`solved`,`submit` FROM `users` $where ORDER BY `solved` DESC,submit,reg_time  LIMIT  " . strval($rank) . ",$page_size";

if ($scope) {
        $s = "";
        switch ($scope) {
                case 'd':
                        $s = date('Y') . '-' . date('m') . '-' . date('d');
                        break;
                case 'w':
                        $monday = mktime(0, 0, 0, date("m"), date("d") - (date("w") + 7) % 8 + 1, date("Y"));
                        //$monday->subDays(date('w'));
                        $s = strftime("%Y-%m-%d", $monday);
                        break;
                case 'm':
                        $s = date('Y') . '-' . date('m') . '-01';;
                        break;
                default:
                        $s = date('Y') . '-01-01';
        }
        //echo $s."<-------------------------";
        $sql = "SELECT users.`user_id`,`nick`,s.`solved`,t.`submit` FROM `users`
                                        inner join
                                        (select count(distinct problem_id) solved ,user_id from solution 
						where in_date>str_to_date('$s','%Y-%m-%d') and result=4 
						group by user_id order by solved desc limit " . strval($rank) . ",$page_size) s 
					on users.user_id=s.user_id
                                        inner join
                                        (select count( problem_id) submit ,user_id from solution 
						where in_date>str_to_date('$s','%Y-%m-%d') 
						group by user_id order by submit desc ) t 
					on users.user_id=t.user_id
                                ORDER BY s.`solved` DESC,t.submit,reg_time  LIMIT  0,50
                         ";
        //                      echo $sql;
}




if (isset($_GET['prefix'])) {
        if (is_valid_user_name($_GET['prefix'])) {
                $result = pdo_query($sql, $_GET['prefix'] . "%");
        } else {
                $view_errors =  "<h2>invalid user name prefix</h2>";
                require("template/" . $OJ_TEMPLATE . "/error.php");
                exit(0);
        }
} else {
        $result = mysql_query_cache($sql);
}
if ($result) $rows_cnt = count($result);
else $rows_cnt = 0;
$view_rank = array();
$i = 0;
for ($i = 0; $i < $rows_cnt; $i++) {

        $row = $result[$i];

        $rank++;

        $view_rank[$i][0] = $rank;
        $view_rank[$i][1] =  "<div class=center><a href='userinfo.php?user=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "'>" . $row['user_id'] . "</a>" . "</div>";
        $view_rank[$i][2] =  "<div class=center>" . htmlentities($row['nick'], ENT_QUOTES, "UTF-8") . "</div>";
        $view_rank[$i][3] =  "<div class=center><a href='status.php?user_id=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "&jresult=4'>" . $row['solved'] . "</a>" . "</div>";
        $view_rank[$i][4] =  "<div class=center><a href='status.php?user_id=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "'>" . $row['submit'] . "</a>" . "</div>";

        if ($row['submit'] == 0)
                $view_rank[$i][5] = "0.00%";
        else
                $view_rank[$i][5] = sprintf("%.02lf%%", 100 * $row['solved'] / $row['submit']);

        //                      $i++;
}

$sql = "SELECT count(1) as `mycount` FROM `users`";
//        $result = mysql_query ( $sql );
// require("./include/memcache.php");
$result = mysql_query_cache($sql);
$row = $result[0];
$view_total = $row['mycount'];




/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/ranklist.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
