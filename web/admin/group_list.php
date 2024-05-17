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

    $sql = "DELETE FROM `group` WHERE `gid`= ?";
    pdo_query($sql, $del_group);

    $sql = "UPDATE users SET gid = NULL WHERE gid = ?";
    pdo_query($sql, $del_group);

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

            $sql = "SELECT COUNT('gid') AS ids FROM `group`";
            $result = pdo_query($sql);
            $row = $result[0];

            $ids = intval($row['ids']);

            $idsperpage = 25;
            $pages = intval(ceil($ids / $idsperpage));

            if (isset($_GET['page'])) {
              $page = intval($_GET['page']);
            } else {
              $page = 1;
            }

            $pagesperframe = 5;
            $frame = intval(ceil($page / $pagesperframe));

            $spage = ($frame - 1) * $pagesperframe + 1;
            $epage = min($spage + $pagesperframe - 1, $pages);

            $sid = ($page - 1) * $idsperpage;

            $sql = "SELECT `gid`,`name`,`allow_view` FROM `group` ORDER BY `gid` DESC LIMIT $sid,$idsperpage";
            $result = pdo_query($sql);
            ?>
            <center>
              <form action=group_list.php class="form-search form-inline">
                <input type="text" name="add" class="form-control search-query" placeholder="<?php echo $MSG_GROUP ?>">
                <input type=hidden name="getkey" value="<?php echo end($_SESSION[$OJ_NAME . '_' . 'getkey']) ?>">
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
                    echo "<td><a href='javascript:confirmDelete(\"" . $row['gid'] . "\",\"" . end($_SESSION[$OJ_NAME . "_" . "getkey"]) . "\")'>$MSG_DELETE</a></td>";
                    if ($row["allow_view"] == "Y") {
                      echo "<td><a href='group_list.php?do=do&visiable=false&group=" . $row['gid'] . "&getkey=" . end($_SESSION[$OJ_NAME . '_' . 'getkey']) . "'><span class=green>$MSG_TRUE</span></a></td>";
                    } else {
                      echo "<td><a href='group_list.php?do=do&visiable=true&group=" . $row['gid'] . "&getkey=" . end($_SESSION[$OJ_NAME . '_' . 'getkey']) . "'><span class=red>$MSG_FALSE</span></a></td>";
                    }
                    echo "<td><a href=history.php?target=g" . $row['gid'] . ">$MSG_HISTORY</a></td>";

                    echo "</tr>";
                  } ?>
                </tbody>
              </table>
            </div>

            <?php
            echo "<div style='display:inline;'>";
            echo "<nav class='center'>";
            echo "<ul class='pagination pagination-sm'>";
            echo "<li class='page-item'><a href='group_list.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
            echo "<li class='page-item'><a href='group_list.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
            for ($i = $spage; $i <= $epage; $i++) {
              echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='group_list.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
            }
            echo "<li class='page-item'><a href='group_list.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
            echo "<li class='page-item'><a href='group_list.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
            echo "</ul>";
            echo "</nav>";
            echo "</div>";
            ?>
          </div>

          <br><br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <script>
    function confirmDelete(gid, getkey) {
      swal({
        title: "<?php echo $MSG_CONFIRM ?>",
        text: "<?php echo $MSG_CONFIRM_MSG ?>",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          window.location.href = "group_list.php?do=do&del=" + gid + "&getkey=" + getkey;
        }
      });
    }
  </script>
</body>

</html>