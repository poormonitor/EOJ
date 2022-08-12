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

if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    $allow = array();
    $pattern = "/" . $OJ_NAME . "_q([\d]+)/";
    foreach ($_SESSION as $key => $value) {
        if (preg_match($pattern, $key, $matches)) {
            array_push($allow, $matches[1]);
        }
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
        $view_swal = "$MSG_NOT_EXISTED";
        require("template/error.php");
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
        $quiz_total = array_sum(array_map("intval", explode("/", $row['score'])));

        if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && $now < $start_time) {
            $view_errors = "<center>";
            $view_errors .= "<h3>$MSG_QUIZ_ID : $view_qid - $view_title</h3>";
            $view_errors .= "<p>$view_description</p>";
            $view_errors .= "<br>";
            $view_errors .= "<span class=text-success>$MSG_TIME_WARNING</span>";
            $view_errors .= "</center>";
            $view_errors .= "<br><br>";

            require("template/error.php");
            exit(0);
        }
    }

    if (!$quiz_ok) {
        $view_errors = "<center>";
        $view_errors .= "<h3>$MSG_QUIZ_ID : $view_qid - $view_title</h3>";
        $view_errors .= "<p>$view_description</p>";
        $view_errors .= "<span class=text-danger>$MSG_PRIVATE_WARNING</span>";
        $view_errors .= "<br>";
        require("template/error.php");
        exit(0);
    }

    $answered = false;
    if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
        $not_my = false;
        if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            $not_my = true;
        } else {
            $user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
        }
        $sql = "SELECT * FROM `answer` JOIN `users` ON `users`.`user_id` = `answer`.`user_id`
                WHERE `answer`.`quiz_id`=? AND `answer`.`user_id`=?";

        $answer = pdo_query($sql, $qid, $user_id);
        $rows_cnt = count($answer);
        if ($rows_cnt) {
            $answered = true;
            $sql = "SELECT * FROM `quiz` WHERE `quiz_id`=?";
            $result = pdo_query($sql, $qid);
            $quiz = $result[0];
            $answer = $answer[0];
        } else {
            if ($not_my) {
                $view_swal = $MSG_NOT_FINISHED;
                $error_location = "admin/quiz_analysis.php?qid=$qid";
                require("template/error.php");
                exit(0);
            }
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

    $wheremy = "";
    if (isset($_SESSION[$OJ_NAME . '_user_id'])) {
        if (isset($user_allowed) && !isset($_SESSION[$OJ_NAME . '_' . "administrator"])) {
            $wheremy = " and (quiz_id in ($user_allowed) or private=0)";
        }
    }

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
    $view_total_page = $total > 0 ? intval(($total - 1) / $page_cnt) + 1 : 1;
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
            $view_quiz[$i][2] .= "<span class=text-danger>$MSG_LeftTime</span> <span class='time-left'>" . formatTimeLength($left) . "</span>";
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
    require("template/quiz.php");
else
    require("template/quizset.php");

if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
