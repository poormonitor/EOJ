<?php require("admin-header.php"); ?>
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
          <div class="container">
            <br>
            <?php
            $csql = array();

            $csql[0] = "DELETE FROM solution WHERE result=13;";
            $csql[1] = "DELETE FROM source_code WHERE solution_id NOT IN (SELECT solution_id FROM solution);";
            $csql[2] = "DELETE FROM runtimeinfo WHERE solution_id NOT IN (SELECT solution_id FROM solution);";
            $csql[3] = "DELETE FROM compileinfo WHERE solution_id NOT IN (SELECT solution_id FROM solution);";
            $csql[4] = "UPDATE solution JOIN users ON users.user_id = solution.user_id SET solution.nick = users.nick;";
            $csql[5] = "DELETE FROM sim WHERE sim_s_id NOT IN (SELECT solution_id FROM solution);";
            $csql[6] = "DELETE FROM sim WHERE s_id NOT IN (SELECT solution_id FROM solution);";
            $csql[7] = "DELETE FROM loginlog WHERE `time` < DATE_SUB(NOW(), INTERVAL 1 YEAR)";

            if (isset($_POST['db'])) {
              require_once("../include/check_post_key.php");
              for ($i = 0; isset($csql[$i]); $i++) {
                pdo_query($csql[$i]);
              }
              $banner = true;
            } elseif (isset($_POST['cache'])) {
              require_once("../include/check_post_key.php");
              require_once("../include/memcache.php");
              updateCache();
              $banner = true;
            }
            ?>
            <br>
            <h3 class='center'><?php echo $MSG_UPDATE_DATABASE ?></h3>
            <?php if (isset($banner)) { ?>
              <div class="row">
                <div class="col-sm-4"></div>
                <div class="alert alert-success center col-sm-4" role="alert"><?php echo $MSG_SUCCESS ?></div>
                <div class="col-sm-4"></div>
              </div>
            <?php } ?>
            <p class='center'>
              <?php echo $MSG_HELP_UPDATE_DATABASE ?>
            </p>
            <br>
            <form action='update_db.php' method=post class='middle mt-3'>
              <?php require_once("../include/set_post_key.php"); ?>
              <input type='hidden' name='db' value='do'>
              <input type=submit class='btn btn-info btn-nm' value='<?php echo $MSG_UPGRADE ?>'>
            </form>
            <form action='update_db.php' method=post class='middle mt-3'>
              <input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
              <input type='hidden' name='cache' value='do'>
              <input type=submit class='btn btn-info btn-nm' value='<?php echo $MSG_DELETE ?> Cache'>
            </form>
            <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
              <form action='adminer.php' method=post class="center mt-3">
                <input type="hidden" name="auth[driver]" value="server">
                <input type="hidden" name="auth[server]" value="">
                <input type="hidden" name="auth[username]" value="<?php echo $DB_USER ?>">
                <input type="hidden" name="auth[password]" value="<?php echo $DB_PASS ?>">
                <input type="hidden" name="auth[db]" value="<?php echo $DB_NAME ?>">
                <input type="submit" class="btn btn-primary btn-nm" value="Adminer">
              </form>
            <?php } ?>
          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>