<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if (isset($OJ_LANG)) {
  require_once("../lang/$OJ_LANG.php");
}
?>

<title>The Second Problem List</title>
<hr>
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

  <?php
  /*
echo "<select class='input-mini' onchange=\"location.href='problem_list_2.php?page='+this.value;\">";
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
    <table width=100% border=1 style="text-align:center;">
      <tr>
        <td width=60px><?php echo $MSG_PROBLEM_ID ?></td>
        <td><?php echo $MSG_TITLE ?></td>
        <td><?php echo $MSG_SOURCE ?></td>
        <?php
        if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
          echo "<td>编辑</td>";
        }
        ?>
      </tr>
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