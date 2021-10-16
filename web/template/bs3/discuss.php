<?php
$view_discuss = ob_get_contents();
ob_end_clean();
require_once("../lang/$OJ_LANG.php");
?>
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
  <?php include("../template/$OJ_TEMPLATE/css.php"); ?>



</head>

<body>

  <div class="container">
    <?php include("../template/$OJ_TEMPLATE/nav.php"); ?>
    <div class="jumbotron">
      <?php echo $view_discuss ?>
    </div>

  </div>
  <?php include("../template/$OJ_TEMPLATE/js.php"); ?>
</body>

</html>