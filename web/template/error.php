<?php
if (isset($view_errors_js) || isset($view_swal) || isset($view_swal_params)) { ?>
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
    <?php include(dirname(__FILE__) . "/css.php"); ?>
  </head>

  <body>
  </body>

  <script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
  <script src="<?php echo $OJ_CDN_URL .  "include/" ?>sweetalert.min.js"></script>
  <script>
    <?php if (isset($view_errors_js)) {
      echo $view_errors_js;
    }
    if (isset($error_location)) {
      $error_location = "window.location.href='$error_location'";
    } else {
      $error_location = "history.go(-1)";
    }
    if (isset($view_swal_params))
      echo "swal($view_swal_params).then((onConfirm)=>{" . $error_location . ";});";
    if (isset($view_swal)) {
      echo "swal('$view_swal').then((onConfirm)=>{" . $error_location . ";});";
    }
    ?>
  </script>

  </html>

<?php } else { ?>

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
    <?php include(dirname(__FILE__) . "/css.php"); ?>
  </head>

  <body>

    <div class="container">
      <?php include("template/nav.php"); ?>
      <div class="jumbotron">
        <div class='main-container'>
          <?php if (isset($view_error_title)) echo "<h2>" . $view_error_title . "</h2><br>" ?>
          <?php if (isset($view_errors)) echo $view_errors ?>
        </div>
      </div>
    </div>
    <?php include(dirname(__FILE__) . "/js.php"); ?>
  </body>

  </html>
<?php } ?>