<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

</head>

<body>

  <div class="container">
    <?php include("template/nav.php"); ?>
    <div class="jumbotron">

      <pre id='code' class="alert alert-error"><?php echo $view_reinfo ?></pre>
      <div id='errexp'>
      </div>

    </div>

  </div>
  <?php include("template/js.php"); ?>
  <script>
    var pats = new Array();
    var exps = new Array();
    pats[0] = /A Not allowed system call.* /;
    exps[0] = "<?php echo $MSG_A_NOT_ALLOWED_SYSTEM_CALL ?>";
    pats[1] = /Segmentation fault/;
    exps[1] = "<?php echo $MSG_SEGMETATION_FAULT ?>";
    pats[2] = /Floating point exception/;
    exps[2] = "<?php echo $MSG_FLOATING_POINT_EXCEPTION ?>";
    pats[3] = /buffer overflow detected/;
    exps[3] = "<?php echo $MSG_BUFFER_OVERFLOW_DETECTED ?>";
    pats[4] = /Killed/;
    exps[4] = "<?php echo $MSG_PROCESS_KILLED ?>";
    pats[5] = /Alarm clock/;
    exps[5] = "<?php echo $MSG_ALARM_CLOCK ?>";
    pats[6] = /CALLID:20/;
    exps[6] = "<?php echo $MSG_CALLID_20 ?>";
    pats[7] = /ArrayIndexOutOfBoundsException/;
    exps[7] = "<?php echo $MSG_ARRAY_INDEX_OUT_OF_BOUNDS_EXCEPTION ?>";
    pats[8] = /StringIndexOutOfBoundsException/;
    exps[8] = "<?php echo $MSG_STRING_INDEX_OUT_OF_BOUNDS_EXCEPTION ?>";

    explain();

    <?php if (isset($OJ_DOWNLOAD) && $OJ_DOWNLOAD) echo  "showDownload();" ?>
  </script>
</body>

</html>