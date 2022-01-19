<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/$OJ_TEMPLATE/css.php"); ?>



</head>

<body>

  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <form id=frmSolution action="printer.php" method="post">
          <textarea style="width:80%" cols=180 rows=20 id="source" name="content">
</textarea><br />
          <input type="submit" value="<?php echo $MSG_PRINTER ?>">
          <?php require_once(dirname(__FILE__) . "/../../include/set_post_key.php") ?>
        </form>
      </center>
    </div>

  </div>
  <?php include("template/$OJ_TEMPLATE/js.php"); ?>

</body>

</html>