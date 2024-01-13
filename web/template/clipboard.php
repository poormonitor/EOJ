<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $MSG_CLIPBOARD . " - " . $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

  <style>
    #source {
      width: 100%;
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

          <?php if (isset($flag) && !$flag) { ?>
            <br><span class='alert alert-success'><?php echo $MSG_ERROR ?></span><br>
          <?php } ?>
        </center>
        <br>
        <center class="mb-4">
          <span class='form-inline' id="language_span"><?php echo $MSG_LANG ?>&nbsp;:
            <select class='form-control' id="language" name="language" onChange="reloadtemplate($(this).val());">
              <?php
              $lang_count = count($language_ext);

              if (isset($_GET['langmask']))
                $langmask = $_GET['langmask'];
              else
                $langmask = $OJ_LANGMASK;

              $lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);

              if (isset($_COOKIE['lastlang'])) $lastlang = $_COOKIE['lastlang'];
              else $lastlang = 6;

              for ($i = 0; $i < $lang_count; $i++) {
                if ($lang & (1 << $i))
                  echo "<option value=$i " . ($lastlang == $i ? "selected" : "") . ">" . $language_name[$i] . "</option>";
              }
              ?>
            </select>
          </span>
        </center>
        <div class="editor mx-auto">
          <div class="editor-border" id="source"></div>
        </div>
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
    function reloadtemplate(lang) {
      console.log("lang=" + lang);
      document.cookie = "lastlang=" + lang;
      //swal(document.cookie);
      var url = window.location.href;
      var i = url.indexOf("sid=");
      if (i != -1) url = url.substring(0, i - 1);

      <?php if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE) { ?>
        if (confirm("<?php echo  $MSG_LOAD_TEMPLATE_CONFIRM ?>"))
          document.location.href = url;
      <?php } ?>
      switchLang(lang);
    }

    require.config({
      paths: {
        vs: ['monaco/min/vs']
      }
    });

    require(['vs/editor/editor.main'], function() {
      window.editor = monaco.editor.create(document.getElementById('source'), {
        value: `<?php if (isset($content)) echo str_replace('`', '\`', $content) ?>`,
        language: 'plain',
        fontSize: "18px",
      });

      window.editor.getModel().onDidChangeContent((event) => {
        $("textarea[name=content]").val(window.editor.getValue())
      });

      switchLang(<?php echo isset($lastlang) ? $lastlang : 6;  ?>);
    });

    window.onresize = function() {
      window.editor.layout();
    }
  </script>

</body>

</html>