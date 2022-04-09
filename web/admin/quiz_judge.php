<?php
require_once("../include/db_info.inc.php");
require_once("../lang/$OJ_LANG.php");
require_once("../include/const.inc.php");
$description = "";
if (!(isset($_SESSION[$OJ_NAME . '_' . "mq$cid"]) || isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
    header("Location: quiz_list.php");
    exit(0);
};
if (isset($_POST['qid'])) {
    require_once("../include/check_post_key.php");
    $qid = intval($_POST['qid']);
    $sql = "SELECT * FROM quiz WHERE `quiz_id`=?";
    $result = pdo_query($sql, $qid);
    $row = $result[0];
    $type = explode("/", $row['type']);
    $type = array_map('intval', $type);
    $score = explode("/", $row['score']);
    $score = array_map('intval', $score);
    $num = count($type);

    $sql = "SELECT * FROM `answer` WHERE `quiz_id`=? AND `judged` = 0 ORDER BY `aid` ASC LIMIT 1";
    $result = pdo_query($sql, $qid);
    $row = $result[0];
    $aid = $row['aid'];
    $n_score = array_map("intval", explode("/", $row['score']));
    for ($i = 0; $i < $num; $i++) {
        $pid = $i + 1;
        if ($type[$i] == 3) {
            $n_score[$i] = intval($_POST[$pid]);
        }
    }
    $n_score = join("/", $n_score);
    $sql = "UPDATE `answer` SET `score`=?, `judged`=1, `judgetime`=NOW() WHERE `aid`=?";
    pdo_query($sql, $n_score, $aid);
    //exit(0);
    header("Location: quiz_judge.php?qid=$qid");
    exit(0);
} else if (isset($_GET['qid'])) {
    $qid = intval($_GET['qid']);
    $sql = "SELECT * FROM quiz WHERE `quiz_id`=?";
    $result = pdo_query($sql, $qid);
    $row = $result[0];
    $title = $row['title'];
    $private = $row['private'];
    $description = $row['description'];
    $type = explode("/", $row['type']);
    $type = array_map('intval', $type);
    $question = explode("<sep />", $row['question']);
    $c_answer = explode("/", $row['correct_answer']);
    $score = explode("/", $row['score']);
    $num = count($type);
    $starttime = $row['start_time'];
    $endtime = $row['end_time'];

    $sql = "SELECT * FROM `answer` WHERE `quiz_id`=? AND `judged` = 0 ORDER BY `aid` ASC LIMIT 1";
    $result = pdo_query($sql, $qid);
    $sql = "SELECT COUNT(*) FROM `answer` WHERE `quiz_id`=? AND `judged`=0";
    $left = intval(pdo_query($sql, $qid)[0][0]);
    if ($result) {
        $row = $result[0];
        $aid = $row['aid'];
        $answer = $row['answer'];
        $answer = explode("/", $answer);
    } else {
        header("Location: quiz_list.php?judge_over=true");
        exit(0);
    }
} else {
    header("Location: quiz_list.php");
    exit(0);
}

require_once("admin-header.php");
?>
<?php echo "<center><h3>" . $MSG_QUIZ . "-" . $MSG_QUIZ_JUDGE . "</h3></center>"; ?>
<style>
    input[type=date],
    input[type=time],
    input[type=datetime-local],
    input[type=month] {
        line-height: normal;
    }
</style>
<div class="container">
    <form method=POST>
        <p align=left>
            <input type='hidden' name='qid' value='<?php echo $qid; ?>'>
        <h3><?php echo $MSG_QUIZ . "-" . $MSG_QUIZ_ID . ":" . $qid ?></h3>
        <h3><?php echo $MSG_JUDGE_LEFT ?> : <?php echo $left ?></h3>
        </p>
        <br>
        <p>
            <?php
            for ($i = 0; $i < $num; $i++) {
                if ($type[$i] == 3) {
                    $pid = $i + 1;
            ?>
        <table class='table'>
            <thead>
                <tr>
                    <th><?php echo $MSG_QUIZ_PROBLEM ?></th>
                    <th><?php echo $MSG_QUIZ_PROBLEM_INFORMATION ?></th>
                    <th><?php echo $MSG_CORRECT_ANSWER ?></th>
                    <th><?php echo $MSG_QUIZ_ANSWER ?></th>
                    <th><?php echo $MSG_SCORE ?></th>
                    <th><?php echo $MSG_QUIZ_SCORE ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $qid; ?></td>
                    <td><?php echo $question[$i]; ?></td>
                    <td><?php echo $c_answer[$i]; ?></td>
                    <td><?php echo $answer[$i]; ?></td>
                    <td><a class='label label-primary' onclick='$("input[name=<?php echo $pid ?>]").val(this.innerText)'><?php echo $score[$i]; ?></a></td>
                    <td width=20%>
                        <input class='form-control' name='<?php echo $pid ?>' required>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
                }
            }
?>
</p>
<br>
<div align=center>
    <?php require_once("../include/set_post_key.php"); ?>
    <input type=submit class='form-control' value='<?php echo $MSG_SAVE ?>' name=submit>
</div>
    </form>
</div>

<?php
require_once("../oj-footer.php");
require_once('../tinymce/tinymce.php'); ?>
<?php
require_once("admin-footer.php");
?>