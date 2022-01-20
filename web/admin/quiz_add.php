<!DOCTYPE html>
<?php
header("Cache-control:private");
?>
<html>

<head>
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-cache">
  <meta http-equiv="Content-Language" content="zh-cn">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Quiz Add</title>
</head>
<hr>
<?php
require_once("../include/db_info.inc.php");
require_once("../lang/$OJ_LANG.php");
require_once("../include/const.inc.php");
require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
echo "<center><h3>" . $MSG_QUIZ . "-" . $MSG_ADD . "</h3></center>";

?>

<body leftmargin="30">
  <style>
    input[type=date],
    input[type=time],
    input[type=datetime-local],
    input[type=month] {
      line-height: normal;
    }
  </style>
  <?php
  $description = "";
  if (isset($_POST['startdate'])) {
    require_once("../include/check_post_key.php");

    $starttime = $_POST['startdate'] . " " . intval($_POST['shour']) . ":" . intval($_POST['sminute']) . ":00";
    $endtime = $_POST['enddate'] . " " . intval($_POST['ehour']) . ":" . intval($_POST['eminute']) . ":00";
    //echo $starttime;
    //echo $endtime;

    $title = $_POST['title'];
    $private = $_POST['private'];

    $description = $_POST['description'];

    $title = stripslashes($title);
    $private = stripslashes($private);
    $description = stripslashes($description);

    $sql = "INSERT INTO `quiz`(`title`,`start_time`,`end_time`,`private`,`description`,`user_id`)
          VALUES(?,?,?,?,?,?)";

    $description = str_replace("<p>", "", $description);
    $description = str_replace("</p>", "<br />", $description);
    $description = str_replace(",", "&#44; ", $description);
    $user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
    $qid = pdo_query($sql, $title, $starttime, $endtime, $private, $description, $user_id);
    echo "Add Quiz " . $qid;

    $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
    pdo_query($sql, "q$qid");

    $sql = "INSERT INTO `privilege` (`user_id`,`rightstr`) VALUES(?,?)";
    pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], "mq$qid");

    $_SESSION[$OJ_NAME . '_' . "mq$qid"] = true;

    $pieces = array();
    $glist = ($_POST["gid"]);
    if (isset($_POST["gid"]) and $_POST["gid"] != '') {
      foreach ($glist as $i) {
        $sql = "SELECT `user_id` FROM `users` WHERE `gid`=?;";
        $result = pdo_query($sql, trim($i));
        foreach ($result as $p) {
          array_push($pieces, $p["user_id"]);
        }
      }
    }

    if (count($pieces) > 0 && strlen($pieces[0]) > 0) {
      $sql_1 = "INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES (?,?)";
      for ($i = 0; $i < count($pieces); $i++) {
        pdo_query($sql_1, trim($pieces[$i]), "q$qid");
      }
    }

    echo "<script>window.location.href=\"quiz_list.php\";</script>";
  } else {
    if (isset($_GET['qid'])) {
      $qid = intval($_GET['qid']);
      $sql = "SELECT * FROM contest WHERE `contest_id`=?";
      $result = pdo_query($sql, $qid);
      $row = $result[0];
      $title = $row['title'] . "-Copy";

      $private = $row['private'];
      $description = $row['description'];

      $ulist = "";
      $sql = "SELECT `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
      $result = pdo_query($sql, "q$qid");

      foreach ($result as $row) {
        if ($ulist) $ulist .= "\n";
        $ulist .= $row[0];
      }
    }

  ?>

    <div class="container">
      <form method=POST>
        <p align=left>
          <?php echo "<h3>" . $MSG_QUIZ . "-" . $MSG_TITLE . "</h3>" ?>
          <input class="input form-control" style="width:100%;" type=text name=title value="<?php echo isset($title) ? $title : "" ?>"><br /><br />
        </p>
        <div style="margin-bottom: 5px;">
          <?php echo $MSG_QUIZ . $MSG_Start ?>:
          <input class=input-large type=date name='startdate' value='<?php echo date('Y') . '-' . date('m') . '-' . date('d') ?>'>
          Hour: <input class=input-mini type=text name=shour size=2 value=<?php echo date('H') ?>>&nbsp;
          Minute: <input class=input-mini type=text name=sminute value=00 size=2>
        </div>
        <div>
          <?php echo $MSG_QUIZ . $MSG_End ?>:
          <input class=input-large type=date name='enddate' value='<?php echo date('Y') . '-' . date('m') . '-' . date('d') ?>'>
          Hour: <input class=input-mini type=text name=ehour size=2 value=<?php echo (date('H') + 4) % 24 ?>>&nbsp;
          Minute: <input class=input-mini type=text name=eminute value=00 size=2>
        </div>
        <br />
        <p align=left>
          <?php echo "<h4>" . $MSG_QUIZ . "-" . $MSG_Description . "</h4>" ?>
          <textarea id="tinymce0" rows=13 name=description cols=80><?php echo isset($description) ? $description : "" ?></textarea>
        </p>
        <br />
        <table width="100%">
          <tr>
            <td height="*" style="padding:20px;">
              <p align=left>
                <?php echo $MSG_QUIZ . "-" . $MSG_GROUP ?>
                <br />
                <select name="gid[]" class="selectpicker show-menu-arrow form-control" size='8' multiple>
                  <?php
                  require_once("../include/my_func.inc.php");
                  $sql_all = "SELECT * FROM `group`;";
                  $result = pdo_query($sql_all);
                  $all_group = $result;
                  foreach ($all_group as $i) {
                    $show_id = $i["gid"];
                    $show_name = $i["name"];
                    echo "<option value=$show_id>$show_name</option>";
                  }
                  ?>
                </select>&nbsp;&nbsp;
              </p>
            </td>
          </tr>
        </table>

        <div align=center>
          <?php require_once("../include/set_post_key.php"); ?>
          <input type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
        </div>
      </form>
    </div>

  <?php }
  require_once("../oj-footer.php");
  require_once('../tinymce/tinymce.php'); ?>
</body>

</html>