<?php
require_once("admin-header.php"); 

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
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
          echo "<center><h3>" . $MSG_NEWS . "-" . $MSG_ADD . "</h3></center>";
          ?>

          <?php
          if (isset($_GET['cid'])) {
            $cid = intval($_GET['cid']);
            $sql = "SELECT * FROM news WHERE `news_id`=?";
            $result = pdo_query($sql, $cid);
            $row = $result[0];
            $title = $row['title'];
            $content = $row['content'];
            $defunct = $row['defunct'];
          }
          ?>

          <div class="container">
            <form method=POST action=news_add.php>
              <p align=left>
              <div class='form-inline'>
                <label class="col control-label"><?php echo $MSG_TITLE ?></label>&nbsp;&nbsp;
                <input class='form-control' type=text name=title size=71 value='<?php echo isset($title) ? $title . "-Copy" : "" ?>'>
              </div>
              </p>
              <p align=left>
                <textarea id="tinymce0" class='form-control' rows="13" name=content>
        <?php echo isset($content) ? $content : "" ?>
      </textarea>
              </p>

              <div class='col-sm-4 col-sm-offset-4'>
                <p>
                  <?php echo "<h4>" . $MSG_Private . "</h4>" ?>
                  <?php echo $MSG_TRUE_FALSE[false] . " " ?><input type=radio name=private value='0' checked><?php echo "/ " . $MSG_TRUE_FALSE[true] ?><input type=radio name=private value='1'><br><br>
                </p>
                <h4 class='control-label'><?php echo $MSG_GROUP; ?></h4>
                <select name="gid[]" class="selectpicker show-menu-arrow form-control" size=8 multiple>
                  <?php
                  $gid_before = pdo_query("SELECT `gid` FROM `privilege_group` WHERE rightstr='n$cid'");
                  $sql_all = "SELECT * FROM `group`;";
                  $result = pdo_query($sql_all);
                  $all_group = $result;
                  $gid = array();
                  foreach ($gid_before as $i) {
                    array_push($gid, $i[0]);
                  }
                  foreach ($all_group as $i) {
                    $show_id = $i["gid"];
                    $show_name = $i["name"];
                    if (in_array($show_id, $gid)) {
                      echo "<option value=$show_id selected>$show_name</option>";
                    } else {
                      echo "<option value=$show_id>$show_name</option>";
                    }
                  }
                  ?>
                </select><br>
                <?php require_once("../include/set_post_key.php"); ?>
                <button name="submit" type="submit" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
              </div>
              <?php require_once("../include/set_post_key.php"); ?>
            </form>
          </div>
          <br><br>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <?php require_once('../tinymce/tinymce.php'); ?>
</body>

</html>