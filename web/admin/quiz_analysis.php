<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
if (isset($_GET['qid'])) {
    $qid = intval($_GET['qid']);
    $sql = "SELECT * FROM quiz WHERE `quiz_id`=? LIMIT 1";
    $result = pdo_query($sql, $qid);

    $row = $result[0];

    $title = $row['title'];
    $private = intval($row['private']);
    $description = $row['description'];
    $type = explode("/", $row['type']);
    $question = explode("<sep />", $row['question']);
    $answer = explode("/", $row['correct_answer']);
    $score = explode("/", $row['score']);
    $num = count($type);
    $starttime = $row['start_time'];
    $endtime = $row['end_time'];
    $ana = new Analysis($type, $score);

    $sql = "SELECT * FROM `answer` JOIN `users` 
            ON `answer`.`user_id` = `users`.`user_id` 
            WHERE `answer`.quiz_id = ?";
    $result = pdo_query($sql, $qid);

    if (!count($result)){
        header("Location: quiz_list.php");
        exit(0);
    }

    $all_answers = array();
    foreach ($result as $row) {
        $user_id = $row['user_id'];
        $nick = $row['nick'];
        $total = $row['total'];
        $ans = explode("/", $row['answer']);
        $sc = explode("/", $row['score']);
        $ans = new Answer($ans, $sc, $user_id, $nick, $total);
        $ana->add_answer($ans);
        $all_answers[] = $ans;
    }
} else {
    header("Location: quiz_list.php");
    exit(0);
}
require_once("admin-header.php");
?>
<script>
    var score_options = [];
    var choice_options = [];
</script>
<?php echo "<center><h3>" . $MSG_QUIZ . "-" . $MSG_ANALYSIS . "</h3></center>"; ?>
<div class='container'>
    <div class='table-responsive'>
        <table width=100% class="table">
            <thead>
                <tr>
                    <th><?php echo $MSG_QUIZ_ID ?></th>
                    <th><?php echo $MSG_QUIZ_TITLE ?></th>
                    <th><?php echo $MSG_ANSWERED_NUMBER ?></th>
                    <th><?php echo $MSG_AVERAGE_SCORE ?></th>
                    <th><?php echo $MSG_MAX_SCORE ?></th>
                    <th><?php echo $MSG_MIN_SCORE ?></th>
                    <th><?php echo $MSG_SCORE_DIFF ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $qid ?>
                    </td>
                    <td>
                        <?php echo $title ?>
                    </td>
                    <td>
                        <?php echo $ana->get_answered_num() ?>
                    </td>
                    <td>
                        <?php echo round($ana->get_average(), 3) ?>
                    </td>
                    <td>
                        <?php echo max($ana->sum_score) ?>
                    </td>
                    <td>
                        <?php echo min($ana->sum_score) ?>
                    </td>
                    <td>
                        <?php echo round($ana->get_diff(), 3) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class='table-responsive'>
        <table width=100% class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php echo $MSG_TYPE ?></th>
                    <th><?php echo $MSG_QUIZ_PROBLEM ?></th>
                    <th><?php echo $MSG_CORRECT_ANSWER ?></th>
                    <th><?php echo $MSG_SCORE ?></th>
                    <th><?php echo $MSG_SCORE_ANALYSIS ?></th>
                    <th><?php echo $MSG_AVERAGE_SCORE ?></th>
                    <th><?php echo $MSG_SCORE_RATE ?></th>
                    <th><?php echo $MSG_CHOICE_ANALYSIS ?></th>
                    <th><?php echo $MSG_CORRECT_RATE ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < $num; $i++) {
                    echo "<tr>";
                    echo "<td>" . ($i + 1) . "</td>";
                    echo "<td>" . $MSG_QUIZ_TYPE[intval($type[$i])] . "</td>";
                    echo "<td>" . $question[$i] . "</td>";
                    echo "<td>" . $answer[$i] . "</td>";
                    echo "<td>" . $score[$i] . "</td>";
                    echo "<td>";
                ?>
                    <div id='score<?php echo $i ?>' style="width:160px;height:160px;"></div>
                    <?php
                    $options_ana = array();
                    foreach ($ana->get_score_num($i) as $key => $value) {
                        array_push($options_ana, array('value' => $value, 'name' => $key . "(" . $value . ")"));
                    }
                    ?>
                    <script>
                        score_options[<?php echo $i ?>] = {
                            series: [{
                                type: 'pie',
                                avoidLabelOverlap: false,
                                itemStyle: {
                                    borderRadius: 10,
                                    borderColor: '#fff',
                                    borderWidth: 2
                                },
                                label: {
                                    show: false,
                                    position: 'center'
                                },
                                emphasis: {
                                    label: {
                                        show: true,
                                        fontSize: '40',
                                        fontWeight: 'bold'
                                    }
                                },
                                labelLine: {
                                    show: false
                                },
                                data: <?php echo json_encode($options_ana) ?>,
                            }]
                        };
                    </script>
                    <?php
                    echo "</td>";
                    echo "<td>" . round($ana->get_problem_average($i), 3) . "</td>";
                    echo "<td>" . round($ana->get_problem_average($i) / $score[$i], 3) . "</td>";
                    echo "<td>";
                    ?>
                    <div id='choice<?php echo $i ?>' style="width:160px;height:160px;"></div>
                    <?php
                    $options_ana = array();
                    foreach ($ana->get_choice_num($i) as $key => $value) {
                        array_push($options_ana, array('value' => $value, 'name' => $key . "(" . $value . ")"));
                    }
                    ?>
                    <script>
                        choice_options[<?php echo $i ?>] = {
                            series: [{
                                type: 'pie',
                                avoidLabelOverlap: false,
                                itemStyle: {
                                    borderRadius: 10,
                                    borderColor: '#fff',
                                    borderWidth: 2
                                },
                                label: {
                                    show: false,
                                    position: 'center'
                                },
                                emphasis: {
                                    label: {
                                        show: true,
                                        fontSize: '40',
                                        fontWeight: 'bold'
                                    }
                                },
                                labelLine: {
                                    show: false
                                },
                                data: <?php echo json_encode($options_ana) ?>,
                            }]
                        };
                    </script>
                <?php
                    echo "</td>";
                    echo "<td>" . round($ana->get_choice_rate($i, $answer[$i]), 3) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class='table-responsive'>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th><?php echo $MSG_SUBMITTED ?></th>
                    <th><?php echo $MSG_NOT_SUBMITTED ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><?php echo $MSG_USER_ID ?></th>
                                    <th><?php echo $MSG_NICK ?></th>
                                    <th><?php echo $MSG_SCORE ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($all_answers as $submit) {
                                    $user = $submit->user;
                                    echo "<tr>";
                                    echo "<td>" . $user . "</td>";
                                    echo "<td>" . $submit->nick . "</td>";
                                    echo "<td><a href='../quiz.php?qid=$qid&user_id=$user' target='view_window'>" .
                                        $submit->total . "</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <?php if ($private) {
                            $sql = "SELECT * FROM `privilege`  JOIN `users` ON `privilege`.user_id = `users`.user_id 
                                WHERE `privilege`.rightstr = ? AND `privilege`.user_id NOT IN 
                                (SELECT `user_id` FROM `answer` WHERE `quiz_id`= ?)";
                            $result = pdo_query($sql, "q$qid", $qid);
                        ?>
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th><?php echo $MSG_USER_ID ?></th>
                                        <th><?php echo $MSG_NICK ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($result as $submit) {
                                        echo "<tr>";
                                        echo "<td>" . $submit["user_id"] . "</td>";
                                        echo "<td>" . $submit["nick"] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </td>
                </tr>
        </table>
    </div>


</div>
<br>
<?php
require_once("admin-footer.php");
?>
<script src="../template/echarts.min.js"></script>
<script>
    var score_charts = [];
    $("div[id^=score]").each(function(index, elem) {
        score_charts[index] = echarts.init(elem);
        score_charts[index].setOption(score_options[index]);
    });
    var choice_charts = [];
    $("div[id^=choice]").each(function(index, elem) {
        choice_charts[index] = echarts.init(elem);
        choice_charts[index].setOption(choice_options[index]);
    });
</script>