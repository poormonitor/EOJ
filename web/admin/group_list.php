<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

if (isset($_GET['do'])) {
  require_once("../include/check_get_key.php");

  if (isset($_GET["add"])) {
    $group_name = trim($_GET["add"]);
    echo pdo_query("SELECT COUNT(*) FROM `group` WHERE `name` = ?", $group_name)[0][0];
    if (!pdo_query("SELECT COUNT(*) FROM `group` WHERE `name` = ?", $group_name)[0][0]) {
      $gid = pdo_query("INSERT INTO `group` (`name`, `allow_view`) VALUES (?, 'N');", $group_name);

      $ip = getRealIP();
      $sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
      pdo_query($sql, "g$gid", $_SESSION[$OJ_NAME . '_' . 'user_id'], "add", $ip);
    }
  }

  if (isset($_GET["del"])) {
    $del_group = trim($_GET["del"]);
    pdo_query("DELETE FROM `group` WHERE `gid`=?;", $del_group);

    $ip = getRealIP();
    $sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
    pdo_query($sql, "g$del_group", $_SESSION[$OJ_NAME . '_' . 'user_id'], "delete", $ip);
  }

  if (isset($_GET["visiable"])) {
    $visiable = trim($_GET["visiable"]);
    $gid = trim($_GET["group"]);
    if ($visiable == "true") {
      pdo_query("UPDATE `group` SET allow_view='Y' WHERE gid=?", $gid);
    } else {
      pdo_query("UPDATE `group` SET allow_view='N' WHERE gid=?", $gid);
    }

    $ip = getRealIP();
    $sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
    pdo_query($sql, "g$gid", $_SESSION[$OJ_NAME . '_' . 'user_id'], "change visible", $ip);
  }

  header("Location: group_list.php");
  exit(0);
}

require("admin-header.php");
require_once("../include/set_get_key.php");
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
              <form action=group_list.php class="form-search form-inline">
                <input type="text" name="add" class="form-control search-query" placeholder="<?php echo $MSG_GROUP ?>">
                <input type=hidden name="getkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'getkey'] ?>">
                <button name="do" value="do" type="submit" class="form-control"><?php echo $MSG_ADD ?></button>
              </form>
            </center>
            <br>
            <div class='table-responsive'>
              <table width=100% class='center table table-condensed'>
                <thead>
                  <tr>
                    <th class='center'>GID</th>
                    <th class='center'><?php echo $MSG_GROUP ?></th>
                    <th class='center'><?php echo $MSG_DELETE ?></th>
                    <th class='center'><?php echo $MSG_VIEW_ERROR ?></th>
                    <th class='center'><?php echo $MSG_HISTORY ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['gid'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td><a href='group_list.php?do=do&del=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'>$MSG_DELETE</a></td>";
                    if ($row["allow_view"] == "Y") {
                      echo "<td><a href='group_list.php?do=do&visiable=false&group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'><span class=green>$MSG_TRUE</span></a></td>";
                    } else {
                      echo "<td><a href='group_list.php?do=do&visiable=true&group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'><span class=red>$MSG_FALSE</span></a></td>";
                    }
                    echo "<td><a href=history.php?target=g" . $row['gid'] . ">$MSG_HISTORY</a></td>";

                    echo "</tr>";
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
          <br><br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>