<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

  <style>
    #source {
      width: 80%;
      height: 600px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <form id="frmSolution" action="quiz_submit.php" method="post" onsubmit='return check_quiz()'>
          <br>
          <?php echo $MSG_QUIZ_ID . " : " ?> <span class="blue"><?php echo $id ?></span>
          <br>
          <input id=quiz_id type='hidden' value='<?php echo $id ?>' name="qid">
          <br>
          <div id="answer">
            <?php
            $tinymce = 0;
            for ($i = 1; $i <= count($question); $i++) {
              $detail = $question[$i - 1];
              $t = $type[$i - 1];
              $n_score = $score[$i - 1];
              echo "<div class='panel panel-default news' style='margin:0 auto;text-align:left;'>";
              echo "<div class='panel-heading'><big>$MSG_QUIZ_PROBLEM $i $MSG_SCORE : $n_score</big></div>";
              echo "<div class='panel-body'>";
              echo $detail;
              switch (intval($t)) {
                case 0:
                  echo "<div id='t0'>";
                  foreach (str_split("ABCD") as $op) {
                    echo "<div class='col-sm-3'>";
                    echo "<div class='form-group'>";
                    echo "<br>";
                    echo "<label class='form-control' for='q$op$i'><input class='option$i' type='radio' name='q$i' id='q$op$i' value='$op'>&nbsp;&nbsp;$op</label>";
                    echo "</div>";
                    echo "</div>";
                  }
                  echo "</div>";
                  break;
                case 1:
                  echo "<div id='t1'>";
                  foreach (str_split("ABCD") as $op) {
                    echo "<div class='col-sm-3'>";
                    echo "<div class='form-group'>";
                    echo "<br>";
                    echo "<label class='form-control' for='q$op$i'><input class='option$i' type='checkbox' name='q$i" . "[]" . "' id='q$op$i' value='$op'>&nbsp;&nbsp;$op</label>";
                    echo "</div>";
                    echo "</div>";
                  }
                  echo "</div>";
                  break;
                case 2:
                  echo "<div class='form-group' id='t2'>";
                  echo "<input class='form-control' name='q$i' id='q$i'></input>";
                  echo "</div>";
                  break;
                case 3:
                  echo "<div class='form-group' id='t3'>";
                  echo "<textarea name='q$i' id='tinymce$tinymce'></textarea>";
                  echo "</div>";
                  $tinymce++;
                  break;
              }
              echo "</div>";
              echo "</div>";
              echo "<br>";
            } ?>
          </div>
          <br>
          <div>
            <button type="submit" class="btn btn-default"><?php echo $MSG_SUBMIT; ?></button>
          </div>
          <br>
        </form>
        <br>
      </center>
    </div>
  </div>
  <?php
  include("template/js.php");

  $student_mode = true;
  include("tinymce/tinymce.php");
  ?>

  <script>
    function check_quiz() {
      var flag = true;
      var el = [];
      $("div#t0").each(function(index, elem) {
        if ($(elem).find("input:checked").length == 0) {
          flag = false;
          el.push(elem)
        }
      });
      $("div#t1").each(function(index, elem) {
        if ($(elem).find("input:checked").length == 0) {
          flag = false;
          el.push(elem)
        }
      });
      $("div#t2").each(function(index, elem) {
        if ($(elem).find("textarea").val() == "") {
          flag = false;
          el.push(elem)
        }
      });
      if (flag) {
        swal({
          title: "确认提交",
          text: "确认提交?",
          buttons: [true, true]
        }).then(function(value) {
          if (value) {
            $("form#frmSolution").attr("onsubmit", "").submit()
          }
        })
      } else {
        $('html,body').animate({
          scrollTop: $(el[0]).offset().top
        }, 500);
        swal({
          text: "<?php echo $MSG_PLEASE_ANSWER_ALL_QUESTIONS; ?>",
          timer: 1000
        });
      }
      return false;
    }
    $("input[class^=option]").change(function() {
      $("input." + $(this).attr("class")).each(function(index, elem) {
        if ($(elem).prop("checked")) {
          $(elem).parent().css("background", "#BDFCC9")
        } else {
          $(elem).parent().css("background", "")
        }
      })
    })
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