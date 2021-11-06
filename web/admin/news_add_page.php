<?php
require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

echo "<hr>";
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
      <label class="col control-label"><?php echo $MSG_TITLE ?></label>
      <input type=text name=title size=71 value='<?php echo isset($title) ? $title . "-Copy" : "" ?>'>
    </p>
    <p align=left>
      <textarea id="tinymce0" name=content>
        <?php echo isset($content) ? $content : "" ?>
      </textarea>
    </p>

    <div class='col-sm-4 col-sm-offset-4'>
      <p>
        <?php echo "<h4>" . "私有" . "</h4>" ?>
        <?php echo "否 " ?><input type=radio name=private value='0' checked><?php echo "/ 是 " ?><input type=radio name=private value='1'><br><br>
      </p>
      <h4 class='control-label'><?php echo $MSG_GROUP; ?></h4>
      <select name="gid[]" class="selectpicker show-menu-arrow form-control" size=8 multiple>
        <?php
        $cid = intval($_GET['cid']);
        $gid_before = pdo_query("SELECT `users`.`gid` FROM `privilege` JOIN `users` ON `privilege`.`user_id`=`users`.`user_id` WHERE `privilege`.rightstr='c$cid' GROUP BY `users`.`gid`;");
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
</div><br><br>
<?php require_once('../tinymce/tinymce.php'); ?>