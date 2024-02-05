<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo  $MSG_SUBMIT . " - " . $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

  <style>
    #source {
      width: 100%;
      height: 600px;
      margin: 20px auto;
    }
  </style>
</head>

<body>
  <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <main class="modal__content" id="modal-1-content">
          <?php include("template/problem_simple.php") ?>
        </main>
      </div>
    </div>
  </div>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="main-container jumbotron">
      <form id=frmSolution action="submit.php" method="post" onsubmit='do_submit()'>
        <center>
          <?php if (isset($id)) { ?>
            <div class="fs-2">
              <div>
                <?php echo $MSG_PROBLEM_ID ?> :
                <a href="javascript:MicroModal.show('modal-1');" class=blue>
                  <?php echo $id ?>
                </a>
              </div>
            </div>
            <br>

            <input id=problem_id type='hidden' value='<?php echo $id ?>' name="id">
          <?php } else {
            //$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            //if ($pid>25) $pid=25;
          ?>
            <div class="fs-2">
              <div>
                <?php echo $MSG_PROBLEM_ID ?> :
                <a href="javascript:MicroModal.show('modal-1');" class=blue>
                  <?php echo chr($pid + ord('A')) ?>
                </a>
              </div>
              <div>
                <?php echo $MSG_CONTEST_ID ?> : <a href="contest.php?cid=<?php echo $cid ?>" class=blue> <?php echo $cid ?> </a>
              </div>
            </div>
            <br>

            <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
            <input id="pid" type='hidden' value='<?php echo $pid ?>' name="pid">
          <?php } ?>

          <div class='form-inline' id="language_span">
            <label><?php echo $MSG_LANG ?> :</label>
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
          </div>
          <?php if (isset($code) && $no_blank) { ?>
            <pre id='copy' class='alert alert-error' style='text-align:left;display:none;'><?php echo $copy; ?></pre>
            <br>
            <div class='btn-group mt-4'>
              <a class='btn btn-sm btn-info' href='javascript:CopyToClipboard($("#copy").text())'><?php echo $MSG_COPY ?></a>
              <a class='btn btn-sm btn-info' href='<?php echo str_replace("&blank=false", "", $_SERVER['REQUEST_URI']); ?>'><?php echo $MSG_BLANK_FILLING ?></a>
            </div>
          <?php } ?>
        </center>
        <div class="editor mx-auto">
          <div id="source" class="editor-border"></div>
        </div>

        <textarea hidden="hidden" id="hide_source" name="source"><?php echo $view_src ?></textarea>
        <center>
          <?php if ($OJ_VCODE) { ?>
            <?php echo $MSG_VCODE ?>:
            <input name="vcode" size=4 type=text style='margin:5px;' id='vcode_input'>
            <img id="vcode" alt="click to change" onclick="this.src='vcode.php?small=true&'+Math.random()" autocomplete="off">
          <?php } ?>
          <?php if (isset($OJ_TEST_RUN) && $OJ_TEST_RUN) { ?>
            <div class="checkbox">
              <label>
                <input type="checkbox" id="test_run_checkbox">
                <span><?php echo $MSG_TEST_RUN ?></span>
              </label>
            </div>
            <div id='test_run' class='form-group' style='margin-bottom:10px;width:80%;'>
              <div class='row'>
                <div class='col-sm-4'>
                  <textarea id="input_text" class='form-control' style='width:100%;resize:none;' rows=5 name="input_text"><?php echo $view_sample_input ?></textarea>
                </div>
                <div class='col-sm-4'>
                  <textarea id="s_out" name="out" class='form-control cursor-auto' style='width:100%;resize:none;' rows=5 disabled="true"><?php echo $view_sample_output ?></textarea>
                </div>
                <div class='col-sm-4'>
                  <textarea id="out" name="out" class='form-control cursor-auto' style='width:100%;resize:none;' rows=5 disabled="true"></textarea>
                </div>
              </div>
              <div class='row' style='margin-top:10px;'>
                <div class='col-sm-4 col-sm-offset-4'>
                  <input id="TestRun" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_TR ?>" onclick=do_test_run();>
                  <span class="label label-info m-3" id=result><?php echo $MSG_STATUS ?></span>
                </div>
              </div>
            </div>
          <?php } ?>
          <input id="Submit" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_SUBMIT ?>" onclick="do_submit();" style='margin:6px;'>
        </center>
      </form>
      <br>
    </div>
  </div>
  <?php include("template/js.php"); ?>

  <script>
    <?php if (isset($OJ_TEST_RUN) && $OJ_TEST_RUN) { ?>
      $('#TestRun').hide()
      $('#result').hide()
      $('#test_run').hide()
      $('#test_run_checkbox').prop("checked", false)
      $('#vcode_input').val("")

      $("#test_run_checkbox").click(function() {
        $('#TestRun').fadeToggle()
        $('#result').fadeToggle()
        $('#test_run').fadeToggle()
        $('#test_run_btn').toggleClass("height");
        $('#test_run_btn').toggleClass("margin");
      });
    <?php } ?>

    var mark = "<?php echo isset($id) ? 'problem_id' : 'cid'; ?>";

    function resume() {
      count--;
      var s = $("#Submit")[0];
      var t = $("#TestRun")[0];
      if (count < 0) {
        s.disabled = false;
        if (t != null)
          t.disabled = false;
        s.value = "<?php echo $MSG_SUBMIT ?>";
        if (t != null)
          t.value = "<?php echo $MSG_TR ?>";
        if (handler_interval)
          window.clearInterval(handler_interval);
        if ($("#vcode") != null) $("#vcode").click();
      } else {
        s.value = "<?php echo $MSG_SUBMIT ?>(" + count + ")";
        if (t != null)
          t.value = "<?php echo $MSG_TR ?>(" + count + ")";
        window.setTimeout("resume();", 1000);
      }
    }

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

    function do_submit() {
      <?php
      if ($OJ_LONG_LOGIN == true && isset($_COOKIE[$OJ_IDENTITY . "_user"]) && isset($_COOKIE[$OJ_IDENTITY . "_check"]))
        echo "let xhr=new XMLHttpRequest();xhr.open('GET','login.php',true);xhr.send();";
      ?>

      $("#hide_source").val(encode64(utf16to8(window.editor.getValue())));

      var mark = "<?php echo isset($id) ? 'problem_id' : 'cid'; ?>";
      var problem_id = document.getElementById(mark);

      if (mark == 'problem_id')
        problem_id.value = '<?php if (isset($id)) echo $id ?>';
      else
        problem_id.value = '<?php if (isset($cid)) echo $cid ?>';

      document.getElementById("frmSolution").target = "_self";
      document.getElementById("frmSolution").submit();
    }

    var sid = 0;
    var i = 0;
    var judge_result = [<?php
                        foreach ($judge_result as $result) {
                          echo "'$result',";
                        } ?> ''];
  </script>

  <script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/base64.min.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL ?>template/micromodal.min.js"></script>
  <script>
    MicroModal.init();
  </script>
  <script src="<?php echo $OJ_CDN_URL . "monaco/min/vs/" ?>loader.js"></script>
  <script>
    require.config({
      paths: {
        vs: ['monaco/min/vs']
      }
    });

    require(['vs/editor/editor.main'], function() {
      window.editor = monaco.editor.create(document.getElementById('source'), {
        value: `<?php echo str_replace("`", "\`", $view_src) ?>`,
        language: 'plain',
        fontSize: window.innerWidth < 768 ? "14px" : "18px",
      });

      window.editor.getModel().onDidChangeContent((event) => {
        $("textarea#hide_source").val(window.editor.getValue())
      });

      switchLang(<?php echo isset($lastlang) ? $lastlang : 6;  ?>);
    });

    window.onresize = function() {
      window.editor.layout();
      window.editor.updateOptions({
        fontSize: window.innerWidth < 768 ? "14px" : "18px",
      });
    }
  </script>

  <?php if ($OJ_VCODE) { ?>
    <script>
      $(document).ready(function() {
        $("#vcode").attr("src", "vcode.php?small=true&" + Math.random());
      })
    </script>
  <?php } ?>
  <?php if (isset($background_url)) { ?>
    <style>
      @media (prefers-color-scheme: light) {
        body {
          opacity: 0.95;
          background: url("<?php echo $background_url ?>") no-repeat 50% 50% / cover;
          background-attachment: fixed;
        }

        .footer-container {
          background: rgba(255, 255, 255, 0.9);
          margin: 18px 2rem 18px 2rem;
          padding: 1em;
          border-radius: 20px;
        }

        .container .jumbotron {
          background-color: rgba(255, 255, 255, 0.9);
        }
      }
    </style>
  <?php } ?>
</body>

</html>