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

  <style>
    #source {
      width: 80%;
      height: 600px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <form id=frmSolution action="clipboard.php" method="post">
          <h3 style='text-align:center;'>剪切板</h3>
          <?php if (isset($flag)) { ?>
            <br><span class='alert alert-success'>成功</span><br><br>
          <?php } ?>
          <div id='container_status'>
            <pre style="width:80%;height:600;font-size:13pt;margin:8px;" cols=180 rows=20 id="source"><?php if (isset($content)) echo htmlentities($content, ENT_QUOTES, "UTF-8") ?></pre>
            <textarea name='content' style='display:none;'><?php if (isset($content)) echo htmlentities($content, ENT_QUOTES, "UTF-8") ?></textarea>
          </div>
          <input id="Submit" class="btn btn-info btn-sm" type=submit value="<?php echo $MSG_SUBMIT; ?>" style="margin:6px;">
        </form>
        <br>
      </center>
    </div>
  </div>
  <?php include("template/$OJ_TEMPLATE/js.php"); ?>

  <script src="<?php echo $OJ_CDN_URL . $path_fix . "ace/" ?>ace.js"></script>
  <script src="<?php echo $OJ_CDN_URL . $path_fix . "ace/" ?>ext-language_tools.js"></script>
  <script>
    ace.require("ace/ext/language_tools");
    var editor = ace.edit("source");
    editor.setTheme("ace/theme/chrome");
    switchLang(<?php echo isset($lastlang) ? $lastlang : 6;  ?>);
    editor.setOptions({
      enableBasicAutocompletion: true,
      enableSnippets: true,
      enableLiveAutocompletion: true
    });
    editor.session.on('change', function(delta) {
      $("textarea[name=content]").val(editor.getValue())
    });
  </script>

</body>

</html>