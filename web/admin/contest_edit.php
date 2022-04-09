<?php
require_once("../include/db_info.inc.php");
require_once("../include/const.inc.php");

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

    pdo_query("update solution set num=-1 where contest_id=?", $cid);

    $plist = "";
    for ($i = 0; $i < count($pieces); $i++) {
      if ($plist) $plist .= ",";
      $plist .= $pieces[$i];
      $sql_2 = "update solution set num=? where contest_id=? and problem_id=?;";
      pdo_query($sql_2, $i, $cid, $pieces[$i]);
    }

    $sql = "update `problem` set defunct='N' where `problem_id` in ($plist)";
    pdo_query($sql);
  }

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql, "c$cid");
  if (intval($private) == 1) {
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
        pdo_query($sql_1, trim($pieces[$i]), "c$cid");
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

  $ulist = "";
  $sql = "SELECT `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
  $result = pdo_query($sql, "c$cid");

  foreach ($result as $row) {
    if ($ulist) $ulist .= "\n";
    $ulist .= $row[0];
  }
}
require_once("admin-header.php");
?>
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
        $problems = pdo_query("select problem_id,title from problem;");
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
      手动输入<br>
      <input id='problem' data-role="tagsinput" class='form-control' style='margin-top:10px;' onchange='return set_tag(this)'></input>
    </p>
    <br>
    <p align=left>
      <?php echo "<h4>" . $MSG_CONTEST . "-" . $MSG_Description . "</h4>" ?>
      <textarea id="tinymce0" rows=13 name=description cols=80>
        <?php echo htmlentities($description, ENT_QUOTES, 'UTF-8') ?>
      </textarea>
      <br>
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
              $cid = intval($_GET['cid']);
              $gid_before = pdo_query("SELECT `users`.`gid` FROM `privilege` JOIN `users` ON `privilege`.`user_id`=`users`.`user_id` WHERE `privilege`.rightstr='c$cid' GROUP BY `users`.`gid`;");
              $sql_all = "SELECT * FROM `group`;";
              $result = pdo_query($sql_all);
              $all_group = $result;
              $gid = array();
              foreach ($gid_before as $i) {
                array_push($gid, $i[0]);
              }
              foreach ($all_group as $i) {
                $show_id = $i["gid"];
                $show_name = $i["name"];
                if (in_array($show_id, $gid)) {
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
    </p>
  </form>
</div>
</body>
<script src='<?php echo $OJ_CDN_URL ?>include/bootstrap-tagsinput.min.js'></script>
<script>
  function set_tag(self) {
    info = $('#problem').tagsinput('items');
    info.forEach(element => {
      $('#multiple_problem').find("option[value='" + element + "']").attr("selected", true)
    });
    return true
  }
</script>

<?php
require_once("admin-footer.php");
require_once('../tinymce/tinymce.php');
?>