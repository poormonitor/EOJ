<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

?>

<center>
  <h3><?php echo $MSG_PROBLEM . "-" . $MSG_LIST ?></h3>
</center>

<div class='container'>

  <?php
  $sql = "SELECT COUNT('id') AS ids FROM `problem_2`";
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
    $sql = "SELECT `id`,`title`,`tag` FROM `problem_2` WHERE (`id` LIKE ?) OR (`title` LIKE ?) OR (`description` LIKE ?) OR (`tag` LIKE ?)";
    $result = pdo_query($sql, $keyword, $keyword, $keyword, $keyword);
  } else {
    $sql = "SELECT `id`,`title`,`tag` FROM `problem_2` ORDER BY `id` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
  }
  ?>

  <center>
    <form action=problem_list_2.php class="form-search form-inline">
      <input type="text" name=keyword class="form-control search-query" placeholder="<?php echo $MSG_PROBLEM_ID . ', ' . $MSG_TITLE . ', ' . $MSG_Description . ', ' . $MSG_SOURCE ?>">
      <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
    </form>
  </center>
  <br />
  <center>
    <table width=100% class='center table table-bordered table-condensed'>
      <thead>
        <tr>
          <th class='center'><?php echo $MSG_PROBLEM_ID ?></th>
          <th class='center'><?php echo $MSG_TITLE ?></th>
          <th class='center'><?php echo $MSG_SOURCE ?></th>
          <?php
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
            echo "<th class='center'>编辑</th>";
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($result as $row) {
          echo "<tr>";
          echo "<td>" . $row['id'] . "</td>";
          echo "<td>" . $row['title'] . "</td>";
          echo "<td>" . $row['tag'] . "</td>";
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
            echo "<td><a href=problem_add_page_2.php?id=" . $row['id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">编辑</a>";
          }
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </center>

  <script src='<?php echo $OJ_CDN_URL . "template/bs3/" ?>jquery.min.js'></script>
</div>

<?php
if (!(isset($_GET['keyword']) && $_GET['keyword'] != "")) {
  echo "<div style='display:inline;'>";
  echo "<nav class='center'>";
  echo "<ul class='pagination pagination-sm'>";
  echo "<li class='page-item'><a href='problem_list_2.php?page=" . (strval(1)) . "'>&lt;&lt;</a></li>";
  echo "<li class='page-item'><a href='problem_list_2.php?page=" . ($page == 1 ? strval(1) : strval($page - 1)) . "'>&lt;</a></li>";
  for ($i = $spage; $i <= $epage; $i++) {
    echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='problem_list_2.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
  }
  echo "<li class='page-item'><a href='problem_list_2.php?page=" . ($page == $pages ? strval($page) : strval($page + 1)) . "'>&gt;</a></li>";
  echo "<li class='page-item'><a href='problem_list_2.php?page=" . (strval($pages)) . "'>&gt;&gt;</a></li>";
  echo "</ul>";
  echo "</nav>";
  echo "</div>";
}
?>

</div>
<br />
<?php
require_once("admin-footer.php");
?>