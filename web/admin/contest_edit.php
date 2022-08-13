<?php
require_once("../include/db_info.inc.php");
require_once("../include/const.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
	|| isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])
)) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

if (isset($_POST['startdate'])) {
  require_once("../include/check_post_key.php");

  $starttime = $_POST['startdate'] . " " . intval($_POST['shour']) . ":" . intval($_POST['sminute']) . ":00";
  $endtime = $_POST['enddate'] . " " . intval($_POST['ehour']) . ":" . intval($_POST['eminute']) . ":00";
  //echo $starttime;
  //echo $endtime;

  $title = $_POST['title'];
  $private = $_POST['private'];
  $password = $_POST['password'];
  $description = $_POST['description'];

  $title = stripslashes($title);
  $private = stripslashes($private);
  $password = stripslashes($password);
  $description = stripslashes($description);

  $lang = $_POST['lang'];
  $langmask = 0;
  foreach ($lang as $t) {
    $langmask += 1 << $t;
  }

  $langmask = ((1 << count($language_ext)) - 1) & (~$langmask);
  //echo $langmask; 

  $cid = intval($_POST['cid']);

  if (!(isset($_SESSION[$OJ_NAME . '_' . "m$cid"]) || isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
    header("Location: contest_list.php");
    exit(0);
  };

  $description = str_replace("<p>", "", $description);
  $description = str_replace("</p>", "<br>", $description);
  $description = str_replace(",", "&#44;", $description);


  $sql = "UPDATE `contest` SET `title`=?,`description`=?,`start_time`=?,`end_time`=?,`private`=?,`langmask`=?,`password`=? WHERE `contest_id`=?";
  //echo $sql;
  pdo_query($sql, $title, $description, $starttime, $endtime, $private, $langmask, $password, $cid);

  $sql = "DELETE FROM `contest_problem` WHERE `contest_id`=?";
  pdo_query($sql, $cid);
  $plist = $_POST['problem'];
  $pieces = array();
  foreach ($plist as $i) {
    array_push($pieces, trim($i));
  }

  if (count($pieces) > 0 && strlen($pieces[0]) > 0) {
    $sql_1 = "INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) VALUES (?,?,?)";
    for ($i = 0; $i < count($pieces); $i++) {
      $pid = intval($pieces[$i]);
      pdo_query($sql_1, $cid, $pid, $i);
      $sql = "UPDATE `contest_problem` SET `c_accepted`=(SELECT count(1) FROM `solution` WHERE `problem_id`=? and contest_id=? AND `result`=4) WHERE `problem_id`=? and contest_id=?";
      pdo_query($sql, $pid, $cid, $pid, $cid);
      $sql = "UPDATE `contest_problem` SET `c_submit`=(SELECT count(1) FROM `solution` WHERE `problem_id`=? and contest_id=?) WHERE `problem_id`=? and contest_id=?";
      pdo_query($sql, $pid, $cid, $pid, $cid);
    }

    pdo_query("UPDATE solution set num=-1 where contest_id=?", $cid);

    $plist = "";
    for ($i = 0; $i < count($pieces); $i++) {
      if ($plist) $plist .= ",";
      $plist .= $pieces[$i];
      $sql_2 = "UPDATE solution set num=? where contest_id=? and problem_id=?;";
      pdo_query($sql_2, $i, $cid, $pieces[$i]);
    }

    $sql = "UPDATE `problem` set defunct='N' where `problem_id` in ($plist)";
    pdo_query($sql);
  }

  $sql = "DELETE FROM `privilege_group` WHERE `rightstr`=?";
  pdo_query($sql, "c$cid");
  if (intval($private) == 1) {
    $pieces = array();
    $glist = $_POST["gid"];
    if ($glist) {
      foreach ($glist as $i) {
        $sql = "INSERT INTO `privilege_group`(`gid`,`rightstr`) VALUES (?,?)";
        $result = pdo_query($sql, trim($i), "c$cid");
      }
    }
  }

  header("Location: contest_list.php");
  exit(0);
} else {
  $cid = intval($_GET['cid']);
  $sql = "SELECT * FROM `contest` WHERE `contest_id`=?";
  $result = pdo_query($sql, $cid);

  if (count($result) != 1) {
    echo "No such Contest!";
    exit(0);
  }

  $row = $result[0];
  $starttime = $row['start_time'];
  $endtime = $row['end_time'];
  $private = $row['private'];
  $password = $row['password'];
  $langmask = $row['langmask'];
  $description = $row['description'];
  $title = htmlentities($row['title'], ENT_QUOTES, "UTF-8");

  $plist = "";
  $sql = "SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? ORDER BY `num`";
  $result = pdo_query($sql, $cid);

  foreach ($result as $row) {
    if ($plist) $plist .= ",";
    $plist .= $row[0];
  }

  $sql = "SELECT `gid` FROM `privilege_group` WHERE `rightstr`=? order by `gid`";
  $result = pdo_query($sql, "c$cid");
  $gid_before = array();
  foreach ($result as $row) {
    array_push($gid_before, $row[0]);
  }
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
        <div class='col-md-10 p-0'>
          <center>
            <h3><?php echo $MSG_CONTEST . "-" . $MSG_EDIT ?></h3>
          </center>
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
              <?php require_once("../include/set_post_key.php"); ?>
              <input type=hidden name='cid' value=<?php echo $cid ?>>
              <p align=left>
                <?php echo "<h3>" . $MSG_CONTEST . "-" . $MSG_TITLE . "</h3>" ?>
                <input class='form-control' style="width:100%;" type=text name=title value="<?php echo $title ?>"><br><br>
              </p>
              <div style="margin-bottom: 10px;" class='form-inline'>
                <?php echo $MSG_CONTEST . $MSG_Start ?>:
                <input class='form-control' type=date name='startdate' value='<?php echo substr($starttime, 0, 10) ?>' size=4>
                Hour: <input class='form-control' type=text name=shour size=2 value='<?php echo substr($starttime, 11, 2) ?>'>&nbsp;
                Minute: <input class='form-control' type=text name=sminute value='<?php echo substr($starttime, 14, 2) ?>' size=2>
              </div>
              <div style="margin-bottom: 10px;" class='form-inline'>
                <?php echo $MSG_CONTEST . $MSG_End ?>:
                <input class='form-control' type=date name='enddate' value='<?php echo substr($endtime, 0, 10) ?>' size=4>
                Hour: <input class='form-control' type=text name=ehour size=2 value='<?php echo substr($endtime, 11, 2) ?>'>&nbsp;
                Minute: <input class='form-control' type=text name=eminute value='<?php echo substr($endtime, 14, 2) ?>' size=2>
              </div>
              <br>
              <p align=left>
                <?php echo $MSG_CONTEST . "-" . $MSG_PROBLEM_ID ?><br>
                <?php echo $MSG_PLS_ADD ?><br>
                <select name='problem[]' id='multiple_problem' size="10" class="selectpicker show-menu-arrow form-control" multiple style='margin-top:10px;'>
                  <?php
                  $all = explode(",", $plist);
                  $problems = pdo_query("SELECT problem_id,title from problem;");
                  $problem_array = array();
                  foreach ($problems as $i) {
                    $pid = $i[0];
                    $title = $i[1];
                    if (in_array($pid, $all)) {
                      echo ("<option value='$pid' selected>$pid $title</option>");
                      array_push($problem_array, $pid);
                    } else {
                      echo ("<option value='$pid'>$pid $title</option>");
                    }
                  }

                  ?></select>
                <br>
              </p>
              <p align=left>
                <span>手动输入</span><br>
                <input id='problem' data-role="tagsinput" class='form-control' style='margin-top:10px;' value="<?php echo $plist ?>" />
              </p>
              <br>
              <p align=left>
                <?php echo "<h4>" . $MSG_CONTEST . "-" . $MSG_Description . "</h4>" ?>
                <textarea id="tinymce0" rows=13 name=description cols=80>
                  <?php echo htmlentities($description, ENT_QUOTES, 'UTF-8') ?>
                </textarea>
                <br>
              </p>
              <table width="100%">
                <tr>
                  <td rowspan=2>
                    <p aligh=left>
                      <?php echo $MSG_CONTEST . "-" . $MSG_LANG ?><br>
                      <?php echo $MSG_PLS_ADD ?><br>
                      <select name="lang[]" multiple="multiple" style="height:220px;margin-top:10px;" class='selectpicker show-menu-arrow form-control'>
                        <?php
                        $lang_count = count($language_ext);
                        $lang = (~((int)$langmask)) & ((1 << $lang_count) - 1);

                        if (isset($_COOKIE['lastlang'])) $lastlang = $_COOKIE['lastlang'];
                        else $lastlang = 6;

                        for ($i = 0; $i < $lang_count; $i++) {
                          if ($i == $lastlang) {
                            echo "<option value=$i selected>" . $language_name[$i] . "</option>";
                          } else {
                            echo "<option value=$i>" . $language_name[$i] . "</option>";
                          }
                        }
                        ?>
                      </select>
                    </p>
                  </td>

                  <td height="10px" style="padding:10px;">
                    <div class='form-inline'>
                      <?php echo $MSG_CONTEST . "-" . $MSG_Public ?>:
                      <select class='form-control' name=private style="width:150px;">
                        <option value=0 <?php echo $private == '0' ? 'selected=selected' : '' ?>><?php echo $MSG_Public ?></option>
                        <option value=1 <?php echo $private == '1' ? 'selected=selected' : '' ?>><?php echo $MSG_Private ?></option>
                      </select>
                      <?php echo $MSG_CONTEST . "-" . $MSG_PASSWORD ?>:
                      <input class='form-control' type=text name=password style="width:150px;" value='<?php echo htmlentities($password, ENT_QUOTES, 'utf-8') ?>'>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td height="*" style="padding:20px;">
                    <p align=left>
                      <?php echo $MSG_CONTEST . "-" . $MSG_GROUP ?>
                      <br>
                      <select name="gid[]" class="selectpicker show-menu-arrow form-control" size=8 multiple>
                        <?php
                        $sql_all = "SELECT * FROM `group`;";
                        $all_group = pdo_query($sql_all);
                        foreach ($all_group as $i) {
                          $show_id = $i["gid"];
                          $show_name = $i["name"];
                          if (in_array($show_id, $gid_before)) {
                            echo "<option value=$show_id selected>$show_name</option>";
                          } else {
                            echo "<option value=$show_id>$show_name</option>";
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
                <input class='btn btn-default' type=reset value=Reset name=reset>
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
  <script src='<?php echo $OJ_CDN_URL ?>include/bootstrap-tagsinput.min.js'></script>
  <script>
    function selectToTag() {
      var info = $('#multiple_problem').val();
      var ti = $("#problem")
      ti.tagsinput("removeAll");
      info.forEach(element => {
        ti.tagsinput("add", element)
      });
      ti.tagsinput('refresh');
    }

    function tagToSelect() {
      var info = $('#problem').tagsinput("items");
      $("#multiple_problem").val(info);
    }

    $(document).ready(function() {
      $('#problem').on('itemRemoved', tagToSelect);
      $('#problem').on('itemAdded', tagToSelect);
      $("#multiple_problem").change(selectToTag);
    })
  </script>
</body>

</html>