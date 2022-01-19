<?php
require("admin-header.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
if (isset($OJ_LANG)) {
  require_once("../lang/$OJ_LANG.php");
}
if (isset($_GET['do'])) {
  if (isset($_GET["group_name"])) {
    require_once("../include/check_get_key.php");
    $group_name = trim($_GET["group_name"]);
    if (count(pdo_query("SELECT COUNT(*) FROM group where name = ?", $group_name)) != 0) {
      echo "<div class='alert alert-danger' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>有同名组，添加失败。</div>";
    } else {
      pdo_query("INSERT INTO `group` (`name`) VALUES (?);", $group_name);
      echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>添加成功！</div>";
    }
  }
  if (isset($_GET["del_group"])) {
    require_once("../include/check_get_key.php");
    $del_group = trim($_GET["del_group"]);
    pdo_query("DELETE FROM `group` WHERE `gid`=?;", $del_group);
    echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>删除成功！</div>";
  }
  if (isset($_GET["visiable"])) {
    require_once("../include/check_get_key.php");
    $visiable = trim($_GET["visiable"]);
    $gid = trim($_GET["group"]);
    if ($visiable == "true") {
      pdo_query("UPDATE `group` SET allow_view='Y' WHERE gid=?", $gid);
    } else {
      pdo_query("UPDATE `group` SET allow_view='N' WHERE gid=?", $gid);
    }
    echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>修改成功！</div>";
  }
}
?>
<title>Group List</title>
<hr>
<center>
  <h3><?php echo $MSG_GROUP . "-" . $MSG_LIST ?></h3>
</center>

<div class='container'>
  <?php
  require_once("../include/set_get_key.php");
  $sql = "SELECT `gid`,`name`,`allow_view` FROM `group` ORDER BY `gid` DESC";
  $result = pdo_query($sql);
  ?>
  <center>
    <table width=100% border=1 style="text-align:center;">
      <tr>
        <td>GID</td>
        <td>组名</td>
        <td>删除</td>
        <td>查看错误</td>
      </tr>
      <?php
      foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['gid'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td><a href='group_list.php?do=do&del_group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'>删除</a></td>";
        if ($row["allow_view"] == "Y") {
          echo "<td><a href='group_list.php?do=do&visiable=false&group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'><span class=green>允许</span></a></td>";
        } else {
          echo "<td><a href='group_list.php?do=do&visiable=true&group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'><span class=red>禁止</span></a></td>";
        }

        echo "</tr>";
      } ?>
    </table>
  </center>
</div>
<br /><br />
<center>
  <form action=group_list.php class="form-search form-inline">
    <input type="text" name=group_name class="form-control search-query" placeholder="<?php echo $MSG_GROUP ?>">
    <input type=hidden name="getkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'getkey'] ?>">
    <button name="do" value="do" type="submit" class="form-control"><?php echo $MSG_ADD ?></button>
  </form>
</center>