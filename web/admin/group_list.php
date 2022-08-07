<?php
if (isset($_GET['do'])) {
  require_once("../include/db_info.inc.php");
  require_once("../include/my_func.inc.php");

  if (isset($_GET["group_name"])) {
    require_once("../include/check_get_key.php");
    $group_name = trim($_GET["group_name"]);
    if (count(pdo_query("SELECT COUNT(*) FROM group where name = ?", $group_name)) == 0) {
      pdo_query("INSERT INTO `group` (`name`) VALUES (?);", $group_name);
    }
  }

  if (isset($_GET["del_group"])) {
    require_once("../include/check_get_key.php");
    $del_group = trim($_GET["del_group"]);
    pdo_query("DELETE FROM `group` WHERE `gid`=?;", $del_group);
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
  }

  header("Location: group_list.php");
  exit(0);
}

require("admin-header.php");
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
                <input type="text" name=group_name class="form-control search-query" placeholder="<?php echo $MSG_GROUP ?>">
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
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['gid'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td><a href='group_list.php?do=do&del_group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'>$MSG_DELETE</a></td>";
                    if ($row["allow_view"] == "Y") {
                      echo "<td><a href='group_list.php?do=do&visiable=false&group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'><span class=green>$MSG_TRUE</span></a></td>";
                    } else {
                      echo "<td><a href='group_list.php?do=do&visiable=true&group=" . $row['gid'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "'><span class=red>$MSG_FALSE</span></a></td>";
                    }

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