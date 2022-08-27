<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
    || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
    $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
    $error_location = "../index.php";
    require("../template/error.php");
    exit(0);
}

if (isset($_GET['qid'])) {
    $qid = intval($_GET['qid']);
    $sql = "SELECT * FROM quiz WHERE `quiz_id`=? LIMIT 1";
    $result = pdo_query($sql, $qid);

    if (!count($result)) {
        header("Location: quiz_list.php");
        exit(0);
    }

    $row = $result[0];

    $title = $row['title'];
    $private = intval($row['private']);
    $description = $row['description'];
    $type = explode("/", $row['type']);
    $question = explode("<sep />", $row['question']);
    $answer = explode("/", $row['correct_answer']);
    $score = array_map("intval", explode("/", $row['score']));
    $score_total = array_sum($score);
    $num = count($type);
    $starttime = $row['start_time'];
    $endtime = $row['end_time'];
    $ana = new Analysis($type, $score);

    $sql = "SELECT * FROM `answer` JOIN `users` 
            ON `answer`.`user_id` = `users`.`user_id`
            JOIN `group` ON `users`.`gid` = `group`.`gid`
            WHERE `answer`.quiz_id = ?";

    if (isset($_GET['gid'])) {
        $gid = intval($_GET["gid"]);
        $sql .= " AND  `group`.`gid` = ?";
        $result = pdo_query($sql, $qid, $gid);

        $sql = "SELECT name FROM `group` WHERE `gid` = ?";
        $rs = pdo_query($sql, $gid);
        if ($rs) {
            $gname = $rs[0][0];
        }
    } else {
        $result = pdo_query($sql, $qid);
    }

    if (!count($result)) {
        header("Location: quiz_list.php");
        exit(0);
    }

    $all_answers = array();
    $user_info = array();
    foreach ($result as $row) {
        $user_id = $row['user_id'];
        $nick = $row['nick'];
        $total = $row['total'];
        $ans = explode("/", $row['answer']);
        $sc = explode("/", $row['score']);
        $ans = new Answer($ans, $sc, $user_id, $nick, $total);
        $ana->add_answer($ans);
        $all_answers[] = $ans;
        $user_info[$user_id] = array($row["name"], $row["in_date"], $row["gid"]);
    }
} else {
    header("Location: quiz_list.php");
    exit(0);
}
require_once("admin-header.php");
?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $OJ_NAME ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <?php include("../template/css.php"); ?>
    <title><?php echo $OJ_NAME ?></title>
</head>

<body>
    <div class='container'>
        <?php include("../template/nav.php") ?>
        <div class='jumbotron'>
            <div class='row lg-container'>
                <?php require_once("sidebar.php") ?>
                <div class='col-md-9 col-lg-10 p-0'>
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
                                        <?php if (isset($gid)) { ?>
                                            <th>
                                                <?php echo $MSG_GROUP ?>
                                                <a href="quiz_analysis.php?qid=<?php echo $qid ?>"><?php echo $MSG_RESET ?></a>
                                            </th>
                                        <?php  } ?>
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
                                        <?php if (isset($gid)) { ?>
                                            <td><?php echo $gname ?></td>
                                        <?php  } ?>
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
                                        $score_users = $ana->get_score_users($i);
                                        $cnt = 0;
                                        foreach ($ana->get_score_num($i) as $key => $value) {
                                            $user_list = "";
                                            foreach ($score_users[$key] as $sc => $uss) {
                                                $user_list .= $uss[0] . "(" . $uss[1] . ") ";
                                                $cnt++;
                                                if ($cnt == 4) {
                                                    $cnt = 0;
                                                    $user_list .= "<br>";
                                                }
                                            }
                                            array_push($options_ana, array('value' => $value, 'name' => $key, "tooltip" => $user_list));
                                        }
                                        ?>
                                        <script>
                                            score_options[<?php echo $i ?>] = {
                                                tooltip: {
                                                    trigger: 'item',
                                                    enterable: true,
                                                    triggerOn: 'click',
                                                    transitionDuration: 0,
                                                    extraCssText: 'overflow-y:auto;max-height:120px;'
                                                },
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
                                                            formatter: '{b}({c})',
                                                            show: true,
                                                            fontSize: '40',
                                                            fontWeight: 'bold'
                                                        }
                                                    },
                                                    labelLine: {
                                                        show: false
                                                    },
                                                    data: JSON.parse('<?php echo json_encode($options_ana) ?>'),
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
                                        $choice_users = $ana->get_choice_users($i);
                                        $options_ana = array();
                                        foreach ($ana->get_choice_num($i) as $key => $value) {
                                            $user_list = "";
                                            $cnt = 0;
                                            foreach ($choice_users[$key] as $sc => $uss) {
                                                $user_list .= $uss[0] . "(" . $uss[1] . ") ";
                                                $cnt++;
                                                if ($cnt == 4) {
                                                    $cnt = 0;
                                                    $user_list .= "<br>";
                                                }
                                            }
                                            array_push($options_ana, array('value' => $value, 'name' => $key . "(" . $value . ")", 'tooltip' => $user_list));
                                        }
                                        ?>
                                        <script>
                                            choice_options[<?php echo $i ?>] = {
                                                tooltip: {
                                                    trigger: 'item',
                                                    enterable: true,
                                                    triggerOn: 'click',
                                                    transitionDuration: 0,
                                                    extraCssText: 'overflow-y:auto;max-height:120px;'
                                                },
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
                                                    data: JSON.parse('<?php echo json_encode($options_ana) ?>'),
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
                                            <table class="table table-condensed" id="userlist">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $MSG_USER_ID ?></th>
                                                        <th><?php echo $MSG_NICK ?></th>
                                                        <th><?php echo $MSG_SCORE ?></th>
                                                        <th><?php echo $MSG_GROUP ?></th>
                                                        <th><?php echo $MSG_SUBMIT_TIME ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($all_answers as $submit) {
                                                        $rate = 1 - $submit->total / $score_total;
                                                        $aa = 0xff - $rate * 64;
                                                        $aa = dechex($aa);
                                                        $bg_color = "ff$aa$aa";

                                                        $user = $submit->user;
                                                        $cgid = $user_info[$user][2];
                                                        echo "<tr style='background: #$bg_color'>";
                                                        echo "<td><a href='../userinfo.php?user=$user'>" . $user . "</a></td>";
                                                        echo "<td>" . $submit->nick . "</td>";
                                                        echo "<td><a href='../quiz.php?qid=$qid&user_id=$user' target='view_window'>" .
                                                            $submit->total . "</a></td>";
                                                        echo "<td><a href='quiz_analysis.php?qid=$qid&gid=$cgid'>" . $user_info[$user][0] . "</a></td>";
                                                        echo "<td>" . $user_info[$user][1] . "</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <?php if ($private) {
                                                $sql = "SELECT * FROM `privilege_group` 
                                                    JOIN `users` ON `privilege_group`.`gid` = `users`.`gid`
                                                    JOIN `group` ON `group`.`gid` = `users`.`gid`
                                                    WHERE `privilege_group`.`rightstr` = ? AND `users`.`user_id` NOT IN 
                                                    (SELECT `user_id` FROM `answer` WHERE `quiz_id`= ?)";

                                                if (isset($gid)) {
                                                    $sql .= " AND `group`.`gid` = ?";
                                                    $result = pdo_query($sql, "q$qid", $qid, $gid);
                                                } else {
                                                    $result = pdo_query($sql, "q$qid", $qid);
                                                }
                                            ?>
                                                <table class="table table-condensed" id="userlist">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo $MSG_USER_ID ?></th>
                                                            <th><?php echo $MSG_NICK ?></th>
                                                            <th><?php echo $MSG_GROUP ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($result as $submit) {
                                                            echo "<tr>";
                                                            echo "<td>" . $submit["user_id"] . "</td>";
                                                            echo "<td>" . $submit["nick"] . "</td>";
                                                            echo "<td>" . $submit["name"] . "</td>";
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
                    <br>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("../template/js.php"); ?>
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
    <script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/jquery.tablesorter.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#userlist").tablesorter();
        });
    </script>
</body>

</html>