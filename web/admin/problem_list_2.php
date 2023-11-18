<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
)) {
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
              $sql = "SELECT `id`,`title`,`tag`,`source` FROM `problem_2` WHERE (`id` LIKE ?) OR (`title` LIKE ?) OR (`description` LIKE ?) OR (`tag` LIKE ?) OR (`source` LIKE ?)";
              $result = pdo_query($sql, $keyword, $keyword, $keyword, $keyword);
            } else {
              $sql = "SELECT `id`,`title`,`tag`,`source` FROM `problem_2` ORDER BY `id` DESC LIMIT $sid, $idsperpage";
              $result = pdo_query($sql);
            }
            ?>

            <center>
              <form action=problem_list_2.php class="form-search form-inline">
                <input type="text" name=keyword class="form-control search-query" placeholder="<?php echo $MSG_PROBLEM_ID . ', ' . $MSG_TITLE . ', ' . $MSG_Description . ', ' . $MSG_SOURCE ?>">
                <button type="submit" class="form-control"><?php echo $MSG_SEARCH ?></button>
              </form>
            </center>
            <br>
            <div class='table-responsive'>
              <table width=100% class='center table table-condensed'>
                <thead>
                  <tr>
                    <th class='center'><?php echo $MSG_PROBLEM_ID ?></th>
                    <th class='center'><?php echo $MSG_TITLE ?></th>
                    <th class='center'><?php echo $MSG_SOURCE ?></th>
                    <th class='center'><?php echo $MSG_Source ?></th>
                    <?php
                    if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
                      echo "<th class='center'>$MSG_EDIT</th>";
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
                    echo "<td>" . $row['source'] . "</td>";
                    if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) {
                      echo "<td><a href=problem_add_page_2.php?id=" . $row['id'] . "&getkey=" . $_SESSION[$OJ_NAME . '_' . 'getkey'] . ">$MSG_EDIT</a>";
                    }
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
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

          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>