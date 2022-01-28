<?php
header("Cache-control:private");
require_once("../include/db_info.inc.php");
require_once("../lang/$OJ_LANG.php");
require_once("../include/const.inc.php");
require_once("admin-header.php");
echo "<center><h3>" . $MSG_CONTEST . "-" . $MSG_ADD . "</h3></center>";

?>
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

  $sql = "INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`password`,`user_id`)
          VALUES(?,?,?,?,?,?,?,?)";

  $description = str_replace("<p>", "", $description);
  $description = str_replace("</p>", "<br />", $description);
  $description = str_replace(",", "&#44; ", $description);
  $user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
  echo $sql . $title . $starttime . $endtime . $private . $langmask . $description . $password, $user_id;
  $cid = pdo_query($sql, $title, $starttime, $endtime, $private, $langmask, $description, $password, $user_id);
  echo "Add Contest " . $cid;

  $sql = "DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
  $plist = $_POST['problem'];
  $pieces = array();
  foreach ($plist as $i) {
    array_push($pieces, trim($i));
  }

  if (count($pieces) > 0 && intval($pieces[0]) > 0) {
    $sql_1 = "INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) VALUES (?,?,?)";
    $plist = "";
    for ($i = 0; $i < count($pieces); $i++) {
      if ($plist) $plist .= ",";
      $plist .= $pieces[$i];
      pdo_query($sql_1, $cid, $pieces[$i], $i);
    }
    //echo $sql_1;
    $sql = "UPDATE `problem` SET defunct='N' WHERE `problem_id` IN ($plist)";
    pdo_query($sql);
  }

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql, "c$cid");

  $sql = "INSERT INTO `privilege` (`user_id`,`rightstr`) VALUES(?,?)";
  pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], "m$cid");

  $_SESSION[$OJ_NAME . '_' . "m$cid"] = true;

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

  echo "<script>window.location.href=\"contest_list.php\";</script>";
} else {
  if (isset($_GET['cid'])) {
    $cid = intval($_GET['cid']);
    $sql = "SELECT * FROM contest WHERE `contest_id`=?";
    $result = pdo_query($sql, $cid);
    $row = $result[0];
    $title = $row['title'] . "-Copy";

    $private = $row['private'];
    $langmask = $row['langmask'];
    $description = $row['description'];

    $plist = "";
    $sql = "SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? ORDER BY `num`";
    $result = pdo_query($sql, $cid);
    foreach ($result as $row) {
      if ($plist) $plist = $plist . ',';
      $plist = $plist . $row[0];
    }

    $ulist = "";
    $sql = "SELECT `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
    $result = pdo_query($sql, "c$cid");

    foreach ($result as $row) {
      if ($ulist) $ulist .= "\n";
      $ulist .= $row[0];
    }
  } else if (isset($_POST['problem2contest'])) {
    $plist = "";
    //echo $_POST['pid'];
    sort($_POST['pid']);
    foreach ($_POST['pid'] as $i) {
      if ($plist)
        $plist .= ',' . intval($i);
      else
        $plist = $i;
    }
  } else if (isset($_GET['spid'])) {
    //require_once("../include/check_get_key.php");
    $spid = intval($_GET['spid']);

    $plist = "";
    $sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id`>=? ";
    $result = pdo_query($sql, $spid);
    foreach ($result as $row) {
      if ($plist) $plist .= ',';
      $plist .= $row[0];
    }
  }


?>

  <div class="container">
    <form method=POST>
      <p align=left>
        <?php echo "<h3>" . $MSG_CONTEST . "-" . $MSG_TITLE . "</h3>" ?>
        <input class='form-control' style="width:100%;" type=text name=title value="<?php echo isset($title) ? $title : "" ?>"><br /><br />
      </p>
      <div style="margin-bottom: 10px;" class='form-inline'>
        <?php echo $MSG_CONTEST . $MSG_Start ?>:
        <input class='form-control' type=date name='startdate' value='<?php echo date('Y') . '-' . date('m') . '-' . date('d') ?>'>
        Hour: <input class='form-control' type=text name=shour size=2 value=<?php echo date('H') ?>>&nbsp;
        Minute: <input class='form-control' type=text name=sminute value=00 size=2>
      </div>
      <div style="margin-bottom: 5px;" class='form-inline'>
        <?php echo $MSG_CONTEST . $MSG_End ?>:
        <input class='form-control' type=date name='enddate' value='<?php echo date('Y') . '-' . date('m') . '-' . date('d') ?>'>
        Hour: <input class='form-control' type=text name=ehour size=2 value=<?php echo (date('H') + 4) % 24 ?>>&nbsp;
        Minute: <input class='form-control' type=text name=eminute value=00 size=2>
      </div>
      <br />
      <p align=left>
        <?php echo $MSG_CONTEST . "-" . $MSG_PROBLEM_ID ?><br />
        <?php echo $MSG_PLS_ADD ?><br />
        <select name='problem[]' id='multiple_problem' size="10" class="selectpicker show-menu-arrow form-control" multiple style='margin-top:10px;'>
          <?php
          $all = explode(",", $plist);
          $problems = pdo_query("select problem_id,title from problem;");
          foreach ($problems as $i) {
            $pid = $i[0];
            $title = $i[1];
            if (in_array($pid, $all)) {
              echo ("<option value='$pid' selected>$pid $title</option>");
            } else {
              echo ("<option value='$pid'>$pid $title</option>");
            }
          }
          ?></select>
        <br />
      </p>
      <p align=left>
        手动输入<br />
        <input class='form-control' id='problem' data-role="tagsinput" style='margin-top:10px;width:auto;' onchange='return get_tag(this)'></input>
      </p>
      <br />
      <p align=left>
        <?php echo "<h4>" . $MSG_CONTEST . "-" . $MSG_Description . "</h4>" ?>
        <textarea id="tinymce0" rows=13 name=description cols=80><?php echo isset($description) ? $description : "" ?></textarea>
        <br />
      <table width="100%">
        <tr>
          <td rowspan=2>
            <p aligh=left>
              <?php echo $MSG_CONTEST . "-" . $MSG_LANG ?>
              <?php echo "( Add PLs with Ctrl+click )" ?><br />
              <?php echo $MSG_PLS_ADD ?><br />
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
                <option value=0 <?php echo isset($private) && $private == '0' ? 'selected=selected' : '' ?>><?php echo $MSG_Public ?></option>
                <option value=1 <?php echo isset($private) && $private == '1' ? 'selected=selected' : '' ?>><?php echo $MSG_Private ?></option>
              </select>
              &nbsp;&nbsp;
              <?php echo $MSG_CONTEST . "-" . $MSG_PASSWORD ?>:
              <input type=text name=password style="width:150px;" value="" class='form-control'>
            </div>
          </td>
        </tr>
        <tr>
          <td height="*" style="padding:20px;">
            <p align=left>
              <?php echo $MSG_CONTEST . "-" . $MSG_GROUP ?>
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
        <input class='btn btn-default' type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
      </div>
      </p>
    </form>
  </div>

<?php }
require_once("../oj-footer.php");
?>

<script src='<?php echo $OJ_CDN_URL .  "include/" ?>bootstrap-tagsinput.min.js'></script>
<script>
  function get_tag(self) {
    info = $('#problem').tagsinput('items');
    info.forEach(element => {
      $('#multiple_problem').find("option[value='" + element + "']").attr("selected", true)
    });
    return true
  }
</script>

<?php
require_once("admin-footer.php");
?>
<?php require_once('../tinymce/tinymce.php'); ?>