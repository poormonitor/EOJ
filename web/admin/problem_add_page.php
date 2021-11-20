<!DOCTYPE html>

<head>
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-cache">
  <meta http-equiv="Content-Language" content="zh-cn">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Problem Add</title>
</head>
<hr>

<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
echo "<center><h3>" . $MSG_PROBLEM . "-" . $MSG_ADD . "</h3></center>";

?>

<body leftmargin="30">
  <div class="container">
    <form method=POST action=problem_add.php>
      <input type=hidden name=problem_id value="New Problem">
      <p align=left>
        <?php echo "<h3>" . $MSG_TITLE . "</h3>" ?>
        <input class="input input-xxlarge" style="width:100%;" type=text name=title><br><br>
      </p>
      <p align=left>
        <?php echo $MSG_Time_Limit ?><br>
        <input class="input input-mini" type=text name=time_limit size=20 value=1> sec<br><br>
        <?php echo $MSG_Memory_Limit ?><br>
        <input class="input input-mini" type=text name=memory_limit size=20 value=128> MB<br><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Description . "</h4>" ?>
        <textarea id="tinymce0" rows=13 name=description cols=80></textarea><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Input . "</h4>" ?>
        <textarea id="tinymce1" rows=13 name=input cols=80></textarea><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Output . "</h4>" ?>
        <textarea id="tinymce2" rows=13 name=output cols=80></textarea><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Sample_Input . "</h4>" ?>
        <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_input></textarea><br><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Sample_Output . "</h4>" ?>
        <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_output></textarea><br><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Test_Input . "</h4>" ?>
        <?php echo "(" . $MSG_HELP_MORE_TESTDATA_LATER . ")" ?><br>
        <textarea class="input input-large form-control" style="width:100%;" rows=13 name=test_input></textarea><br><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_Test_Output . "</h4>" ?>
        <?php echo "(" . $MSG_HELP_MORE_TESTDATA_LATER . ")" ?><br>
        <textarea class="input input-large form-control" style="width:100%;" rows=13 name=test_output></textarea><br><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_HINT . "</h4>" ?>
        <textarea id="tinymce3" rows=13 name=hint cols=80></textarea><br>
      </p>
      <p>
        <?php echo "<h4>" . $MSG_SPJ . "</h4>" ?>
        <span><?php echo $MSG_HELP_SPJ ?></span><br>
        <?php echo "否 " ?><input type=radio name=spj value='0' checked>
        <?php echo "/ 结束后特判 " ?><input type=radio name=spj value='1'>
        <?php echo "/ 运行时特判 " ?><input type=radio name=spj value='2'>
        <br><br>
      </p>
      <p>
        <?php echo "<h4>" . "代码填空" . "</h4>" ?>
        <?php echo "否 " ?><input type=radio id=blank_false name=blank value='0' checked><?php echo "/ 是 " ?><input type=radio id=blank_true name=blank value='1'><br><br>
      </p>
      <p>
      <div id='blank_code'>
        <h4>待填空代码</h4>
        <h5>单行填空请用%*%表示，多行填空用*%*表示，一个问题仅支持一个多行填空</h5>
        <textarea hidden='hidden' id='multiline' name='blank_code' autocomplete='off'></textarea>
        <pre id=source style='height:300px;width:auto;font-size:13pt;margin-top:8px;'></pre>
      </div>
      </p>
      <p align=left>
        <?php echo "<h4>禁用关键词</h4>" ?>
        <input name=block data-role="tagsinput" class=form-control></input><br><br>
      </p>
      <p align=left>
        <?php echo "<h4>必须关键词</h4>" ?>
        <input name=allow data-role="tagsinput" class=form-control></input><br><br>
      </p>
      <p align=left>
        <?php echo "<h4>" . $MSG_SOURCE . "</h4>" ?>
        <input name=source data-role="tagsinput" class=form-control></input><br><br>
      </p>
      <p align=left><?php echo "<h4>" . $MSG_CONTEST . "</h4>" ?>
        <select name=contest_id>
          <?php
          $sql = "SELECT `contest_id`,`title` FROM `contest` WHERE `start_time`>NOW() order by `contest_id`";
          $result = pdo_query($sql);
          echo "<option value=''>无</option>";
          if (count($result) == 0) {
          } else {
            foreach ($result as $row) {
              echo "<option value='{$row['contest_id']}'>{$row['contest_id']} {$row['title']}</option>";
            }
          } ?>
        </select>
      </p>

      <div align=center>
        <?php require_once("../include/set_post_key.php"); ?>
        <input type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
      </div>
      </input>
    </form>
  </div>
  <script>
    $("#blank_code").hide();
    $("#blank_false").click(function() {
      $("#blank_code").hide();
    })
    $("#blank_true").click(function() {
      $("#blank_code").show();
    })
  </script>
  <script src='<?php echo $OJ_CDN_URL .  "include/" ?>bootstrap-tagsinput.min.js'></script>
  <script src="<?php echo $OJ_CDN_URL . "ace/" ?>ace.min.js"></script>
  <script src="<?php echo $OJ_CDN_URL . "ace/" ?>ext-language_tools.min.js"></script>
  <script>
    ace.require("ace/ext/language_tools");
    ace.config.set('basePath', '<?php echo $OJ_CDN_URL . "ace/" ?>');
    var editor = ace.edit("source");
    editor.setTheme("ace/theme/chrome");
    editor.setOptions({
      enableBasicAutocompletion: true,
      enableSnippets: true,
      enableLiveAutocompletion: true
    });
    editor.session.setTabSize(4);
    $("#multiline").val(editor.getValue())
    editor.session.on('change', function(delta) {
      $("#multiline").val(editor.getValue())
    });
  </script>
  <?php require_once('../tinymce/tinymce.php'); ?>
</body>

</html>