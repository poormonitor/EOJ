<?php require_once("admin-header.php");?>

<center>
  <h3><?php echo $MSG_USER . "-" . $MSG_ADD ?></h3>
</center>

<div class='container'>

  <?php
  if (isset($_POST['do'])) {
    //echo $_POST['user_id'];
    require_once("../include/check_post_key.php");
    //echo $_POST['passwd'];
    require_once("../include/my_func.inc.php");

    $pieces = explode("\n", trim($_POST['ulist']));
    $gid = intval(trim($_POST['gid']));

    $ulist = "";
    if (count($pieces) > 0 && strlen($pieces[0]) > 0) {
      for ($i = 0; $i < count($pieces); $i++) {
        $id_pw = explode(" ", trim($pieces[$i]));
        $id_pw[0] = preg_replace("/[^\x20-\x7e]/", " ", $id_pw[0]);
        $id_pw[1] = preg_replace("/[^\x20-\x7e]/", " ", $id_pw[1]);
        if (count($id_pw) != 2 and count($id_pw) != 3) {
          echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $id_pw[0] . " ... Error : Line format error!<br>";
          for ($j = 0; $j < count($id_pw); $j++) {
            $ulist = $ulist . $id_pw[$j] . " ";
          }
          $ulist = trim($ulist) . "\n";
        } else {
          $sql = "SELECT `user_id` FROM `users` WHERE `users`.`user_id` = ?";
          $result = pdo_query($sql, $id_pw[0]);
          $rows_cnt = count($result);

          if ($rows_cnt == 1) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $id_pw[0] . " ... Error : User already exist!<br>";
            $ulist = $ulist . $id_pw[0] . " " . $id_pw[1];
            if (count($id_pw) == 3) {
              $ulist .= " " . $id_pw[2];
            }
            $ulist .= "\n";
          } else {
            $passwd = pwGen($id_pw[1]);
            if (count($id_pw) == 3) {
              $nick = $id_pw[2];
            } else {
              $nick = $id_pw[0];
            }
            $sql = "INSERT INTO `users` (`user_id`, `password`, `reg_time`, `nick`, `gid`) VALUES (?, ?, NOW(), ?, ?);";
            pdo_query($sql, $id_pw[0], $passwd, $nick, $gid);
            echo $id_pw[0] . " is added!<br>";

            $ip = ($_SERVER['REMOTE_ADDR']);
            $sql = "INSERT INTO `loginlog` VALUES(?,?,?,NOW())";
            pdo_query($sql, $id_pw[0], "user added", $ip);
          }
        }
      }
      echo "<br>Remained lines have error!<hr>";
    }
  }
  ?>

  <form action=user_add.php method=post class="form-horizontal">
    <div>
      <label class="col-sm"><?php echo $MSG_USER_ID ?> <?php echo $MSG_PASSWORD ?> &nbsp;&nbsp;姓名（可选）</label>
    </div>
    <div>
      <?php echo "( Add new user, password and name(optional) with newline )" ?>
      <br><br>
      <table width="100%">
        <tr>
          <td height="*">
            <p align=left>
              <textarea class='form-control' name='ulist' rows='10' style='width:100%;' placeholder='userid1 password1 (name1)<?php echo "\n" ?>userid2 password2 (name2)<?php echo "\n" ?>userid3 password3 (name3)<?php echo "\n" ?>
            <?php echo "\n" ?>'><?php if (isset($ulist)) {
                                  echo $ulist;
                                } ?></textarea>
            </p>
          </td>
        </tr>
      </table>
    </div>
    <div class="form-group">
      <div style='width:30%; margin:auto;'>
        <select class="form-control" size="1" name="gid">
          <option value="0" selected>Null</option>
          <?php
          require_once("../include/my_func.inc.php");
          $sql_all = "SELECT * FROM `group`;";
          $result = pdo_query($sql_all);
          $all_group = $result;
          if (isset($_GET['gid'])) {
            $gid = intval($_GET['gid']);
          }
          foreach ($all_group as $i) {
            $show_id = $i["gid"];
            $show_name = $i["name"];
            if (isset($_GET['gid'])) {
              if ($_GET['gid'] == $show_id) {
                echo "<option value='$show_id' selected>$show_name</option>";
              } else {
                echo "<option value='$show_id' >$show_name</option>";
              }
            } else {
              echo "<option value='$show_id' >$show_name</option>";
            }
          }
          ?>
        </select>&nbsp;&nbsp;
      </div>
      <?php require_once("../include/set_post_key.php"); ?>
      <div class="col-sm-offset-4 col-sm-2">
        <button name="do" type="hidden" value="do" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
      </div>
      <div class="col-sm-2">
        <button name="submit" type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET ?></button>
      </div>
    </div>

  </form>

</div>
<?php
require_once("admin-footer.php");
?>