<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

?>

<center>
  <h3><?php echo $MSG_NEWS . "-" . $MSG_LIST ?></h3>
</center>

<div class='container'>

  <?php
  $sql = "SELECT COUNT('news_id') AS ids FROM `news`";
  $result = pdo_query($sql);
  $row = $result[0];

  $ids = intval($row['ids']);

  $idsperpage = 25;
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
    $sql = "SELECT `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` WHERE (title LIKE ?) OR (content LIKE ?) ORDER BY `news_id` DESC";
    $result = pdo_query($sql, $keyword, $keyword);
  } else {
    $sql = "SELECT `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` ORDER BY `news_id` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
  }
  ?>

  <center>
    <form action=news_list.php class="form-search form-inline">
      <input type="text" name=keyword class="form-control search-query" placeholder="<?php echo $MSG_TITLE . ', ' . $MSG_CONTENTS ?>">
      <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
    </form>
  </center>
  <br>
  <div class='table-responsive'>
    <table width=100% class='center table table-condensed'>
      <thead>
        <tr style='height:22px;'>
          <th class='center'>ID</th>
          <th class='center'><?php echo $MSG_TITLE ?></th>
          <th class='center'><?php echo $MSG_LAST_UPDATE ?></th>
          <th class='center'><?php echo $MSG_STATUS ?></th>
          <th class='center'><?php echo $MSG_COPY ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($result as $row) {
          echo "<tr style='height:22px;'>";
          echo "<td>" . $row['news_id'] . "</td>";
          echo "<td><a href='news_edit.php?id=" . $row['news_id'] . "'>" . $row['title'] . "</a>" . "</td>";
          echo "<td>" . $row['time'] . "</td>";
          echo "<td><a href=news_df_change.php?id=" . $row['news_id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">" . ($row['defunct'] == "N" ? "<span class=green>开启</span>" : "<span class=red>关闭</span>") . "</a>" . "</td>";
          echo "<td><a href=news_add_page.php?cid=" . $row['news_id'] . ">复制</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <?php
  if (!(isset($_GET['keyword']) && $_GET['keyword'] != "")) {
    echo "<div style='display:inline;'>";
    echo "<nav class='center'>";
    echo "<ul class='pagination pagination-sm'>";
    echo "<li class='page-item'><a href='news_list.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
    echo "<li class='page-item'><a href='news_list.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
    for ($i = $spage; $i <= $epage; $i++) {
      echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='news_list.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
    }
    echo "<li class='page-item'><a href='news_list.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
    echo "<li class='page-item'><a href='news_list.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "</div>";
  }
  ?>

</div>
<?php
require_once("admin-footer.php");
?>