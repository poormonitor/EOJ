<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo $view_description ?>">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title>
    <?php echo $OJ_NAME ?>
  </title>

  <?php include("template/css.php"); ?>

</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <div>
          <h3><?php echo $MSG_QUIZ_ID ?> : <?php echo $view_qid ?> - <?php echo $view_title ?></h3>
          <p>
            <?php echo $view_description ?>
          </p>
          <br>
          <?php echo $MSG_SERVER_TIME ?> : <span id=nowdate> <?php echo date("Y-m-d H:i:s") ?></span>
          <br>

          <?php if ($now > $end_time) {
            echo "<span class=text-muted>$MSG_Ended</span>";
          } else if ($now < $start_time) {
            echo "<span class=text-success>$MSG_Start&nbsp;</span>";
            echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
          } else {
            echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
            echo "<span class='text-danger'>$MSG_LeftTime</span> <span class='time-left'>" . formatTimeLength($end_time - $now) . "</span>";
          }
          ?>

          <br><br>

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

          <br>

          <?php echo $MSG_START_TIME ?> : <?php echo $view_start_time ?>
          <br>
          <?php echo $MSG_END_TIME ?> : <?php echo $view_end_time ?>
          <br><br>

          <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
            <div class="btn-group">
              <a href="admin/quiz_analysis.php?qid=<?php echo $view_qid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_ANALYSIS ?></a>
              <a target="_blank" href="admin/quiz_judge.php?qid=<?php echo $view_qid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_QUIZ_JUDGE ?></a>
              <a target="_blank" href="admin/quiz_edit.php?qid=<?php echo $view_qid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_EDIT ?></a>
            </div>
          <?php } ?>

          <?php if (!$answered) { ?>
            <a class="btn btn-sm btn-info" href="quiz_submitpage.php?qid=<?php echo $view_qid ?>"><?php echo $MSG_QUIZ_ANS; ?></a>
          <?php } else { ?>
            <div id="statistic" class="main-container">
              <div class='table-responsive'>
                <table class="table">
                  <thead>
                    <tr>
                      <th><?php echo $MSG_USER_ID ?></th>
                      <th><?php echo $MSG_NICK ?></th>
                      <th><?php echo $MSG_SUBMIT_TIME ?></th>
                      <th><?php echo $MSG_SCORE_SUM ?></th>
                      <th><?php echo $MSG_QUIZ_SCORE ?></th>
                      <th><?php echo $MSG_IS_JUDGED ?></th>
                      <th><?php echo $MSG_QUIZ_JUDGE_TIME ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?php echo $answer["user_id"] ?></td>
                      <td><?php echo $answer["nick"] ?></td>
                      <td><?php echo $answer["in_date"] ?></td>
                      <td><?php echo $quiz_total ?></td>
                      <td><?php echo $answer["total"] ?></td>
                      <td><?php echo $answer["judged"] ? $MSG_TRUE_FALSE[true] : $MSG_TRUE_FALSE[false] ?></td>
                      <td><?php echo $answer["judgetime"] == NULL ? "-" : $answer["judgetime"] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class='table-responsive'>
                <?php
                $type = explode("/", $quiz['type']);
                $my_score = explode("/", $answer['score']);
                $score = explode("/", $quiz['score']);
                $my_answer = explode("/", $answer['answer']);
                $correct = explode("/", $quiz['correct_answer']);
                $view_description = explode("<sep />", $quiz['question']);
                $sum = array_sum(array_map('intval', $score));
                $blank = isAllEmpty($view_description);
                ?>
                <table class="table">
                  <thead class="keep-all">
                    <tr>
                      <th><?php echo $MSG_QUIZ_PROBLEM; ?></th>
                      <?php if (!$blank) { ?>
                        <th><?php echo $MSG_Description; ?></th>
                      <?php } ?>
                      <th><?php echo $MSG_TYPE; ?></th>
                      <th><?php echo $MSG_YOUR_ANSWER; ?></th>
                      <th><?php echo $MSG_CORRECT_ANSWER; ?></th>
                      <th><?php echo $MSG_SCORE; ?></th>
                      <th><?php echo $MSG_QUIZ_SCORE; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    for ($i = 0; $i < count($type); $i++) {
                      if ($score[$i] != $my_score[$i])
                        echo "<tr class='bg-red'>";
                      else
                        echo "<tr>";
                      echo "<td>" . ($i + 1) . "</td>";
                      if (!$blank)
                        echo "<td style='width:36%'>" . $view_description[$i] . "</td>";
                      echo "<td>" . $MSG_QUIZ_TYPE[intval($type[$i])] . "</td>";
                      if (intval($type[$i]) == 3)
                        echo "<td class='ans'>" . str_replace("\\", "/", $my_answer[$i]) . "</td>";
                      else
                        echo "<td>" . $my_answer[$i] . "</td>";
                      echo "<td>" . $correct[$i] . "</td>";
                      echo "<td>" . $score[$i] . "</td>";
                      if ($answer['judged'] || $type[$i] != '3') {
                        echo "<td>" . $my_score[$i] . "</td>";
                      } else {
                        echo "<td>" . $MSG_NOT_JUDGED . "</td>";
                      }
                      echo "</tr>";
                    }
                    ?>
                    <tr>
                      <th><?php echo $MSG_SCORE_SUM ?></th>
                      <?php if (!$blank) { ?>
                        <td></td>
                      <?php } ?>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><?php echo $quiz_total ?></td>
                      <td><?php echo $answer['total'] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          <?php } ?>
          <br><br>
      </center>
    </div>
  </div>

  <?php include("template/js.php"); ?>
  <script>
    var diff = new Number("<?php echo round(microtime(true) * 1000) ?>") - new Date().getTime();
    setTimeout("clock()", diff % 1000 ? diff > 0 : 1000 + diff % 1000);
  </script>
  <script src="<?php echo $OJ_CDN_URL . "include/" ?>simpleLightbox.min.js"></script>
  <script>
    $(".content").find("img").each(function(index, elem) {
      var atag = $("<a class='image'></a>")
      atag.attr("href", $(elem).attr("src") + "&large=true")
      $(elem).wrap(atag)
    })
    $(".image").simpleLightbox();
  </script>
</body>

</html>