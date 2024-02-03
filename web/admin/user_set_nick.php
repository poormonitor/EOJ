<?php
require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

if (isset($_POST["do"])) {
  require_once("../include/check_post_key.php");
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
            <h3><?php echo $MSG_USER . "-" . $MSG_NICK ?></h3>
          </center>

          <div class='container'>

            <?php
            if (isset($_POST['do'])) {
              //echo $_POST['user_id'];
              //echo $_POST['passwd'];
              require_once("../include/my_func.inc.php");

              $pieces = explode("\n", trim($_POST['ulist']));
              $ulist = "";
              if (count($pieces) > 0 && strlen($pieces[0]) > 0) {
                for ($i = 0; $i < count($pieces); $i++) {
                  $id_pw = explode(" ", trim($pieces[$i]));
                  if (count($id_pw) != 2) {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $id_pw[0] . " ... $MSG_ERROR : $MSG_PARAMS_ERROR <br>";
                    for ($j = 0; $j < count($id_pw); $j++) {
                      $ulist = $ulist . $id_pw[$j] . " ";
                    }
                    $ulist = trim($ulist) . "\n";
                  } else {
                    $sql = "SELECT `user_id` FROM `users` WHERE `users`.`user_id` = ?";
                    $result = pdo_query($sql, $id_pw[0]);
                    $rows_cnt = count($result);

                    if ($rows_cnt != 1) {
                      echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $id_pw[0] . " ... $MSG_ERROR : $MSG_EXISTED<br>";
                      $ulist = $ulist . $id_pw[0] . " " . $id_pw[1] . "\n";
                    } else {
                      $nick = $id_pw[1];
                      $sql = "UPDATE `users` set `nick`=? WHERE `user_id`=?;";
                      pdo_query($sql, $nick, $id_pw[0]);
                      $sql = "UPDATE `solution` set `nick`=? WHERE `user_id`=?;";
                      pdo_query($sql, $nick, $id_pw[0]);

                      $ip = getRealIP();
                      $sql = "INSERT INTO `loginlog` VALUES(?,?,?,NOW())";
                      pdo_query($sql, $id_pw[0], "user nick set by " . $_SESSION[$OJ_NAME . "_" . "user_id"], $ip);

                      echo $id_pw[0] . "$MSG_SUCCESS!<br>";
                    }
                  }
                }
                echo "<br>$MSG_REMAINED_ERROR<hr>";
              }
            }
            ?>

            <form action=user_set_nick.php method=post class="form-horizontal">
              <div>
                <label class="col-sm"><?php echo $MSG_USER_ID ?> <?php echo $MSG_NICK ?></label>
              </div>
              <div>
                <table width="100%">
                  <tr>
                    <td height="*">
                      <p align=left>
                        <textarea class='form-control' name='ulist' rows='10' style='width:100%;' placeholder='userid1 nick1<?php echo "\n" ?>userid2 nick2<?php echo "\n" ?>userid3 nick3<?php echo "\n" ?>'><?php if (isset($ulist)) {
                                                                                                                                                                                                                echo $ulist;
                                                                                                                                                                                                              } ?></textarea>
                      </p>
                    </td>
                  </tr>
                </table>
              </div>
              <br>
              <div class="form-group">
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

          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>