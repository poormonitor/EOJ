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
  <?php include("template/css.php"); ?>



  <link href='<?php echo $OJ_CDN_URL ?>highlight/styles/shCore.css' rel='stylesheet' type='text/css' />
  <link href='<?php echo $OJ_CDN_URL ?>highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css' />
</head>

<body>

  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <?php
      if ($ok == true) {
        $brush = strtolower($language_name[$slanguage]);
        if ($brush == 'pascal') $brush = 'delphi';
        if ($brush == 'obj-c') $brush = 'c';
        if ($brush == 'freebasic') $brush = 'vb';
        if ($brush == 'fortran') $brush = 'vb';
        if ($brush == 'swift') $brush = 'csharp';
        if ($brush == 'python3') $brush = 'python';
        echo "<pre id='code' class='alert alert-error'><pre class=\"brush:" . $brush . ";\">";
        ob_start();
        echo "\n'''\n";
        echo "=== Submission Info ===\n";
        echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n\tName: $snick\n";
        echo "\tLanguage: " . $language_name[$slanguage] . "\n\tResult: " . $judge_result[$sresult] . "\n";
        if ($sresult == 4) {
          echo "\tTime:" . $stime . " ms\n";
          echo "\tMemory:" . $smemory . " kb\n";
        }
        echo "'''";
        $auth = ob_get_contents();
        ob_end_clean();
        echo htmlentities(str_replace("\n\r", "\n", $view_source), ENT_QUOTES, "utf-8") . "\n" . $auth . "</pre></pre>";
      } else {
        echo $MSG_WARNING_ACCESS_DENIED;
      }
      ?>
      <pre id='code' class="alert alert-error"><?php echo $view_reinfo ?></pre>
    </div>

  </div>
  <?php include("template/js.php"); ?>
  <script src='<?php echo $OJ_CDN_URL ?>highlight/scripts/shCore.js' type='text/javascript'></script>
  <script src='<?php echo $OJ_CDN_URL ?>highlight/scripts/shBrushPython.js' type='text/javascript'></script>

  <script>
    SyntaxHighlighter.config.bloggerMode = false;
    SyntaxHighlighter.config.clipboardSwf = '<?php echo $OJ_CDN_URL ?>highlight/scripts/clipboard.swf';
    SyntaxHighlighter.all();
  </script>
</body>

</html>