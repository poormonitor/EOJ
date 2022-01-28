<?php
require("admin-header.php");
require_once("../include/set_get_key.php");
?>

<center>
  <h3><?php echo $MSG_QUIZ . "-" . $MSG_LIST ?></h3>
</center>

<div class='container'>

  <?php
  $sql = "SELECT COUNT('quiz_id') AS ids FROM `quiz`";
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
    $sql = "SELECT `quiz_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `quiz` WHERE (title LIKE ?) OR (description LIKE ?) ORDER BY `quiz_id` DESC";
    $result = pdo_query($sql, $keyword, $keyword);
  } else {
    $sql = "SELECT `quiz_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `quiz` ORDER BY `quiz_id` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
  }
  ?>
  <center>
    <form action=quiz_list.php class="form-search form-inline">
      <input type="text" name=keyword class="form-control search-query" placeholder="<?php echo $MSG_QUIZ . ', ' . $MSG_EXPLANATION ?>">
      <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
    </form>
  </center>
  <br />
  <center>
    <form action=quiz_add.php method='GET' class="form-search form-inline">
      <input type="text" name='num' class="form-control search-query" required placeholder="<?php echo $MSG_INQUERY_NUMBER ?>">
      <button type="submit" class="form-control"><?php echo $MSG_ADD ?></button>
    </form>
  </center>
  <br />
  <center>
    <table width=100% class='center table table-bordered table-condensed'>
      <thead>
        <tr>
          <th class='center'>ID</th>
          <th class='center'><?php echo $MSG_QUIZ_TITLE ?></th>
          <th class='center'><?php echo $MSG_START_TIME ?></th>
          <th class='center'><?php echo $MSG_END_TIME ?></th>
          <th class='center'><?php echo $MSG_PRIVILEGE ?></th>
          <th class='center'><?php echo $MSG_STATUS ?></th>
          <th class='center'><?php echo $MSG_EDIT ?></th>
          <th class='center'><?php echo $MSG_COPY ?></th>
          <th class='center'><?php echo $MSG_REJUDGE ?></th>
          <th class='center'><?php echo $MSG_QUIZ_JUDGE ?></th>
          <th class='center'><?php echo $MSG_ANALYSIS ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($result as $row) {
          echo "<tr>";
          echo "<td>" . $row['quiz_id'] . "</td>";
          echo "<td><a href='../quiz.php?qid=" . $row['quiz_id'] . "'>" . $row['title'] . "</a></td>";
          echo "<td>" . $row['start_time'] . "</td>";
          echo "<td>" . $row['end_time'] . "</td>";
          $qid = $row['quiz_id'];
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . "mq$qid"])) {
            echo "<td><a href=quiz_pr_change.php?qid=" . $row['quiz_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['private'] == "0" ? "<span class=green>公开</span>" : "<span class=red>私有<span>") . "</a></td>";
            echo "<td><a href=quiz_df_change.php?qid=" . $row['quiz_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['defunct'] == "N" ? "<span class=green>可用</span>" : "<span class=red>锁定</span>") . "</a></td>";
            echo "<td><a href=quiz_edit.php?qid=" . $row['quiz_id'] . ">编辑</a></td>";
            echo "<td><a href=quiz_add.php?qid=" . $row['quiz_id'] . ">复制</a></td>";
          } else {
            echo "<td colspan=5 align=right><a href=quiz_add.php?qid=" . $row['quiz_id'] . ">复制</a><td>";
          }
          echo "<td><a href=quiz_rejudge.php?qid=" . $row['quiz_id'] . ">" . $MSG_REJUDGE . "</a></td>";
          echo "<td><a href=quiz_judge.php?qid=" . $row['quiz_id'] . ">" . $MSG_QUIZ_JUDGE . "</a></td>";
          echo "<td><a href=quiz_analysis.php?qid=" . $row['quiz_id'] . ">" . $MSG_ANALYSIS . "</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </center>

  <?php
  if (!(isset($_GET['keyword']) && $_GET['keyword'] != "")) {
    echo "<div style='display:inline;'>";
    echo "<nav class='center'>";
    echo "<ul class='pagination pagination-sm'>";
    echo "<li class='page-item'><a href='quiz_list.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
    echo "<li class='page-item'><a href='quiz_list.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
    for ($i = $spage; $i <= $epage; $i++) {
      echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='quiz_list.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
    }
    echo "<li class='page-item'><a href='quiz_list.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
    echo "<li class='page-item'><a href='quiz_list.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "</div>";
  }
  ?>

</div>
<br />
<?php if (isset($_GET['judge_over'])) { ?>
  <script src="<?php echo $OJ_CDN_URL . "include/" ?>sweetalert.min.js"></script>
  <script>
    swal({
      title: '<?php echo $MSG_JUDGE_OVER ?>',
      icon: 'success',
      timer: 1500,
    })
  </script>
<?php } ?>
<?php
require_once("admin-footer.php");
?>