<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title>
    <?php echo $OJ_NAME ?>
  </title>

  <?php include("template/$OJ_TEMPLATE/css.php"); ?>

</head>

<body>
  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <div>
          <h3><?php echo $MSG_QUIZ_ID ?> : <?php echo $view_qid ?> - <?php echo $view_title ?></h3>
          <p>
            <?php echo $view_description ?>
          </p>
          <br />
          <?php echo $MSG_SERVER_TIME ?> : <span id=nowdate> <?php echo date("Y-m-d H:i:s") ?></span>
          <br />

          <?php if ($now > $end_time) {
            echo "<span class=text-muted>$MSG_Ended</span>";
          } else if ($now < $start_time) {
            echo "<span class=text-success>$MSG_Start&nbsp;</span>";
            echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
          } else {
            echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
            echo "<span class=text-danger>$MSG_LeftTime</span>" . " " . formatTimeLength($end_time - $now);
          }
          ?>

          <br /><br />

          <?php echo $MSG_CONTEST_STATUS ?> :

          <?php
          if ($now > $end_time)
            echo "<span class=text-muted>" . $MSG_End . "</span>";
          else if ($now < $start_time)
            echo "<span class=text-success>" . $MSG_Start . "</span>";
          else
            echo "<span class=text-danger>" . $MSG_Running . "</span>";
          ?>
          &nbsp;&nbsp;

          <?php echo $MSG_CONTEST_OPEN ?> :

          <?php if ($view_private == '0')
            echo "<span class=text-primary>" . $MSG_Public . "</span>";
          else
            echo "<span class=text-danger>" . $MSG_Private . "</span>";
          ?>

          <br />

          <?php echo $MSG_START_TIME ?> : <?php echo $view_start_time ?>
          <br />
          <?php echo $MSG_END_TIME ?> : <?php echo $view_end_time ?>
          <br /><br />
          <?php if (!$answered) { ?>
            <a class="btn btn-sm btn-primary" href="quiz_submitpage.php?qid=<?php echo $view_qid ?>"><?php echo $MSG_QUIZ_ANS; ?></a>
          <?php } else { ?>
            <div id="statistic" class="table-responsive main-container">
              <table class="table">
                <thead>
                  <tr>
                    <th><?php echo $MSG_USER_ID ?></th>
                    <th><?php echo $MSG_NICK ?></th>
                    <th><?php echo $MSG_SUBMIT_TIME ?></th>
                    <th><?php echo $MSG_SCORE_SUM ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo $answer["user_id"] ?></td>
                    <td><?php echo $answer["nick"] ?></td>
                    <td><?php echo $answer["in_date"] ?></td>
                    <td><?php echo $answer["total"] ?></td>
                  </tr>
                </tbody>
              </table>
              <table class="table">
                <thead>
                  <tr>
                    <th>
                      <?php echo $MSG_QUIZ_PROBLEM; ?>
                    </th>
                    <th>
                      <?php echo $MSG_Description; ?>
                    </th>
                    <th>
                      <?php echo $MSG_TYPE; ?>
                    </th>
                    <th>
                      <?php echo $MSG_YOUR_ANSWER; ?>
                    </th>
                    <th>
                      <?php echo $MSG_CORRECT_ANSWER; ?>
                    </th>
                    <th>
                      <?php echo $MSG_SCORE; ?>
                    </th>
                    <th>
                      <?php echo $MSG_QUIZ_SCORE; ?>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $type = explode("/", $quiz['type']);
                  $my_score = explode("/", $answer['score']);
                  $score = explode("/", $quiz['score']);
                  $my_answer = explode("/", $answer['answer']);
                  $correct = explode("/", $quiz['correct_answer']);
                  $view_description = explode("<sep />", $quiz['question']);
                  $sum = array_sum(array_map('intval', $score));
                  for ($i = 0; $i < count($type); $i++) {
                    echo "<tr>";
                    echo "<td>" . ($i + 1) . "</td>";
                    echo "<td style='width:36%'>" . $view_description[$i] . "</td>";
                    echo "<td>" . $MSG_QUIZ_TYPE[intval($type[$i])] . "</td>";
                    echo "<td>" . $my_answer[$i] . "</td>";
                    echo "<td>" . $correct[$i] . "</td>";
                    echo "<td>" . $score[$i] . "</td>";
                    echo "<td>" . $my_score[$i] . "</td>";
                    echo "</tr>";
                  }
                  ?>
                  <tr>
                    <th><?php echo $MSG_SCORE_SUM ?></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $answer['total'] ?></td>
                    <td><?php echo $answer['total'] ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          <?php } ?>
          <br /><br />
      </center>
    </div>
  </div>

  <?php include("template/$OJ_TEMPLATE/js.php"); ?>
  <script>
    var diff = new Date("<?php echo date("Y/m/d H:i:s") ?>").getTime() - new Date().getTime();
    clock(diff);
  </script>

</body>

</html>