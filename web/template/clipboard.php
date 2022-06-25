<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
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

      <form id=frmSolution action="clipboard.php" method="post">
        <center>
          <h3 style='text-align:center;'><?php echo $MSG_CLIPBOARD ?></h3>

          <?php if (isset($flag)) {
            if ($flag) { ?>
              <br><span class='alert alert-success'><?php echo $MSG_SUCCESS ?></span><br><br>
            <?php } else { ?>
              <br><span class='alert alert-success'><?php echo $MSG_ERROR ?></span><br><br>
          <?php }
          } ?>
        </center>
        <div class="editor-border" style="width:80%;height:600;margin:8px auto;" id="source"></div>
        <textarea name='content' style='display:none;'><?php if (isset($content)) echo htmlentities($content, ENT_QUOTES, "UTF-8") ?></textarea>
        <center>
          <input id="Submit" class="btn btn-info btn-sm" type=submit value="<?php echo $MSG_SUBMIT; ?>" style="margin:6px;">
        </center>
      </form>
      <br>
    </div>
  </div>
  <?php include("template/js.php"); ?>

  <script src="<?php echo $OJ_CDN_URL . "monaco/min/vs/" ?>loader.js"></script>
  <script>
    require.config({
      paths: {
        vs: 'monaco/min/vs'
      }
    });

    require(['vs/editor/editor.main'], function() {
      window.editor = monaco.editor.create(document.getElementById('source'), {
        value: `<?php if (isset($content)) echo $content ?>`,
        language: 'plain',
        fontSize: "18px",
        scrollbar: {
          alwaysConsumeMouseWheel: false
        }
      });

      window.editor.getModel().onDidChangeContent((event) => {
        $("textarea[name=content]").val(window.editor.getValue())
      });
    });

    window.onresize = function() {
      window.editor.layout();
    }
  </script>

</body>

</html>