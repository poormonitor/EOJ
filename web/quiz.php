<?php
if (isset($_POST['keyword']))
    $cache_time = 1;
else
    $cache_time = 10;

$OJ_CACHE_SHARE = false; //!(isset($_GET['qid'])||isset($_GET['my']));
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/memcache.php');
require_once('./include/my_func.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
$view_title = $MSG_QUIZ;

function formatTimeLength($length)
{
    $hour = 0;
    $minute = 0;
    $second = 0;
    $result = '';

    global $MSG_SECONDS, $MSG_MINUTES, $MSG_HOURS, $MSG_DAYS;

    if ($length >= 60) {
        $second = $length % 60;

        if ($second > 0 && $second < 10) {
            $result = '0' . $second . ' ' . $MSG_SECONDS;
        } else if ($second > 0) {
            $result = $second . ' ' . $MSG_SECONDS;
        }

        $length = floor($length / 60);
        if ($length >= 60) {
            $minute = $length % 60;

            if ($minute == 0) {
                if ($result != '') {
                    $result = '00' . ' ' . $MSG_MINUTES . ' ' . $result;
                }
            } else if ($minute > 0 && $minute < 10) {
                if ($result != '') {
                    $result = '0' . $minute . ' ' . $MSG_MINUTES . ' ' . $result;
                }
            } else {
                $result = $minute . ' ' . $MSG_MINUTES . ' ' . $result;
            }

            $length = floor($length / 60);

            if ($length >= 24) {
                $hour = $length % 24;

                if ($hour == 0) {
                    if ($result != '') {
                        $result = '00' . ' ' . $MSG_HOURS . ' ' . $result;
                    }
                } else if ($hour > 0 && $hour < 10) {
                    if ($result != '') {
                        $result = '0' . $hour . ' ' . $MSG_HOURS . ' ' . $result;
                    }
                } else {
                    $result = $hour . ' ' . $MSG_HOURS . ' ' . $result;
                }

                $length = floor($length / 24);
                $result = $length . $MSG_DAYS . ' ' . $result;
            } else {
                $result = $length . ' ' . $MSG_HOURS . ' ' . $result;
            }
        } else {
            $result = $length . ' ' . $MSG_MINUTES . ' ' . $result;
        }
    } else {
        $result = $length . ' ' . $MSG_SECONDS;
    }
    return $result;
}
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    $privilege = pdo_query("SELECT `rightstr` from `privilege` where `user_id`=? and `rightstr` regexp 'q.*' AND `valuestr`='true'", $_SESSION[$OJ_NAME . '_' . 'user_id']);
    $allow = array();
    foreach ($privilege as $i) {
        array_push($allow, substr($i['rightstr'], 1));
    }
}
//print_r($privilege);
//print_r($allow);
if (isset($_GET['qid'])) {
    $qid = intval($_GET['qid']);
    $view_qid = $qid;
    //print $qid;

    //check quiz valid
    $sql = "SELECT * FROM `quiz` WHERE `quiz_id`=?";
    $result = pdo_query($sql, $qid);

    $rows_cnt = count($result);
    $quiz_ok = true;
    $password = "";

    if (isset($_POST['password']))
        $password = $_POST['password'];

    $password = stripslashes($password);

    if ($rows_cnt == 0) {
        $view_error_title = "测试不存在！";
        $view_errors = "当前测试不存在，请您检查编号是否正确。";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    } else {
        $row = $result[0];
        $view_private = $row['private'];

        if ($password != "" && $password == $row['password'])
            $_SESSION[$OJ_NAME . '_' . 'q' . $qid] = true;

        if ($row['private'] && !isset($_SESSION[$OJ_NAME . '_' . 'q' . $qid]))
            $quiz_ok = false;

        //		if($row['defunct']=='Y')  //defunct problem not in quiz/exam list
        //			$quiz_ok = false;

        if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))
            $quiz_ok = true;

        $now = time();
        $start_time = strtotime($row['start_time']);
        $end_time = strtotime($row['end_time']);
        $view_description = $row['description'];
        $view_title = $row['title'];
        $view_start_time = $row['start_time'];
        $view_end_time = $row['end_time'];

        if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && $now < $start_time) {
            $view_errors = "<center>";
            $view_errors .= "<h3>$MSG_QUIZ_ID : $view_qid - $view_title</h3>";
            $view_errors .= "<p>$view_description</p>";
            $view_errors .= "<br />";
            $view_errors .= "<span class=text-success>$MSG_TIME_WARNING</span>";
            $view_errors .= "</center>";
            $view_errors .= "<br /><br />";

            require("template/" . $OJ_TEMPLATE . "/error.php");
            exit(0);
        }
    }

    if (!$quiz_ok) {
        $view_errors = "<center>";
        $view_errors .= "<h3>$MSG_CONTEST_ID : $view_qid - $view_title</h3>";
        $view_errors .= "<p>$view_description</p>";
        $view_errors .= "<span class=text-danger>$MSG_PRIVATE_WARNING</span>";
        $view_errors .= "<br />";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }

    $answered = false;
    if (isset($_SESSION["user_id"])) {
        $sql = "SELECT * FROM `answer` WHERE `quiz_id`=? AND `user_id`=?";
        $answer = pdo_query($sql, $_SESSION["user_id"]);
        $rows_cnt = count($answer);
        if ($rows_cnt) {
            $answered = true;
        }
    }
} else {
    $page = 1;
    if (isset($_GET['page']))
        $page = intval($_GET['page']);

    $page_cnt = 10;
    $pstart = $page_cnt * $page - $page_cnt;
    $pend = $page_cnt;
    if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
        $user_allowed = join(",", $allow);
    }
    $keyword = "";

    if (isset($_POST['keyword'])) {
        $keyword = "%" . $_POST['keyword'] . "%";
    }

    $sql = "SELECT * FROM `quiz` WHERE `defunct`='N' ORDER BY `quiz_id` DESC LIMIT 1000";

    if ($keyword) {
        $sql = "SELECT *  FROM quiz WHERE quiz.defunct='N' AND quiz.title LIKE ? $wheremy  ORDER BY quiz_id DESC";
        $total = count(pdo_query($sql, $keyword));
        $sql .= " limit " . strval($pstart) . "," . strval($pend);

        $result = pdo_query($sql, $keyword);
    } else {
        $sql = "SELECT *  FROM quiz WHERE quiz.defunct='N' $wheremy  ORDER BY quiz_id DESC";
        $total = count(pdo_query($sql));
        $sql .= " limit " . strval($pstart) . "," . strval($pend);
        //echo $sql;
        $result = mysql_query_cache($sql);
    }
    $view_total_page = intval($total / $page_cnt) + 1;
    $view_quiz = array();
    $i = 0;

    foreach ($result as $row) {
        $view_quiz[$i][0] = $row['quiz_id'];

        if (trim($row['title']) == "")
            $row['title'] = $MSG_CONTEST . $row['quiz_id'];

        $view_quiz[$i][1] = "<a href='quiz.php?qid=" . $row['quiz_id'] . "'>" . $row['title'] . "</a>";
        $start_time = strtotime($row['start_time']);
        $end_time = strtotime($row['end_time']);
        $now = time();

        $length = $end_time - $start_time;
        $left = $end_time - $now;

        if ($end_time <= $now) {
            //past
            $view_quiz[$i][2] = "<span class=text-muted>$MSG_Ended</span>" . " " . "<span class=text-muted>" . $row['end_time'] . "</span>";
        } else if ($now < $start_time) {
            //pending
            $view_quiz[$i][2] = "<span class=text-success>$MSG_Start</span>" . " " . $row['start_time'] . "&nbsp;";
            $view_quiz[$i][2] .= "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($length);
        } else {
            //running
            $view_quiz[$i][2] = "<span class=text-danger>$MSG_Running</span>" . " " . $row['start_time'] . "&nbsp;";
            $view_quiz[$i][2] .= "<span class=text-danger>$MSG_LeftTime</span>" . " " . formatTimeLength($left) . "</span>";
        }

        $private = intval($row['private']);
        if ($private == 0)
            $view_quiz[$i][4] = "<span class=text-primary>$MSG_Public</span>";
        else
            $view_quiz[$i][5] = "<span class=text-danger>$MSG_Private</span>";

        $view_quiz[$i][6] = $row['user_id'];

        $i++;
    }
}


if (isset($_GET['qid']))
    require("template/" . $OJ_TEMPLATE . "/quiz.php");
else
    require("template/" . $OJ_TEMPLATE . "/quizset.php");

if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
