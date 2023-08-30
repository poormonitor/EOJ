<?php require_once("admin-header.php");

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
            <h3><?php echo $MSG_GROUP . "-" . $MSG_ADD ?></h3>
          </center>

          <div class='container'>

            <?php
            if (isset($_POST['do']) and $_POST['do'] != "" and isset($_POST['gid']) and $_POST['gid'] != "" and $_POST['gid'] != "-1") {
              //echo $_POST['user_id'];
              require_once("../include/check_post_key.php");
              //echo $_POST['passwd'];
              require_once("../include/my_func.inc.php");

              $gid = $_POST['gid'];
              $pieces = explode("\n", trim($_POST['ulist']));
              $pieces = preg_replace("/[^\x20-\x7e]/", " ", $pieces);  //!!important
              $ip = getRealIP();
              $count = 0;
              foreach ($pieces as $i) {
                // delete all right
                pdo_query("DELETE FROM privilege WHERE user_id = ? AND rightstr LIKE 'c%'", $i);
                if ($gid == "0") {
                  pdo_query("UPDATE `users` SET `gid`=NULL WHERE `user_id`=?", $i);
                } else {
                  // set gid
                  pdo_query("UPDATE `users` SET `gid`=? WHERE `user_id`= ?;", $gid, $i);
                  // get all contests
                  $result = pdo_query("SELECT rightstr FROM privilege WHERE user_id = (SELECT user_id FROM users WHERE users.gid = ? LIMIT 1) AND rightstr LIKE 'c%'", $gid);
                  foreach ($result as $p) {
                    //update
                    pdo_query("INSERT INTO privilege (user_id,rightstr) VALUES (?,?)", $i, $p[0]);
                  }
                }

                $sql = "INSERT INTO `loginlog` VALUES(?,?,?,NOW())";
                pdo_query($sql, $id_pw[0], "group set by " . $_SESSION[$OJ_NAME . "_" . "user_id"], $ip);
                
                $count += 1;
              }
              echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>$count $MSG_SUCCESS</div>";
            }
            ?>

            <form action=group_add.php method=post class="form-horizontal">
              <div>
                <label class="col-sm"><?php echo $MSG_USER_ID ?></label>
              </div>
              <div>
                <?php echo "( Add new user newline )" ?>
                <br><br>
                <table width="100%">
                  <tr>
                    <td height="*">
                      <p align=left>
                        <textarea class='form-control' name='ulist' rows='10' style='width:100%;' placeholder="userid1&#10;userid2&#10;userid3"></textarea>
                      </p>
                    </td>
                  </tr>
                </table>
              </div>

              <div class="form-group">
                <?php require_once("../include/set_post_key.php"); ?>
                <div class='col-md-4'></div>
                <div class='col-md-4'>
                  <div style='text-align:center;'><?php echo $MSG_GROUP ?></div>
                  <select class="form-control" size="1" name="gid">
                    <option value='0'><?php echo $MSG_DELETE ?></option>
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
                          echo "<option value=$show_id selected>$show_name</option>";
                        } else {
                          echo "<option value=$show_id >$show_name</option>";
                        }
                      } else {
                        echo "<option value=$show_id >$show_name</option>";
                      }
                    }
                    ?>
                  </select>&nbsp;&nbsp;
                  <button name="do" type="hidden" value="do" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
                </div>
                <div class='col-md-4'></div>
              </div>

            </form>

          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>