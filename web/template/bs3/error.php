<?php
if (isset($view_errors_js)) { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <title>错误</title>
  </head>

  <body>
    <style>
      @media (prefers-color-scheme: dark) {
        body {
          height: auto;
          background: #242424;
        }
    </style>
  </body>

  <script src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/template/bs3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
  <script>
    <?php echo $view_errors_js; ?>
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


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div style='margin:30px;'>
          <?php echo $view_errors ?>
          <p></p>
        </div>
      </div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
  </body>

  </html>
<?php } ?>