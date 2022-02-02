<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>



</head>

<body>

  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">

      <form action="register.php" onsubmit="return check();" method="post" role="form" class="form-horizontal">

        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_REG_INFO ?></label>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_USER_ID ?></label>
          <div class="col-sm-4"><input id="user_id" name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID ?>*" type="text" required></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_NICK ?></label>
          <div class="col-sm-4"><input id="nick" name="nick" class="form-control" placeholder="<?php echo $MSG_NICK ?>*" type="text" required></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_PASSWORD ?></label>
          <div class="col-sm-4"><input id="password" name="password" class="form-control" placeholder="<?php echo $MSG_PASSWORD ?>*" type="password" autocomplete="off" required></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_REPEAT_PASSWORD ?></label>
          <div class="col-sm-4"><input id="rptpassword" name="rptpassword" class="form-control" placeholder="<?php echo $MSG_REPEAT_PASSWORD ?>*" type="password" autocomplete="off" required></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_SCHOOL ?></label>
          <div class="col-sm-4"><input id="school" name="school" class="form-control" placeholder="<?php echo $MSG_SCHOOL ?>" type="text"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label"><?php echo $MSG_EMAIL ?></label>
          <div class="col-sm-4"><input id="email" name="email" class="form-control" placeholder="<?php echo $MSG_EMAIL ?>" type="text" required></div>
        </div>

        <?php if ($OJ_VCODE) { ?>
          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $MSG_VCODE ?></label>
            <div class="col-sm-3">
              <input name="vcode" class="form-control" placeholder="<?php echo $MSG_VCODE ?>*" type="text">
            </div>
            <div class="col-sm-4">
              <img alt="click to change" src="vcode.php" onclick="this.src='vcode.php?'+Math.random()" height="30px">*
            </div>
          </div>
        <?php } ?>

        <div class="form-group">
          <div class="col-sm-offset-4 col-sm-2">
            <button name="submit" type="submit" class="btn btn-default btn-block"><?php echo $MSG_REGISTER; ?></button>
          </div>
          <div class="col-sm-2">
            <button name="submit" type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET; ?></button>
          </div>
        </div>

      </form>
      <script>
        function check() {
          if ($("#user_id").val().length < 3) {
            swal("<?php echo $MSG_WARNING_USER_ID_SHORT ?>");
            $("#user_id").focus();
            return false;
          }
          if ($("#password").val().length < 6) {
            swal("<?php echo $MSG_WARNING_PASSWORD_SHORT ?>");
            $("#password").focus();
            return false;
          }

          if ($("#password").val() != $("#rptpassword").val()) {
            swal("<?php echo $MSG_WARNING_REPEAT_PASSWORD_DIFF ?>");
            $("#rptpassword").focus();
            return false;
          }
        }
      </script>
    </div>
  </div>
  <?php include("template/js.php"); ?>
  <script>
    $("input").attr("class", "form-control");
  </script>
</body>

</html>