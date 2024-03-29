<?php
require_once("../include/db_info.inc.php");
require_once("../lang/$OJ_LANG.php");
require_once("../include/my_func.inc.php");
require_once("../include/const.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

$description = "";
if (isset($_POST['qid'])) {
  require_once("../include/check_post_key.php");
  $qid = intval($_POST['qid']);

  $start_time = $_POST['startdate'] . " " . getTimeVal($_POST['shour']) . ":" . getTimeVal($_POST['sminute']) . ":00";
  $end_time = $_POST['enddate'] . " " . getTimeVal($_POST['ehour']) . ":" . getTimeVal($_POST['eminute']) . ":00";

  //echo $start_time;
  //echo $end_time;

  $title = $_POST['title'];
  $private = intval($_POST['private']);

  $questions = array();
  for ($i = 1; isset($_POST['qc' . $i]); $i++) {
    array_push($questions, $_POST['qc' . $i]);
  }
  $question = join("<sep />", $questions);

  $types = array();
  for ($i = 0; isset($_POST['qt' . $i]); $i++) {
    array_push($types, $_POST['qt' . $i]);
  }
  $type = join("/", $types);

  $scores = array();
  for ($i = 0; isset($_POST['qs' . $i]); $i++) {
    array_push($scores, $_POST['qs' . $i]);
  }
  $score = join("/", $scores);

  $correct_answers = array();
  for ($i = 0; isset($_POST['qca' . $i]); $i++) {
    array_push($correct_answers, $_POST['qca' . $i]);
  }
  $correct_answer = join("/", $correct_answers);

  $description = $_POST['description'];

  $title = stripslashes($title);
  $description = stripslashes($description);
  $description = str_replace("<p>", "", $description);
  $description = str_replace("</p>", "<br>", $description);
  $description = str_replace(",", "&#44; ", $description);

  # compare question details and add the number to the diff
  $sql = "SELECT * FROM `quiz` WHERE `quiz_id`=?";
  $result = pdo_query($sql, $qid);
  $row = $result[0];

  $diff = array();

  $compare = array("title", "start_time", "end_time", "private", "description");
  foreach ($compare as $i) {
    if (strval($row[$i]) != strval($$i)) {
      array_push($diff, $i);
    }
  }

  $compare = array("question", "type", "score", "correct_answer");

  $old_question = explode("<sep />", $row['question']);
  $old_type = explode("/", $row['type']);
  $old_score = explode("/", $row['score']);
  $old_correct_answer = explode("/", $row['correct_answer']);

  for ($i = 0; $i < count($questions); $i++) {
    $diff_now = array();
    foreach ($compare as $j) {
      if (strval(${$j . "s"}[$i]) != strval(${"old_" . $j}[$i])) {
        array_push($diff_now, substr($j, 0, 1));
      }
    }
    if (count($diff_now)) {
      array_push($diff, $i + 1 . join("", $diff_now));
    }
  }

  $diffs = join(", ", $diff);

  # get orinal gourp privilege
  $sql = "SELECT `gid` FROM `privilege_group` WHERE `rightstr` = ? order BY `gid`";
  $result = pdo_query($sql, "q$qid");
  $old_glist = array();
  foreach ($result as $row) {
    array_push($old_glist, $row['gid']);
  }

  $sql = "UPDATE `quiz` SET `title`=?,`start_time`=?,`end_time`=?,`private`=?,`description`=?,`user_id`=?,`question`=?,`type`=?,`score`=?,`correct_answer`=? WHERE `quiz_id`=?";

  $user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
  pdo_query($sql, $title, $start_time, $end_time, $private, $description, $user_id, $question, $type, $score, $correct_answer, $qid);

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql, "mq$qid");

  $sql = "DELETE FROM `privilege_group` WHERE `rightstr`=?";
  pdo_query($sql, "q$qid");

  $sql = "INSERT INTO `privilege` (`user_id`,`rightstr`) VALUES(?,?)";
  pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], "mq$qid");

  $_SESSION[$OJ_NAME . '_' . "mq$qid"] = true;

  $glist = $_POST["gid"];
  if ($glist) {
    foreach ($glist as $i) {
      $sql = "INSERT INTO `privilege_group`(`gid`,`rightstr`) VALUES (?,?)";
      $result = pdo_query($sql, trim($i), "q$qid");
    }
  }

  $ip = getRealIP();
  $sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
  pdo_query($sql, "q$qid", $_SESSION[$OJ_NAME . '_' . 'user_id'], "edit $diffs", $ip);

  header("Location: quiz_list.php");
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
  $question = explode("<sep />", $row['question']);
  $answer = explode("/", $row['correct_answer']);
  $score = explode("/", $row['score']);
  $num = count($type);
  $start_time = $row['start_time'];
  $end_time = $row['end_time'];
  $blank = max(array_map('strlen', $question)) ? false : true;

  $ulist = "";
  $sql = "SELECT `gid` FROM `privilege_group` WHERE `rightstr` = ? order BY `gid`";
  $result = pdo_query($sql, "q$qid");
  $glist = array();
  foreach ($result as $row) {
    array_push($glist, $row['gid']);
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
          <?php echo "<center><h3>" . $MSG_QUIZ . "-" . $MSG_EDIT . "</h3></center>"; ?>

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
                <?php echo "<h3>" . $MSG_QUIZ . "-" . $MSG_TITLE . "</h3>" ?>
                <input class='form-control' style="width:100%;" type=text name=title value="<?php echo isset($title) ? $title : "" ?>"><br><br>
              <div style="margin-bottom: 10px;" class='form-inline'>
                <?php echo $MSG_QUIZ . $MSG_Start ?>:
                <input class='form-control' type=date name='startdate' value='<?php echo substr($start_time, 0, 10) ?>' size=4>
                Hour: <input class='form-control' type=text name=shour size=2 value='<?php echo substr($start_time, 11, 2) ?>'>&nbsp;
                Minute: <input class='form-control' type=text name=sminute value='<?php echo substr($start_time, 14, 2) ?>' size=2>
              </div>
              <div style="margin-bottom: 10px;" class='form-inline'>
                <?php echo $MSG_QUIZ . $MSG_End ?>:
                <input class='form-control' type=date name='enddate' value='<?php echo substr($end_time, 0, 10) ?>' size=4>
                Hour: <input class='form-control' type=text name=ehour size=2 value='<?php echo substr($end_time, 11, 2) ?>'>&nbsp;
                Minute: <input class='form-control' type=text name=eminute value='<?php echo substr($end_time, 14, 2) ?>' size=2>
              </div>
              <br>
              <p align=left>
                <?php echo "<h4>" . $MSG_QUIZ . "-" . $MSG_Description . "</h4>" ?>
                <textarea id="tinymce0" class='form-control' rows=13 name=description cols=80><?php echo isset($description) ? $description : "" ?></textarea>
              </p>
              <br>
              <p>
                <?php
                for ($i = 0; $i < $num; $i++) {
                  $pid = $i + 1;
                  echo "<h4>" . $MSG_QUIZ . "-" . $MSG_QUIZ_PROBLEM . " " . $pid . "</h4>";
                  if (!$blank) {
                    echo "<textarea id='tinymce$pid' class='form-control' rows=13 name='qc$pid' cols=80>";
                    if (isset($question)) {
                      echo $question[$i];
                    }
                    echo "</textarea><br>";
                  } else {
                    echo "<textarea style='display:none;' name='qc$pid'></textarea>";
                  }
                  echo "<div class='form-inline'>";
                  for ($t = 0; $t <= 3; $t++) {
                    if (isset($type) && $type[$i] == $t || !isset($type) && $t == 0) {
                      $checked = "checked";
                    } else {
                      $checked = "";
                    }
                    echo "<input type=radio name='qt$i' value='$t' $checked>&nbsp;&nbsp;<label> " . $MSG_QUIZ_TYPE[$t] . "</label>&nbsp;&nbsp;";
                  }
                  echo "<br><br>";
                  $c_score = isset($score) ? $score[$i] : 2;
                  $qa = isset($answer) ? $answer[$i] : "";
                  echo "<label>" . $MSG_SCORE . "</label>&nbsp;&nbsp;";
                  echo "<input type=text name='qs$i' class='form-control' value='" . $c_score . "'>";
                  echo "&nbsp;&nbsp;<label>" . $MSG_CORRECT_ANSWER . "</label>&nbsp;&nbsp;";
                  echo "<input type=text name='qca$i' class='form-control' value='" . $qa . "'>";
                  echo "</div>";
                }
                ?>
              </p>
              <br>
              <table width="100%">
                <tr>
                  <td height="10px" style="padding:10px;">
                    <div style="margin-bottom: 10px;" class='form-inline'>
                      <?php echo $MSG_QUIZ . "-" . $MSG_Public ?>:
                      <select class='form-control' name='private' style="width:150px;">
                        <option value='0' <?php echo isset($private) && $private == '0' ? 'selected' : '' ?>><?php echo $MSG_Public ?></option>
                        <option value='1' <?php echo isset($private) && $private == '1' ? 'selected' : '' ?>><?php echo $MSG_Private ?></option>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td height="*" style="padding:20px;">
                    <p align=left>
                      <?php echo $MSG_QUIZ . "-" . $MSG_GROUP ?>
                      <br>
                      <select name="gid[]" class="selectpicker show-menu-arrow form-control" size='8' multiple>
                        <?php
                        require_once("../include/my_func.inc.php");
                        $sql_all = "SELECT * FROM `group`;";
                        $result = pdo_query($sql_all);
                        $all_group = $result;
                        foreach ($all_group as $i) {
                          $show_id = $i["gid"];
                          $show_name = $i["name"];
                          if (isset($glist) && in_array($show_id, $glist)) {
                            echo "<option value='$show_id' selected>$show_name</option>";
                          } else {
                            echo "<option value='$show_id'>$show_name</option>";
                          }
                        }
                        ?>
                      </select>&nbsp;&nbsp;
                    </p>
                  </td>
                </tr>
              </table>

              <div align=center>
                <?php require_once("../include/set_post_key.php"); ?>
                <input class='btn btn-default' type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
              </div>
            </form>
          </div>

          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <?php require_once('../tinymce/tinymce.php'); ?>
</body>

</html>