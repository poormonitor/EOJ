<?php
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
        <div class='col-md-10 p-0'>
          <center>
            <h3><?php echo $MSG_USER . "-" . $MSG_LIST ?></h3>
          </center>

          <div class='container'>

            <?php
            $sql = "SELECT COUNT('user_id') AS ids FROM `users`";
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

            $ks = "";
            $sql = "";
            if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
              $ks = $keyword = $_GET['keyword'];
              $keyword = "%$keyword%";
              $sql = "SELECT `user_id`,`nick`,`accesstime`,`reg_time`,`ip`,`school`,`defunct`,`name` FROM `users` LEFT JOIN `group` ON `users`.`gid`=`group`.`gid` WHERE (name LIKE ?) OR (user_id LIKE ?) OR (nick LIKE ?) OR (school LIKE ?) ORDER BY `user_id` DESC";
              $result = pdo_query($sql, $keyword, $keyword, $keyword, $keyword);
            } else {
              $sql = "SELECT `user_id`,`nick`,`accesstime`,`reg_time`,`ip`,`school`,`defunct`,`name` FROM `users` LEFT JOIN `group` ON `users`.`gid`=`group`.`gid` ORDER BY `reg_time` DESC LIMIT $sid, $idsperpage";
              $result = pdo_query($sql);
            }
            ?>

            <center>
              <form action=user_list.php class="form-search form-inline">
                <input type="text" name=keyword class="form-control search-query" value="<?php echo $ks ?>" placeholder="<?php echo $MSG_USER_ID . ', ' . $MSG_NICK . ', ' . $MSG_SCHOOL ?>">
                <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
              </form>
            </center>
            <br>

            <div class='table-responsive'>
              <table width=100% class='center table table-condensed'>
                <thead>
                  <tr>
                    <th class='center'>ID</th>
                    <th class='center'><?php echo $MSG_NICK ?></th>
                    <th class='center'><?php echo $MSG_SCHOOL ?></th>
                    <th class='center'><?php echo $MSG_GROUP ?></th>
                    <th class='center'><?php echo $MSG_RECENT_LOGIN ?></th>
                    <th class='center'><?php echo $MSG_REG_TIME ?></th>
                    <th class='center'><?php echo $MSG_STATUS ?></th>
                    <th class='center'><?php echo $MSG_PASSWORD ?></th>
                    <th class='center'><?php echo $MSG_PRIVILEGE ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td><a href='../userinfo.php?user=" . $row['user_id'] . "'>" . $row['user_id'] . "</a></td>";
                    echo "<td>" . $row['nick'] . "</td>";
                    echo "<td>" . $row['school'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['accesstime'] . "</td>";
                    echo "<td>" . $row['reg_time'] . "</td>";
                    if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
                      echo "<td><a href=user_df_change.php?cid=" . $row['user_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['defunct'] == "N" ? "<span class=green>$MSG_ENABLED</span>" : "<span class=red>$MSG_DISABLED</span>") . "</a></td>";
                    } else {
                      echo "<td>" . ($row['defunct'] == "N" ? "<span>$MSG_ENABLED</span>" : "<span>$MSG_DISABLED</span>") . "</td>";
                    }
                    echo "<td><a href=changepass.php?uid=" . $row['user_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . $MSG_RESET . "</a></td>";
                    echo "<td><a href=privilege_add.php?uid=" . $row['user_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . $MSG_ADD . "</a></td>";
                    echo "</tr>";
                  } ?>
                </tbody>
              </table>
            </div>

            <?php
            if (!(isset($_GET['keyword']) && $_GET['keyword'] != "")) {
              echo "<div style='display:inline;'>";
              echo "<nav class='center'>";
              echo "<ul class='pagination pagination-sm'>";
              echo "<li class='page-item'><a href='user_list.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
              echo "<li class='page-item'><a href='user_list.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
              for ($i = $spage; $i <= $epage; $i++) {
                echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='user_list.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
              }
              echo "<li class='page-item'><a href='user_list.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
              echo "<li class='page-item'><a href='user_list.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
              echo "</ul>";
              echo "</nav>";
              echo "</div>";
            }
            ?>

          </div>
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>