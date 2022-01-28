<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

?>
<center>
  <h3><?php echo $MSG_CONTEST . "-" . $MSG_LIST ?></h3>
</center>

<div class='container'>
  <?php
  $sql = "SELECT COUNT('contest_id') AS ids FROM `contest`";
  $result = pdo_query($sql);
  $row = $result[0];

  $ids = intval($row['ids']);

  $idsperpage = 10;
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
    $sql = "SELECT `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` WHERE (title LIKE ?) OR (description LIKE ?) ORDER BY `contest_id` DESC";
    $result = pdo_query($sql, $keyword, $keyword);
  } else {
    $sql = "SELECT `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` ORDER BY `contest_id` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
  }
  ?>

  <center>
    <form action=contest_list.php class="form-search form-inline">
      <input type="text" name=keyword class="form-control search-query" placeholder="<?php echo $MSG_CONTEST_NAME . ', ' . $MSG_EXPLANATION ?>">
      <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
    </form>
  </center>
  <br>
  <div class='table-responsive'>
    <table width=100% class='center table table-condensed'>
      <thead>
        <tr>
          <th class='center'>ID</th>
          <th class='center'><?php echo $MSG_CONTEST_NAME ?></th>
          <th class='center'><?php echo $MSG_START_TIME ?></th>
          <th class='center'><?php echo $MSG_END_TIME ?></th>
          <th class='center'><?php echo $MSG_PRIVILEGE ?></th>
          <th class='center'><?php echo $MSG_STATUS ?></th>
          <th class='center'><?php echo $MSG_EDIT ?></th>
          <th class='center'><?php echo $MSG_COPY ?></th>
          <th class='center'><?php echo $MSG_EXPORT ?></th>
          <th class='center'><?php echo $MSG_LOG ?></th>
          <th class='center'><?php echo $MSG_SUSPECT ?></th>
        </tr>
      </thead>
      <?php
      foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['contest_id'] . "</td>";
        echo "<td><a href='../contest.php?cid=" . $row['contest_id'] . "'>" . $row['title'] . "</a></td>";
        echo "<td>" . $row['start_time'] . "</td>";
        echo "<td>" . $row['end_time'] . "</td>";
        $cid = $row['contest_id'];
        if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . "m$cid"])) {
          echo "<td><a href=contest_pr_change.php?cid=" . $row['contest_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['private'] == "0" ? "<span class=green>公开</span>" : "<span class=red>私有<span>") . "</a></td>";
          echo "<td><a href=contest_df_change.php?cid=" . $row['contest_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['defunct'] == "N" ? "<span class=green>可用</span>" : "<span class=red>锁定</span>") . "</a></td>";
          echo "<td><a href=contest_edit.php?cid=" . $row['contest_id'] . ">编辑</a></td>";
          echo "<td><a href=contest_add.php?cid=" . $row['contest_id'] . ">复制</a></td>";
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) {
            echo "<td><a href=\"problem_export_xml.php?cid=" . $row['contest_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "\">导出</a></td>";
          } else {
            echo "<td></td>";
          }
          echo "<td> <a href=\"../export_contest_code.php?cid=" . $row['contest_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . "\">日志</a></td>";
        } else {
          echo "<td colspan=5 align=right><a href=contest_add.php?cid=" . $row['contest_id'] . ">复制</a><td>";
        }
        echo "<td><a href='suspect_list.php?cid=" . $row['contest_id'] . "'>可疑记录</a></td>";
        echo "</tr>";
      }
      ?>
    </table>
  </div>

  <?php
  if (!(isset($_GET['keyword']) && $_GET['keyword'] != "")) {
    echo "<div style='display:inline;'>";
    echo "<nav class='center'>";
    echo "<ul class='pagination pagination-sm'>";
    echo "<li class='page-item'><a href='contest_list.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
    echo "<li class='page-item'><a href='contest_list.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
    for ($i = $spage; $i <= $epage; $i++) {
      echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='contest_list.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
    }
    echo "<li class='page-item'><a href='contest_list.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
    echo "<li class='page-item'><a href='contest_list.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "</div>";
  }
  ?>

</div>
<?php
require_once("admin-footer.php");
?>