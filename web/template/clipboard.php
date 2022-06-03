<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

  <style>
    #source {
      width: 80%;
      height: 600px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <form id=frmSolution action="clipboard.php" method="post">
          <h3 style='text-align:center;'><?php echo $MSG_CLIPBOARD ?></h3>
          <?php if (isset($flag)) {
            if ($flag) { ?>
              <br><span class='alert alert-success'><?php echo $MSG_SUCCESS ?></span><br><br>
            <?php } else { ?>
              <br><span class='alert alert-success'><?php echo $MSG_ERROR ?></span><br><br>
          <?php }
          } ?>
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
  <?php include("template/js.php"); ?>

  <script src="<?php echo $OJ_CDN_URL . "ace/" ?>ace.js"></script>
  <script src="<?php echo $OJ_CDN_URL . "ace/" ?>ext-language_tools.js"></script>
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