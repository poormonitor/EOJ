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
          <?php
          if (isset($_GET["id"])) {
            $sql = "SELECT * FROM `problem` WHERE `problem_id` = ?";
            $result = pdo_query($sql, intval($_GET["id"]));
            if ($result)
              $row = $result[0];
          }

          echo "<center><h3>" . $MSG_PROBLEM . "-" . $MSG_ADD . "</h3></center>";
          ?>

          <div class="container">
            <form method=POST action=problem_add.php>
              <input type=hidden name=problem_id value="New Problem">
              <p align=left>
                <?php echo "<h3>" . $MSG_TITLE . "</h3>" ?>
                <input class="input form-control" style="width:100%;" type=text name=title value="<?php echo isset($row) ? $row["title"] : "" ?>">
              </p>
              <p align=left>
                <?php echo $MSG_Time_Limit ?><br>
              <div class='form-inline'>
                <input class="input form-control" type=text name=time_limit size=20 value="<?php echo isset($row) ? $row["time_limit"] : "1" ?>"> sec<br><br>
              </div>
              <div class='form-inline'>
                <?php echo $MSG_Memory_Limit ?><br>
                <input class="input form-control" type=text name=memory_limit size=20 value="<?php echo isset($row) ? $row["memory_limit"] : "128" ?>"> MB<br><br>
              </div>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_Description . "</h4>" ?>
                <textarea id="tinymce0" class='form-control' rows=13 name=description cols=80>
      <?php echo isset($row) ? $row["description"] : "" ?>
      </textarea><br>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_Input . "</h4>" ?>
                <textarea id="tinymce1" class='form-control' rows=13 name=input cols=80>
      <?php echo isset($row) ? $row["input"] : "" ?>
      </textarea><br>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_Output . "</h4>" ?>
                <textarea id="tinymce2" class='form-control' rows=13 name=output cols=80>
      <?php echo isset($row) ? $row["output"] : "" ?>
      </textarea><br>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_Sample_Input . "</h4>" ?>
                <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_input><?php echo isset($row) ? $row["sample_input"] : "" ?></textarea>
                <br><br>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_Sample_Output . "</h4>" ?>
                <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_output><?php echo isset($row) ? $row["sample_output"] : "" ?></textarea>
                <br><br>
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
                <textarea id="tinymce3" class='form-control' rows=13 name=hint cols=80><?php echo isset($row) ? $row["hint"] : "" ?></textarea><br>
              </p>
              <p>
                <?php echo "<h4>" . $MSG_SPJ . "</h4>" ?>
              <p><?php echo $MSG_HELP_SPJ ?></p>
              <input type="radio" name="spj" value='0' <?php echo (!isset($row) || !$row["spj"]) ? "checked" : "" ?>> <?php echo $MSG_TRUE_FALSE[false] ?>
              <span> / </span>
              <input type="radio" name="spj" value='1' <?php echo (isset($row) && $row["spj"]) ? "checked" : "" ?>> <?php echo $MSG_TRUE_FALSE[true] ?>
              <br><br>
              </p>
              <p>
                <?php echo "<h4>" . $MSG_BLANK_FILLING . "</h4>" ?>
                <input type=radio id=blank_false name=blank value='0' checked> <?php echo $MSG_TRUE_FALSE[false] ?>
                <span> / </span>
                <input type=radio id=blank_true name=blank value='1'> <?php echo $MSG_TRUE_FALSE[true] ?>
                <br><br>
              </p>
              <p>
              <div id='blank_code'>
                <h4><?php echo $MSG_BLANK_TEMPLATE ?></h4>
                <h5><?php echo $MSG_TEMPLATE_EXPLAIN ?></h5>
                <textarea hidden='hidden' id='multiline' name='blank_code' autocomplete='off'></textarea>
                <div class="editor-border" id=source style='height:300px;width:auto;margin-top:8px;'></div>
              </div>
              </p>
              <p align=left>
                <?php echo "<h4>$MSG_BLOCK_KEYWORD</h4>" ?>
              <h5><?php echo $MSG_HELP_KEYWORD ?></h5>
              <input name=block data-role="tagsinput" class=form-control value="<?php echo isset($row) ? str_replace(" ", ",", $row["block"]) : "" ?>">
              <br>
              </p>
              <p align=left>
                <?php echo "<h4>$MSG_ALLOW_KEYWORD</h4>" ?>
              <h5><?php echo $MSG_HELP_KEYWORD ?></h5>
              <input name=allow data-role="tagsinput" class=form-control value="<?php echo isset($row) ? str_replace(" ", ",", $row["allow"]) : "" ?>">
              <br>
              </p>
              <p align=left>
                <?php echo "<h4>" . $MSG_SOURCE . "</h4>" ?>
                <input name=source data-role="tagsinput" class=form-control value="<?php echo isset($row) ? str_replace(" ", ",", $row["source"]) : "" ?>">
                <br>
              </p>
              <p align=left><?php echo "<h4>" . $MSG_CONTEST . "</h4>" ?>
              <div class='row'>
                <div class='col-sm-4'>
                  <select name=contest_id class='form-control'>
                    <?php
                    $sql = "SELECT `contest_id`,`title` FROM `contest` WHERE `start_time`>NOW() order by `contest_id`";
                    $result = pdo_query($sql);
                    echo "<option value=''>$MSG_EMPTY</option>";
                    if (count($result) == 0) {
                    } else {
                      foreach ($result as $row) {
                        echo "<option value='{$row['contest_id']}'>{$row['contest_id']} {$row['title']}</option>";
                      }
                    } ?>
                  </select>
                </div>
              </div>
              </p>
              <br>

              <div align=center>
                <?php require_once("../include/set_post_key.php"); ?>
                <input class='btn btn-default' type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
              </div>
              </input>
            </form>
          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <script>
    <?php if (!isset($row) || is_null($row['blank'])) { ?>
      $("#blank_code").hide();
      $("#blank_false").click()
    <?php } else { ?>
      $("#blank_true").click()
    <?php } ?>
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
        value: `<?php echo isset($row) ? str_replace("`","\`",$row["blank"]) : "" ?>`,
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