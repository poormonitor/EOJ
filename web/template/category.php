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
    <div class="jumbotron">
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h4>
            <?php echo $MSG_SOURCE ?>
          </h4>
        </div>
        <div class='panel-body content'>
          <?php echo $view_category ?>
        </div>
      </div>
    </div>

  </div>
  <?php include("template/js.php"); ?>
</body>

</html>