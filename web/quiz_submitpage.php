<?php
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/memcache.php');
require_once('./include/my_func.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
$view_title = $MSG_QUIZ;


if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    if (isset($OJ_GUEST) && $OJ_GUEST) {
        $_SESSION[$OJ_NAME . '_' . 'user_id'] = "Guest";
    } else {
        $view_swal = "需要登陆";
        $error_location = "loginpage.php";
        require("template/error.php");
        exit(0);
    }
}

$problem_id = 1000;
if (isset($_GET['qid'])) {
    $id = intval($_GET['qid']);

    $sql = "SELECT * FROM `answer` WHERE `quiz_id`=? AND `user_id`=?";
    $answered = pdo_query($sql, $id, $_SESSION[$OJ_NAME . '_' . 'user_id']);
    $rows_cnt = count($answered);
    if ($rows_cnt) {
        $view_swal = $MSG_ALREADY_SUBMIT;
        $error_location = "quiz.php?qid=$id";
        require("template/error.php");
        exit(0);
    }
    
    if (
        isset($_SESSION[$OJ_NAME . '_' . 'administrator']) ||
        isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) ||
        isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) ||
        isset($_SESSION[$OJ_NAME . '_' . "q$id"])
    )
        $sql = "SELECT * FROM `quiz` WHERE `quiz_id`=?";
    else
        $sql = "SELECT * FROM `quiz` WHERE `quiz_id`=? AND `defunct`='N' AND `end_time`> now() AND `private`=0";
    $result = pdo_query($sql, $id);
    if (count($result) != 1) {
        $view_swal = $MSG_NO_SUCH_PROBLEM;
        require("template/error.php");
        exit(0);
    }
    $result = $result[0];
    $question = explode("<sep />", $result['question']);
    $type = explode("/", $result['type']);
    $score = explode("/", $result['score']);
    /*
    defination of type:
    0: single choice
    1: multiple choice
    2: short answer
    3: human judge
    */
} else {
    $view_swal = "题目不存在！";
    require("template/error.php");
    exit(0);
}

require("template/quiz_submitpage.php");