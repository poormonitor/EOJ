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
        <script src="<?php echo $OJ_CDN_URL .  "include/" ?>checksource.min.js"></script>

        <form id=frmSolution action="submit.php" method="post" onsubmit='do_submit()'>
          <?php if (isset($id)) { ?>
            <br />
            <?php echo $MSG_PROBLEM_ID . " : " ?> <span class=blue><?php echo $id ?></span>
            <br />
            <input id=problem_id type='hidden' value='<?php echo $id ?>' name="id">
            <br />
          <?php } else {
            //$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            //if ($pid>25) $pid=25;
          ?>
            <br />
            <?php echo $MSG_PROBLEM_ID . " : " ?> <span class=blue><?php echo chr($pid + ord('A')) ?></span>
            <br />比赛编号 : <span class=blue> <?php echo $cid ?> </span>
            <br />
            <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
            <input id="pid" type='hidden' value='<?php echo $pid ?>' name="pid">
          <?php } ?>

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
          <?php if (isset($code) and !$no_blank) { ?>
            <br /></br>
            <pre id='copy' style='display:none;'><?php echo $copy; ?></pre>
            <pre id='copy-blank' style='display:none;'><?php echo htmlentities($blank, ENT_QUOTES, "UTF-8"); ?></pre>
            <div class='btn-group'>
              <a class='btn btn-sm btn-info' href='javascript:CopyToClipboard($("#copy").text())'><?php echo $MSG_COPY ?></a>
              <a class='btn btn-sm btn-info' href='javascript:CopyToClipboard(get_full_code())'>复制当前</a>
              <a class='btn btn-sm btn-info' href='<?php echo $_SERVER['REQUEST_URI']; ?>&blank=false'>直接填写</a>
            </div>
            <div id='container_status'>
              <pre id='code' class='alert alert-error form-inline' style='text-align:left;'><?php echo $code; ?></pre>
            </div>
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
                    <textarea id="s_out" name="out" class='form-control' style='width:100%;resize:none;' rows=5 disabled="true"><?php echo $view_sample_output ?></textarea>
                  </div>
                  <div class='col-sm-4'>
                    <textarea id="out" name="out" class='form-control' style='width:100%;resize:none;' rows=5 disabled="true"></textarea>
                  </div>
                </div>
                <div class='row' style='margin-top:10px;'>
                  <div class='col-sm-4 col-sm-offset-4'>
                    <input id="TestRun" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_TR ?>" onclick=do_test_run();>
                    &nbsp;&nbsp;&nbsp;
                    <span class="label label-info" id=result><?php echo $MSG_STATUS ?></span>
                  </div>
                </div>
              </div>
            <?php } ?>
            <input id="Submit" class="btn btn-info btn-sm" type=submit value="<?php echo $MSG_SUBMIT; ?>" style="margin:6px;">
        </form>
      <?php } else { ?>
        <?php if (isset($code) && $no_blank) { ?>
          <pre id='copy' class='alert alert-error' style='text-align:left;display:none;'><?php echo $copy; ?></pre>
          <br /></br>
          <div class='btn-group' style='margin-bottom:10px;'>
            <a class='btn btn-sm btn-info' href='javascript:CopyToClipboard($("#copy").text())'><?php echo $MSG_COPY ?></a>
            <?php if (!isset($sid)) { ?>
              <a class='btn btn-sm btn-info' href='<?php echo str_replace("&blank=false", "", $_SERVER['REQUEST_URI']); ?>'>填空</a>
            <?php } ?>
          </div>
        <?php }
            if ($OJ_ACE_EDITOR) { ?>
          <pre style="width:80%;height:600;font-size:13pt;margin:8px;" cols=180 rows=20 id="source"><?php echo htmlentities($view_src, ENT_QUOTES, "UTF-8") ?></pre>
          <input type=hidden id="hide_source" name="source" value="" />
        <?php } else { ?>
          <textarea style="width:80%;height:600;margin:8px;" cols=180 rows=20 id="source" name="source"> <?php echo htmlentities($view_src, ENT_QUOTES, "UTF-8") ?></textarea>
        <?php } ?>
        <?php if ($OJ_VCODE) { ?>
          <?php echo $MSG_VCODE ?>:
          <input name="vcode" size=4 type=text style='margin:5px;' id='vcode_input'> <img id="vcode" alt="click to change" onclick="this.src='vcode.php?small=true&'+Math.random()" autocomplete="off">
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
                <textarea id="s_out" name="out" class='form-control' style='width:100%;resize:none;' rows=5 disabled="true"><?php echo $view_sample_output ?></textarea>
              </div>
              <div class='col-sm-4'>
                <textarea id="out" name="out" class='form-control' style='width:100%;resize:none;' rows=5 disabled="true"></textarea>
              </div>
            </div>
            <div class='row' style='margin-top:10px;'>
              <div class='col-sm-4 col-sm-offset-4'>
                <input id="TestRun" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_TR ?>" onclick=do_test_run();>
                &nbsp;&nbsp;&nbsp;
                <span class="label label-info" id=result><?php echo $MSG_STATUS ?></span>
              </div>
            </div>
          </div>
        <?php } ?>

        <?php if (isset($OJ_ENCODE_SUBMIT) && $OJ_ENCODE_SUBMIT) { ?>
          <input class="btn btn-success" title="WAF gives you reset? Try this." type=button value="Encoded <?php echo $MSG_SUBMIT ?>" onclick="encoded_submit();">
          <input type=hidden id="encoded_submit_mark" name="reverse2" value="reverse">
        <?php } ?>
        <input id="Submit" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_SUBMIT ?>" onclick="do_submit();" style='margin:6px;'>
      <?php } ?>
      </form>
      <br />
      </center>
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
        $('#TestRun').toggle()
        $('#result').toggle()
        $('#test_run').toggle()
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
        if (t != null) t.disabled = false;

        s.value = "<?php echo $MSG_SUBMIT ?>";

        if (t != null) t.value = "<?php echo $MSG_TR ?>";

        if (handler_interval) window.clearInterval(handler_interval);

        if ($("#vcode") != null) $("#vcode").click();
      } else {
        s.value = "<?php echo $MSG_SUBMIT ?>(" + count + ")";

        if (t != null) t.value = "<?php echo $MSG_TR ?>(" + count + ")";

        window.setTimeout("resume();", 1000);
      }
    }

    function encoded_submit() {
      var mark = "<?php echo isset($id) ? 'problem_id' : 'cid'; ?>";
      var problem_id = document.getElementById(mark);

      if (typeof(editor) != "undefined")
        $("#hide_source").val(editor.getValue());
      if (mark == 'problem_id')
        problem_id.value = '<?php if (isset($id)) echo $id ?>';
      else
        problem_id.value = '<?php if (isset($cid)) echo $cid ?>';

      document.getElementById("frmSolution").target = "_self";
      document.getElementById("encoded_submit_mark").name = "encoded_submit";
      var source = $("#source").val();

      if (typeof(editor) != "undefined") {
        source = editor.getValue();
        $("#hide_source").val(encode64(utf16to8(source)));
      } else {
        $("#source").val(encode64(utf16to8(source)));
      }
      //      source.value=source.value.split("").reverse().join("");
      //      swal(source.value);
      document.getElementById("frmSolution").submit();
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
      <?php if ($OJ_LONG_LOGIN == true && isset($_COOKIE[$OJ_NAME . "_user"]) && isset($_COOKIE[$OJ_NAME . "_check"])) echo "let xhr=new XMLHttpRequest();xhr.open('GET','login.php',true);xhr.send();"; ?>

      if (typeof(editor) != "undefined") {
        $("#hide_source").val(editor.getValue());
      }

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

  <script language="Javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/base64.min.js"></script>

  <?php if ($OJ_ACE_EDITOR) { ?>
    <script src="<?php echo $OJ_CDN_URL . "ace/" ?>ace.js"></script>
    <script src="<?php echo $OJ_CDN_URL .  "ace/" ?>ext-language_tools.js"></script>
    <script>
      <?php if (isset($code) && $multiline && (!isset($no_blank) || !$no_blank)) { ?>
        var editors = Array();
        $("pre[id^='source']").each(function(index, elem) {
          ace.require("ace/ext/language_tools");
          editors[index] = ace.edit("source" + index);
          editors[index].setTheme("ace/theme/chrome");
          switchLangs(<?php echo isset($lastlang) ? $lastlang : 6;  ?>);
          editors[index].setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true
          });
          editors[index].session.setTabSize(4);
          editors[index].renderer.setShowGutter(false);
          editors[index].session.on('change', function(delta) {
            $("textarea[name=multiline" + index + "]").val(editors[index].getValue())
          });
        })
      <?php } elseif (!isset($code) || (isset($no_blank) && $no_blank)) { ?>
        ace.require("ace/ext/language_tools");
        var editor = ace.edit("source");
        editor.setTheme("ace/theme/chrome");
        switchLang(<?php echo isset($lastlang) ? $lastlang : 6;  ?>);
        editor.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true
        });
        editor.session.setTabSize(4);
      <?php } ?>
    </script>
  <?php } ?>

  <?php if ($OJ_VCODE) { ?>
    <script>
      $(document).ready(function() {
        $("#vcode").attr("src", "vcode.php?small=true&" + Math.random());
      })
    </script>
  <?php } ?>
</body>

</html>