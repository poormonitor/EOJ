<?php
require("admin-header.php");

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
          <div class="container">
            <br>
            <?php
            $csql = array();

            $csql[0] = "DELETE FROM solution WHERE result=13;";
            $csql[1] = "DELETE source_code FROM source_code 
                        LEFT JOIN solution ON source_code.solution_id = solution.solution_id
                        WHERE solution.solution_id IS NULL;";
            $csql[3] = "DELETE runtimeinfo FROM runtimeinfo
                        LEFT JOIN solution ON runtimeinfo.solution_id = solution.solution_id
                        WHERE solution.solution_id IS NULL;";
            $csql[4] = "DELETE compileinfo FROM compileinfo
                        LEFT JOIN solution ON compileinfo.solution_id = solution.solution_id
                        WHERE solution.solution_id IS NULL;";
            $csql[5] = "DELETE sim FROM sim
                        LEFT JOIN solution ON sim.sim_s_id = solution.solution_id
                        WHERE solution.solution_id IS NULL;";
            $csql[6] = "DELETE sim FROM sim
                        LEFT JOIN solution ON sim.s_id = solution.solution_id
                        WHERE solution.solution_id IS NULL;";
            $csql[7] = "DELETE privilege FROM privilege
                        LEFT JOIN users ON privilege.user_id = users.user_id
                        WHERE users.user_id IS NULL;";
            $csql[8] = "UPDATE solution JOIN users ON users.user_id = solution.user_id SET solution.nick = users.nick;";
            $csql[9] = "UPDATE users INNER JOIN (SELECT user_id, 
                        COUNT(DISTINCT CASE WHEN result = 4 THEN problem_id END) as solved_cnt,
                        COUNT(CASE WHEN problem_id > 0 THEN solution_id END) as submit_cnt
                        FROM solution GROUP BY user_id) s ON users.user_id = s.user_id 
                        SET users.solved = s.solved_cnt, users.submit = s.submit_cnt;";

            if (isset($_POST['db'])) {
              for ($i = 0; isset($csql[$i]); $i++) {
                pdo_query($csql[$i]);
              }
              $banner = true;
            } elseif (isset($_POST['cache'])) {
              require_once("../include/memcache.php");
              updateCache();
              $banner = true;
            } elseif (isset($_POST['update'])) {
              $shell = "git pull & cd ../../core/ && ./make.sh \
                        && service judged restart > /dev/null 2>/dev/null &";
              shell_exec($shell);
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
              <input type="hidden" name="do" value="true">
              <input type=submit class='btn btn-info btn-nm' value='<?php echo $MSG_UPDATE_DATABASE ?>'>
            </form>
            <form action='update_db.php' method=post class='middle mt-3'>
              <input type=hidden name="postkey" value="<?php echo end($_SESSION[$OJ_NAME . '_' . 'postkey']) ?>">
              <input type='hidden' name='cache' value='do'>
              <input type="hidden" name="do" value="true">
              <input type=submit class='btn btn-info btn-nm' value='<?php echo $MSG_DELETE ?> Cache'>
            </form>
            <form action='update_db.php' method=post class='middle mt-3'>
              <input type=hidden name="postkey" value="<?php echo end($_SESSION[$OJ_NAME . '_' . 'postkey']) ?>">
              <input type='hidden' name='update' value='do'>
              <input type="hidden" name="do" value="true">
              <input type=submit class='btn btn-info btn-nm' value='<?php echo $MSG_UPGRADE ?> EOJ'>
            </form>
            <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
              <form action='adminer.php' method=post class="center mt-3">
                <input type="hidden" name="auth[driver]" value="server">
                <input type="hidden" name="auth[server]" value="<?php echo $DB_HOST ?>">
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