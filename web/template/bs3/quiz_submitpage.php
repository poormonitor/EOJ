<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/$OJ_TEMPLATE/css.php"); ?>

  <style>
    #source {
      width: 80%;
      height: 600px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <form id="frmSolution" action="quiz_submit.php" method="post" onsubmit='return check_quiz()'>
          <br />
          <?php echo $MSG_QUIZ_ID . " : " ?> <span class="blue"><?php echo $id ?></span>
          <br />
          <input id=quiz_id type='hidden' value='<?php echo $id ?>' name="qid">
          <br />
          <div id="answer">
            <?php
            for ($i = 1; $i <= count($question); $i++) {
              $detail = $question[$i - 1];
              $t = $type[$i - 1];
              $n_score = $score[$i - 1];
              echo "<div class='panel panel-default news' style='margin:0 auto;text-align:left;'>";
              echo "<div class='panel-heading'><big>$MSG_QUIZ_PROBLEM $i $MSG_SCORE : $n_score</big></div>";
              echo "<div class='panel-body'>";
              echo $detail;
              echo "<br />";
              switch (intval($t)) {
                case 0:
                  echo "<div id='0'>";
                  foreach (str_split("ABCD") as $op) {
                    echo "<div class='col-sm-3'>";
                    echo "<div class='form-group'>";
                    echo "<br />";
                    echo "<label class='form-control' for='q$op$i'><input type='radio' name='q$i' id='q$op$i' value='$op'>&nbsp;&nbsp;$op</label>";
                    echo "</div>";
                    echo "</div>";
                  }
                  echo "</div>";
                  break;
                case 1:
                  echo "<div id='1'>";
                  foreach (str_split("ABCD") as $op) {
                    echo "<div class='col-sm-3'>";
                    echo "<div class='form-group'>";
                    echo "<br />";
                    echo "<label class='form-control' for='q$op$i'><input type='checkbox' name='q$i"."[]"."' id='q$op$i' value='$op'>&nbsp;&nbsp;$op</label>";
                    echo "</div>";
                    echo "</div>";
                  }
                  echo "</div>";
                  break;
                case 2:
                case 3:
                  echo "<div class='form-group' id='2'>";
                  echo "<textarea class='form-control' name='q$i' id='q$i' rows='5'></textarea>";
                  echo "</div>";
                  break;
              }
              echo "</div>";
              echo "<br />";
            } ?>
          </div>
          <br />
          <button type="submit" class="btn btn-default"><?php echo $MSG_SUBMIT; ?></button>
          <br />
        </form>
        <br />
      </center>
    </div>
  </div>
  <?php include("template/$OJ_TEMPLATE/js.php"); ?>

  <script>
    function check_quiz() {
      var flag = true;
      $("div#0").each(function(index, elem) {
        if ($(elem).find("input:checked").length == 0) {
          flag = false;
        }
      });
      $("div#1").each(function(index, elem) {
        if ($(elem).find("input:checked").length == 0) {
          flag = false;
        }
      });
      $("div#2").each(function(index, elem) {
        if ($(elem).find("textarea").val() == "") {
          flag = false;
        }
      });
      if (flag) {
        return true;
      } else {
        swal({
          text: "<?php echo $MSG_PLEASE_ANSWER_ALL_QUESTIONS; ?>",
          timer: 1000
        });
        return false;
      }
    }
  </script>
  <?php if ($OJ_VCODE) { ?>
    <script>
      $(document).ready(function() {
        $("#vcode").attr("src", "vcode.php?small=true&" + Math.random());
      })
    </script>
  <?php } ?>
</body>

</html>