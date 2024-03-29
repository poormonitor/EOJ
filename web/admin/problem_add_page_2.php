<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
)) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

$id = isset($_GET['id']) ? $_GET['id'] : 1000;
$sql = "SELECT * FROM `problem_2` WHERE `id`=?";
$result = pdo_query($sql, intval($id));
if (!$result) {
  $view_swal = $MSG_NOT_EXISTED;
  require("../template/error.php");
  exit(0);
}
$row = $result[0];
?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">
  <?php include("../template/css.php"); ?>
  <title><?php echo $OJ_NAME ?></title>
</head>

<body>
  <div class='container'>
    <?php include("../template/nav.php") ?>
    <div class='jumbotron'>
      <div class='row lg-container'>
        <?php require_once("sidebar.php") ?>
        <div class='col-md-9 col-lg-10 p-0'>
          <?
          echo "<center><h3>" . $MSG_PROBLEM . "-" . $MSG_ADD . "</h3></center>";
          ?>

          <div class="container">
            <form method=POST action=problem_add.php>

              <p align=left>
              <h3>来源 : <?php echo $row['source'] ?></h3>
              <?php echo "<h3>" . $MSG_TITLE . "</h3>" ?>
              <input class="input form-control" style="width:100%;" type=text name=title value='<?php echo htmlentities($row['title'], ENT_QUOTES, "UTF-8") ?>'>
              </p>

              <div class='form-inline'>
                <?php echo $MSG_Time_Limit ?><br>
                <input class="input form-control" type=text name=time_limit size=20 value='<?php echo htmlentities($row['time'], ENT_QUOTES, "UTF-8") ?>'> sec<br><br>
              </div>
              <div class='form-inline'>
                <?php echo $MSG_Memory_Limit ?><br>
                <input class="input form-control" type=text name=memory_limit size=20 value='<?php echo htmlentities($row['memory'], ENT_QUOTES, "UTF-8") ?>'> MB<br><br>
              </div>

              <p align=left>
                <?php echo "<h4>" . $MSG_Description . "</h4>" ?>
                <textarea id="tinymce0" class='form-control' rows=13 name=description cols=80><?php echo htmlentities($row['description'], ENT_QUOTES, "UTF-8") ?></textarea><br>
              </p>

              <p align=left>
                <?php echo "<h4>" . $MSG_Input . "</h4>" ?>
                <textarea id="tinymce1" class='form-control' rows=13 name=input cols=80><?php echo htmlentities($row['input'], ENT_QUOTES, "UTF-8") ?></textarea><br>
              </p>

              <p align=left>
                <?php echo "<h4>" . $MSG_Output . "</h4>" ?>
                <textarea id="tinymce2" class='form-control' class='form-control' rows=13 name=output cols=80><?php echo htmlentities($row['output'], ENT_QUOTES, "UTF-8") ?></textarea><br>
              </p>

              <p align=left>
                <?php echo "<h4>" . $MSG_Sample_Input . "</h4>" ?>
                <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_input><?php echo htmlentities($row['sample_input'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
              </p>

              <p align=left>
                <?php echo "<h4>" . $MSG_Sample_Output . "</h4>" ?>
                <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_output><?php echo htmlentities($row['sample_output'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
              </p>

              <p align=left>
                <?php echo "<h4>" . $MSG_HINT . "</h4>" ?>
                <textarea id="tinymce3" class='form-control' rows=13 name=hint cols=80><?php echo htmlentities($row['hint'], ENT_QUOTES, "UTF-8") ?></textarea><br>
              </p>

              <p>
                <?php echo "<h4>" . $MSG_SPJ . "</h4>" ?>
                <span><?php echo $MSG_HELP_SPJ ?></span><br>
                <input type="radio" name="spj" value='0' <?php echo (!isset($row) || !$row["spj"]) ? "checked" : "" ?>> <?php echo $MSG_TRUE_FALSE[false] ?>
                <span> / </span>
                <input type="radio" name="spj" value='1' <?php echo (isset($row) && $row["spj"]) ? "checked" : "" ?>> <?php echo $MSG_TRUE_FALSE[true] ?>
                <br><br>
              </p>
              <p>
                <?php echo "<h4>" . $MSG_BLANK_FILLING . "</h4>" ?>
                <?php echo $MSG_TRUE_FALSE[true] . " " ?><input type=radio id=blank_false name=blank value='0' checked><?php echo "/ " . $MSG_TRUE_FALSE[true] . " " ?><input type=radio id=blank_true name=blank value='1'><br><br>
              </p>
              <p>
              <div id='blank_code'>
                <h4><?php echo $MSG_BLANK_TEMPLATE ?></h4>
                <h5><?php echo $MSG_TEMPLATE_EXPLAIN ?></h5>
                <textarea hidden='hidden' id='multiline' name='blank_code' autocomplete='off'></textarea>
                <div id=source class="editor-border" style='height:300px;width:auto;margin-top:8px;'></div>
              </div>
              </p>
              <p align=left>
                <?php echo "<h4>$MSG_BLOCK_KEYWORD</h4>" ?>
              <h5><?php echo $MSG_HELP_KEYWORD ?></h5>
              <input name=block class="form-control" data-role="tagsinput" value=''></input><br><br>
              </p>
              <p align=left>
                <?php echo "<h4>$MSG_ALLOW_KEYWORD</h4>" ?>
              <h5><?php echo $MSG_HELP_KEYWORD ?></h5>
              <input name=allow class="form-control" data-role="tagsinput" value=''></input><br><br>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_SOURCE . "</h4>" ?>
                <input name=source class="form-control" data-role="tagsinput" value='<?php echo htmlentities(join(",", explode(" ", $row['tag'])), ENT_QUOTES, "UTF-8") ?>'></input><br><br>
              </p>

              <div align=center>
                <?php require_once("../include/set_post_key.php"); ?>
                <input class='btn-sm btn btn-default' type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
              </div>
            </form>
          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <script>
    $("#blank_code").hide();
    $("#blank_false").click()
    $("#blank_false").click(function() {
      $("#blank_code").hide();
    })
    $("#blank_true").click(function() {
      $("#blank_code").show();
      window.editor.layout();
    })
  </script>
  <script src='<?php echo $OJ_CDN_URL .  "include/" ?>bootstrap-tagsinput.min.js'></script>
  <script src="<?php echo $OJ_CDN_URL . "monaco/min/vs/" ?>loader.js"></script>
  <script>
    require.config({
      paths: {
        vs: ['../monaco/min/vs']
      }
    });

    require(['vs/editor/editor.main'], function() {
      window.editor = monaco.editor.create(document.getElementById('source'), {
        value: ``,
        language: 'plain',
        fontSize: "18px",
      });

      window.editor.getModel().onDidChangeContent((event) => {
        $("#multiline").val(window.editor.getValue())
      });
    });

    window.onresize = function() {
      window.editor.layout();
    }
  </script>
  <?php require_once('../tinymce/tinymce.php'); ?>
</body>

</html>