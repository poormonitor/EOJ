<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>
</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">

      <form action="modify.php" method="post" role="form" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_REG_INFO ?></label>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_USER_ID ?></label>
          <div class="col-sm-4"><label class="col-sm-2 control-label"><?php echo $_SESSION[$OJ_NAME . '_' . 'user_id'] ?></label></div>
          <?php require_once('./include/set_post_key.php'); ?>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_NICK ?></label>
          <div class="col-sm-4"><input name="nick" class="form-control" value="<?php echo htmlentities($row['nick'], ENT_QUOTES, "UTF-8") ?>" type="text" disabled></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_PASSWORD ?></label>
          <div class="col-sm-4"><input name="opassword" class="form-control" placeholder="<?php echo $MSG_PASSWORD ?>*" type="password"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo "New " . $MSG_PASSWORD ?></label>
          <div class="col-sm-4"><input name="npassword" class="form-control" type="password"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo "New " . $MSG_REPEAT_PASSWORD ?></label>
          <div class="col-sm-4"><input name="rptpassword" class="form-control" type="password"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_SCHOOL ?></label>
          <div class="col-sm-4"><input name="school" class="form-control" value="<?php echo htmlentities($row['school'], ENT_QUOTES, "UTF-8") ?>" type="text" disabled></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_EMAIL ?></label>
          <div class="col-sm-4"><input name="email" class="form-control" value="<?php echo htmlentities($row['email'], ENT_QUOTES, "UTF-8") ?>" type="text"></div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-4 col-sm-2">
            <button name="submit" type="submit" class="btn btn-default btn-block"><?php echo $MSG_SUBMIT; ?></button>
          </div>
          <div class="col-sm-2">
            <button name="submit" type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET; ?></button>
          </div>
        </div>
      </form>

      <a class='btn btn-success btn-sm' style='margin:10px;' href=export_ac_code.php><?php echo $MSG_DOWNLOAD_ALL_AC ?></a><br>
    </div>
  </div>
  <?php include("template/js.php"); ?>
</body>

</html>