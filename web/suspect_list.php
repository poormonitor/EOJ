<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

////////////////////////////Common head
$cache_time = 2;
$OJ_CACHE_SHARE = false;

require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/memcache.php');
require_once("./include/const.inc.php");
require_once('./include/setlang.php');

if (isset($OJ_LANG)) {
  require_once("./lang/$OJ_LANG.php");
}

function formatTimeLength($length) {
  $hour = 0;
  $minute = 0;
  $second = 0;
  $result = '';

  global $MSG_SECONDS, $MSG_MINUTES, $MSG_HOURS, $MSG_DAYS;

  if ($length>=60) {
    $second = $length%60;
    
    if ($second>0 && $second<10) {
    	$result = '0'.$second.' '.$MSG_SECONDS;}
    else if ($second>0) {
    	$result = $second.' '.$MSG_SECONDS;
    }

    $length = floor($length/60);
    if ($length >= 60) {
      $minute = $length%60;
      
      if ($minute==0) {
      	if ($result != '') {
      		$result = '00'.' '.$MSG_MINUTES.' '.$result;
      	}
      }
      else if ($minute>0 && $minute<10) {
      	if ($result != '') {
      		$result = '0'.$minute.' '.$MSG_MINUTES.' '.$result;}
				}
				else {
					$result = $minute.' '.$MSG_MINUTES.' '.$result;
				}
				
				$length = floor($length/60);

				if ($length >= 24) {
					$hour = $length%24;

				if ($hour==0) {
					if ($result != '') {
						$result = '00'.' '.$MSG_HOURS.' '.$result;
					}
				}
				else if ($hour>0 && $hour<10) {
					if($result != '') {
						$result = '0'.$hour.' '.$MSG_HOURS.' '.$result;
					}
				}
				else {
					$result = $hour.' '.$MSG_HOURS.' '.$result;
				}

				$length = floor($length / 24);
				$result = $length .$MSG_DAYS.' '.$result;
			}
			else {
				$result = $length.' '.$MSG_HOURS.' '.$result;
			}
		}
		else {
			$result = $length.' '.$MSG_MINUTES.' '.$result;
		}
	}
	else {
		$result = $length.' '.$MSG_SECONDS;
	}
	return $result;
}

if (isset($_GET['cid'])) {
	$cid = intval($_GET['cid']);
	$view_cid = $cid;
	//print $cid;

	//check contest valid
	$sql = "SELECT * FROM `contest` WHERE `contest_id`=?";
	$result = pdo_query($sql,$cid);

	$rows_cnt = count($result);
	$contest_ok = true;
	$password = "";

	if (isset($_POST['password']))
		$password = $_POST['password'];

		$password = stripslashes($password);

	if ($rows_cnt==0) {
		$view_title = "比赛已经关闭!";
	}
	else{
		$row = $result[0];
		$view_private = $row['private'];

		if ($password!="" && $password==$row['password'])
			$_SESSION[$OJ_NAME.'_'.'c'.$cid] = true;

		if ($row['private'] && !isset($_SESSION[$OJ_NAME.'_'.'c'.$cid]))
			$contest_ok = false;

		if($row['defunct']=='Y')
			$contest_ok = false;

		if (isset($_SESSION[$OJ_NAME.'_'.'administrator']))
			$contest_ok = true;

		$now = time();
		$start_time = strtotime($row['start_time']);
		$end_time = strtotime($row['end_time']);
		$view_description = $row['description'];
		$view_title = $row['title'];
		$view_start_time = $row['start_time'];
		$view_end_time = $row['end_time'];

		if (!isset($_SESSION[$OJ_NAME.'_'.'administrator']) && $now<$start_time) {
			$view_errors = "<center>";
			$view_errors .= "<h3>$MSG_CONTEST_ID : $view_cid - $view_title</h3>";
			$view_errors .= "<p>$view_description</p>";
			$view_errors .= "<br>";
			$view_errors .= "<span class=text-success>$MSG_TIME_WARNING</span>";
			$view_errors .= "</center>";
			$view_errors .= "<br><br>";

			require("template/error.php");
			exit(0);
		}
	}

	if (!$contest_ok) {
		$view_errors = "<center>";
		$view_errors .= "<h3>$MSG_CONTEST_ID : $view_cid - $view_title</h3>";
		$view_errors .= "<p>$view_description</p>";
		$view_errors .= "<span class=text-danger>$MSG_PRIVATE_WARNING</span>";

		$view_errors .= "<br><br>";

		$view_errors .= "<div class='btn-group'>";
		$view_errors .= "<a href=contestrank.php?cid=$view_cid class='btn btn-primary'>$MSG_STANDING</a>";
		$view_errors .= "<a href=contestrank-oi.php?cid=$view_cid class='btn btn-primary'>OI$MSG_STANDING</a>";
		$view_errors .= "<a href=conteststatistics.php?cid=$view_cid class='btn btn-primary'>$MSG_STATISTICS</a>";
		$view_errors .= "</div>";
		$view_errors .= "<br>";

		require("template/error.php");
		exit(0);
	}
}

//require_once("./include/set_get_key.php");
if (isset($_GET['cid'])) {
	$contest_id = intval($_GET['cid']);
	
	$sql = "select * from (select count(distinct user_id) c,ip from solution where contest_id=? and ip not in (select ip from ip where type = 'safe') group by ip) suspect inner join (select distinct ip, user_id, in_date from solution where contest_id=? ) u on suspect.ip=u.ip and suspect.c>1 order by c desc, u.ip, in_date, user_id";

	$result1 = pdo_query($sql,$contest_id,$contest_id);

	$start = pdo_query("select start_time from contest where contest_id=?",$contest_id)[0][0];
	$end = pdo_query("select end_time from contest where contest_id=?",$contest_id)[0][0];
	$sql = "select * from (select count(distinct ip) c,user_id from loginlog where time>=? and time<=? and ip not in (select ip from ip where type = 'safe') group by user_id) suspect inner join (select distinct user_id from solution where contest_id=? ) u on suspect.user_id=u.user_id and suspect.c>1 inner join (select distinct ip, user_id, time from loginlog where time>=? and time<=? and ip ) ips on ips.user_id=u.user_id order by c desc, u.user_id, ips.time, ip";

	$result2 = pdo_query($sql,$start,$end,$contest_id,$start,$end);
}


if (isset($_GET['cid'])) {
    if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])) {
    require("template/suspect_list.php");
    }
    else {
    $view_swal = $MSG_WARNING_ACCESS_DENIED;
    require("template/error.php");
    exit(0);
    }
}
  


if (file_exists('./include/cache_end.php') )
  require_once('./include/cache_end.php');
?>