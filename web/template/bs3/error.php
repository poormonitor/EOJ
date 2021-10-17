<?php
if (isset($view_errors_js) or isset($view_swal)) { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME ?></title>
  </head>

  <body>
    <style>
      @media (prefers-color-scheme: dark) {
        body {
          height: auto;
          background: #242424;
        }
      }
    </style>
  </body>

  <script src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/template/bs3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
  <script>
    <?php if (isset($view_errors_js)) {
      echo $view_errors_js;
    }
    if (isset($error_location)) {
      $error_location = "window.location.href='$error_location'";
    } else {
      $error_location = "history.go(-1)";
    }
    if (isset($view_swal)) {
      echo "swal('$view_swal').then((onConfirm)=>{" . $error_location . ";});";
    }
    ?>
  </script>

  </html>

<?php } else { ?>

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
      <div class="jumbotron">
        <div class='main-container'>
          <?php if (isset($view_error_title)) echo "<h2>" . $view_error_title . "</h2>" ?>
          </br>
          <?php if (isset($view_errors)) echo $view_errors ?>
        </div>
      </div>
    </div>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
  </body>

  </html>
<?php } ?>