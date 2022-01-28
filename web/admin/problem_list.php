<?php
require("admin-header.php");
require_once("../include/set_get_key.php");
?>

<center>
  <h3><?php echo $MSG_PROBLEM . "-" . $MSG_LIST ?></h3>
</center>

<div class='container'>

  <?php
  $sql = "SELECT COUNT('problem_id') AS ids FROM `problem`";
  $result = pdo_query($sql);
  $row = $result[0];

  $ids = intval($row['ids']);

  $idsperpage = 50;
  $pages = intval(ceil($ids / $idsperpage));

  if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
  } else {
    $page = 1;
  }

  $pagesperframe = 5;
  $frame = intval(ceil($page / $pagesperframe));

  $spage = ($frame - 1) * $pagesperframe + 1;
  $epage = min($spage + $pagesperframe - 1, $pages);

  $sid = ($page - 1) * $idsperpage;

  $sql = "";
  if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
    $keyword = $_GET['keyword'];
    $keyword = "%$keyword%";
    $sql = "SELECT `problem_id`,`title`,`accepted`,`in_date`,`defunct` FROM `problem` WHERE (problem_id LIKE ?) OR (title LIKE ?) OR (description LIKE ?) OR (source LIKE ?)";
    $result = pdo_query($sql, $keyword, $keyword, $keyword, $keyword);
  } else {
    $sql = "SELECT `problem_id`,`title`,`accepted`,`in_date`,`defunct` FROM `problem` ORDER BY `problem_id` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
  }
  ?>

  <center>
    <form action=problem_list.php class="form-search form-inline">
      <input type="text" name=keyword class="form-control search-query" placeholder="<?php echo $MSG_PROBLEM_ID . ', ' . $MSG_TITLE . ', ' . $MSG_Description . ', ' . $MSG_SOURCE ?>">
      <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
    </form>
  </center>

  <?php
  /*
echo "<select class='input-mini' onchange=\"location.href='problem_list.php?page='+this.value;\">";
for ($i=1;$i<=$cnt;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<option value='$i' selected>";
        else  echo "<option value='$i'>";
        echo $i+9;
        echo "**</option>";
}
echo "</select>";
*/
  ?>

  <center>
    <table width=100% class='center table table-bordered table-condensed'>
      <form method=post action=contest_add.php>
        <thead>
          <tr>
            <th class='center'><?php echo $MSG_PROBLEM_ID ?>
              <input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'>
            </th>
            <th class='center'><?php echo $MSG_TITLE ?></th>
            <th class='center'><?php echo $MSG_AC ?></th>
            <th class='center'><?php echo $MSG_EDIT_TIME ?></th>
            <?php
            if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
              if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))
                echo "<th class='center'>$MSG_STATUS</th>";
              echo "<th class='center'>$MSG_EDIT</th><th class='center'>$MSG_TESTDATA</th>";
            }
            ?>
          </tr>
        </thead>
        <?php
        foreach ($result as $row) {
          echo "<tr>";
          echo "<td>" . $row['problem_id'] . " <input type=checkbox style='vertical-align:2px;' name='pid[]' value='" . $row['problem_id'] . "'></td>";
          echo "<td><a href='../problem.php?id=" . $row['problem_id'] . "'>" . $row['title'] . "</a></td>";
          echo "<td>" . $row['accepted'] . "</td>";
          echo "<td>" . $row['in_date'] . "</td>";
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
            if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
              echo "<td><a href=problem_df_change.php?id=" . $row['problem_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['defunct'] == "N" ? "<span titlc='click to reserve it' class=green>$MSG_ENABLED</span>" : "<span class=red title='click to be available'>$MSG_DISABLED</span>") . "</a>";
            }
            if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . "p" . $row['problem_id']]) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
              echo "<td><a href=problem_edit.php?id=" . $row['problem_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">$MSG_EDIT</a>";
              echo "<td><a href='javascript:phpfm(" . $row['problem_id'] . ");'>$MSG_TESTDATA</a>";
            }
          }
          echo "</tr>";
        }
        ?>
        <tr>
          <td colspan=2 style="height:40px;"><?php echo $MSG_SET_TO ?></td>
          <td colspan=6>
            <div class='form-inline'>
              <input class='form-control' type=submit name='problem2contest' value='<?php echo $MSG_ADD_TO_CONTEST ?>'>&nbsp;
              <input class='form-control' type=submit name='enable' value='<?php echo $MSG_ENABLE ?>' onclick='$("form").attr("action","problem_df_change.php")'>&nbsp;
              <input class='form-control' type=submit name='disable' value='<?php echo $MSG_DISABLE ?>' onclick='$("form").attr("action","problem_df_change.php")'>
            </div>
          </td>
        </tr>
      </form>
    </table>
  </center>

  <script src='<?php echo $OJ_CDN_URL . "template/bs3/" ?>jquery.min.js'></script>

  <script>
    function phpfm(pid) {
      //alert(pid);
      $.post("phpfm.php", {
        'frame': 3,
        'pid': pid,
        'pass': ''
      }, function(data, status) {
        if (status == "success") {
          document.location.href = "phpfm.php?frame=3&pid=" + pid;
        }
      });
    }
  </script>
</div>

<?php
if (!(isset($_GET['keyword']) && $_GET['keyword'] != "")) {
  echo "<div style='display:inline;'>";
  echo "<nav class='center'>";
  echo "<ul class='pagination pagination-sm'>";
  echo "<li class='page-item'><a href='problem_list.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
  echo "<li class='page-item'><a href='problem_list.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
  for ($i = $spage; $i <= $epage; $i++) {
    echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='problem_list.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
  }
  echo "<li class='page-item'><a href='problem_list.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
  echo "<li class='page-item'><a href='problem_list.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
  echo "</ul>";
  echo "</nav>";
  echo "</div>";
}
?>

</div>
<?php
require_once("admin-footer.php");
?>