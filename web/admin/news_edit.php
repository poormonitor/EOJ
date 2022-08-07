<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (isset($_POST['news_id'])) {
  require_once("../include/check_post_key.php");

  $title = $_POST['title'];
  $content = $_POST['content'];

  $content = str_replace("<p>", "", $content);
  $content = str_replace("</p>", "<br>", $content);
  $content = str_replace(",", "&#44;", $content);

  $user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
  $news_id = intval($_POST['news_id']);

  $sql = "UPDATE `news` SET `title`=?,`time`=now(),`content`=?,user_id=? WHERE `news_id`=?";
  //echo $sql;
  pdo_query($sql, $title, $content, $user_id, $news_id);

  $sql = "DELETE FROM privilege_group where rightstr = 'n$news_id'";
  pdo_query($sql);

  if ($_POST['private'] == '1') {
    $privilege = $_POST['gid'];
    $privilege = join(",", $privilege);
    $sql = "UPDATE news set private = 'Y' where news_id = ?";
    pdo_query($sql, $news_id);
    if ($glist) {
      foreach ($glist as $i) {
        $sql = "INSERT INTO `privilege_group`(`gid`,`rightstr`) VALUES (?,?)";
        $result = pdo_query($sql, trim($i), "n$news_id");
      }
    }
  } else {
    $sql = "UPDATE news set `private` = 'N' where news_id = ?";
    pdo_query($sql, $news_id);
    $sql = "DELETE from privilege where rightstr = 'n$news_id'";
    pdo_query($sql);
  }

  header("location:news_list.php");
  exit(0);
} else {
  $news_id = intval($_GET['id']);
  $sql = "SELECT * FROM `news` WHERE `news_id`=?";
  $result = pdo_query($sql, $news_id);
  if (count($result) != 1) {
    echo "No such News!";
    exit(0);
  }

  $row = $result[0];

  $title = htmlentities($row['title'], ENT_QUOTES, "UTF-8");
  $content = $row['content'];
}
require_once("admin-header.php");
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
        <div class='col-md-10 p-0'>
          <div class="container">
            <center>
              <h3><?php echo  $MSG_NEWS . "-" . "Edit" ?></h3>
            </center>
            <form method=POST action=news_edit.php>
              <input type=hidden name='news_id' value=<?php echo $news_id ?>>
              <p align=left>
                <label class="col control-label"><?php echo $MSG_TITLE ?></label>
                <input class='form-control' type=text name=title size=71 value='<?php echo $title ?>'>
              </p>
              <p align=left>
                <textarea id="tinymce0" name=content>
        <?php echo htmlentities($content, ENT_QUOTES, "UTF-8") ?>
      </textarea>
              </p>
              <div class='col-sm-4 col-sm-offset-4'>
                <p>
                  <?php echo "<h4>" . $MSG_Private . "</h4>" ?>
                  <?php echo $MSG_TRUE_FALSE[false] . " " ?><input type=radio name=private value='0' <?php if ($row['private'] == 'N') echo "checked" ?>><?php echo "/ " . $MSG_TRUE_FALSE[true] . " " ?><input type=radio name=private value='1' <?php if ($row['private'] == 'Y') echo "checked" ?>><br><br>
                </p>
                <h4 class='control-label'><?php echo $MSG_GROUP; ?></h4>
                <select name="gid[]" class="selectpicker show-menu-arrow form-control" size=8 multiple>
                  <?php
                  $gid_before = pdo_query("SELECT `gid` FROM `privilege_group` WHERE rightstr='n$news_id'");
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
            </form>
          </div><br><br>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <?php require_once('../tinymce/tinymce.php'); ?>
</body>

</html>