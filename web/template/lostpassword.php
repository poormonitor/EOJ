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
      <center>
        <h3>密码重置</h3><br>
        <form action=lostpassword.php method=post class='form-horizontal'>
          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $MSG_USER_ID ?></label>
            <div class="col-sm-4">
              <input name="user_id" type="text" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $MSG_EMAIL ?></label>
            <div class="col-sm-4">
              <input name="email" type="text" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $MSG_VCODE ?></label>
            <div class="col-sm-4">
              <div class="col-xs-8" style='padding:0px;'><input name="vcode" class="form-control" type="text"></div>
              <div class="col-xs-4"><img id="vcode-img" alt="click to change" style='float:right;' onclick="this.src='vcode.php?'+Math.random()"></div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-4">
              <input name="submit_btn" type="submit" class="btn btn-default btn-block" value="<?php echo $MSG_SUBMIT;?>">
            </div>
          </div>
        </form>
        <center>
          <br>
    </div>

  </div>
  <?php include("template/js.php"); ?>
</body>

<script>
  $(document).ready(function() {
    $("#vcode-img").attr("src", "vcode.php?" + Math.random());
  })
</script>

</html>