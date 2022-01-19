<?php
require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

echo "<hr>";
echo "<center><h3>" . $MSG_NEWS . "-" . "Edit" . "</h3></center>";


?>

<div class="container">
  <?php
  if (isset($_POST['news_id'])) {
    require_once("../include/check_post_key.php");

    $title = $_POST['title'];
    $content = $_POST['content'];

    $content = str_replace("<p>", "", $content);
    $content = str_replace("</p>", "<br />", $content);
    $content = str_replace(",", "&#44;", $content);

    $user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
    $news_id = intval($_POST['news_id']);

    $sql = "UPDATE `news` SET `title`=?,`time`=now(),`content`=?,user_id=? WHERE `news_id`=?";
    //echo $sql;
    pdo_query($sql, $title, $content, $user_id, $news_id);

    $sql = "DELETE FROM privilege where rightstr = 'n$news_id'";
    pdo_query($sql);

    if ($_POST['private'] == '1') {
      $privilege = $_POST['gid'];
      $privilege = join(",", $privilege);
      $sql = "update news set private = 'Y' where news_id = ?";
      pdo_query($sql, $news_id);
      $sql = "INSERT INTO privilege (`user_id`,`rightstr`,`valuestr`,`defunct`) SELECT user_id, 'n$news_id','true','N' from users where gid in ($privilege)";
      pdo_query($sql);
    } else {
      $sql = "update news set private = 'N' where news_id = ?";
      pdo_query($sql, $news_id);
      $sql = "delete from privilege where rightstr = 'n$news_id'";
      pdo_query($sql);
    }

    header("location:news_list.php");
    exit();
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
  ?>

  <form method=POST action=news_edit.php>
    <input type=hidden name='news_id' value=<?php echo $news_id ?>>
    <p align=left>
      <label class="col control-label"><?php echo $MSG_TITLE ?></label>
      <input type=text name=title size=71 value='<?php echo $title ?>'>
    </p>
    <p align=left>
      <textarea id="tinymce0" name=content>
        <?php echo htmlentities($content, ENT_QUOTES, "UTF-8") ?>
      </textarea>
    </p>
    <div class='col-sm-4 col-sm-offset-4'>
      <p>
        <?php echo "<h4>" . "私有" . "</h4>" ?>
        <?php echo "否 " ?><input type=radio name=private value='0' <?php if ($row['private'] == 'N') echo "checked" ?>><?php echo "/ 是 " ?><input type=radio name=private value='1' <?php if ($row['private'] == 'Y') echo "checked" ?>><br /><br />
      </p>
      <h4 class='control-label'><?php echo $MSG_GROUP; ?></h4>
      <select name="gid[]" class="selectpicker show-menu-arrow form-control" size=8 multiple>
        <?php
        $gid_before = pdo_query("SELECT `users`.`gid` FROM `privilege` JOIN `users` ON `privilege`.`user_id`=`users`.`user_id` WHERE `privilege`.rightstr='n$news_id' GROUP BY `users`.`gid`;");
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
      </select><br />
      <?php require_once("../include/set_post_key.php"); ?>
      <button name="submit" type="submit" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
    </div>
  </form>
</div><br /><br />
<?php require_once('../tinymce/tinymce.php'); ?>