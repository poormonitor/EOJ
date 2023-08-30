<?php
require("admin-header.php");
require_once("../include/iplocation.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}
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
            <h3><?php echo $MSG_HISTORY ?></h3>
          </center>

          <br>

          <div class='container'>

            <?php
            $target = $_GET["target"];

            $sql = "SELECT COUNT(*) AS ids FROM `oplog` WHERE target = ?";
            $result = pdo_query($sql, $target);
            $row = $result[0];

            $ids = intval($row['ids']);

            $idsperpage = 50;
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

            $sql = "SELECT oplog.target, oplog.user_id, users.nick, oplog.operation, oplog.time, oplog.ip FROM oplog
                    LEFT JOIN users ON users.user_id = oplog.user_id WHERE oplog.target = ?";
            $result = pdo_query($sql, $target);
            ?>

            <div class='table-responsive'>
              <table width=100% class='center table table-condensed'>
                <thead>
                  <tr>
                    <th class='center'><?php echo $MSG_HISTORY_TARGET ?></th>
                    <th class='center'><?php echo $MSG_USER ?></th>
                    <th class='center'><?php echo $MSG_HISTORY_OPERATION ?></th>
                    <th class='center'><?php echo $MSG_HISTORY_TIME ?></th>
                    <th class='center'>IP</th>
                    <th class='center'><?php echo $MSG_LOCATION ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row[0] . "</td>";
                    echo "<td><a href='../userinfo.php?user=$row[1]'>" . $row[1] . " " . $row[2] . "</a></td>";
                    echo "<td>" . $row[3] . "</td>";
                    echo "<td>" . $row[4] . "</td>";
                    echo "<td>" . $row[5] . "</td>";
                    echo "<td>" . getLocationFull($row[5]) . "</td>";
                    echo "</tr>";
                  }
                  ?>
              </table>
              </tbody>

            </div>
          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>