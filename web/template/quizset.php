<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>


</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <br><br>
      <table align=center width=80%>
        <tr align='center'>
          <td>
            <form class=form-inline method=post action=contest.php>
              <input class="form-control" name=keyword value="<?php if (isset($_POST['keyword'])) echo htmlentities($_POST['keyword'], ENT_QUOTES, "UTF-8") ?>" placeholder="<?php echo $MSG_QUIZ_NAME ?>">
              <button class="form-control" type=submit><?php echo $MSG_SEARCH ?></button>
            </form>
          </td>
        </tr>
      </table>
      <br>

      <center>
        <h3><?php echo $MSG_SERVER_TIME ?> <span id=nowdate></span></h3>
      </center>
      <br>
      <div class="table-responsive">
        <table class='table table-striped' width=90%>
          <thead>
            <tr class=toprow>
              <th class='center'><?php echo $MSG_QUIZ_ID ?></th>
              <th class='center'><?php echo $MSG_QUIZ_NAME ?></th>
              <th><?php echo $MSG_QUIZ_STATUS ?></th>
              <th class='center'><?php echo $MSG_QUIZ_OPEN ?></th>
              <th class='center'><?php echo $MSG_QUIZ_CREATOR ?></th>
            </tr>
          </thead>
          <tbody align='center'>
            <?php
            $cnt = 0;
            foreach ($view_quiz as $row) {
              if ($cnt)
                echo "<tr class='oddrow'>";
              else
                echo "<tr class='evenrow'>";
              $i = 0;
              foreach ($row as $table_cell) {
                if ($i == 2) echo "<td class=text-left>";
                else echo "<td>";
                echo "\t" . $table_cell;
                echo "</td>";
                $i++;
              }
              echo "</tr>";
              $cnt = 1 - $cnt;
            }
            ?>
          </tbody>
        </table>
      </div>
      <nav class="center">
          <small>
            <ul class="pagination">
              <?php
              $section = 2;
              $href_url = "quizt.php"
              ?>
              <?php if ($page > $section + 1) { ?>
                <li class="page-item"><a href="<?php echo $href_url ?>?page=1">1</a></li>
              <?php } ?>
              <?php if ($page > $section + 2) { ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
              <?php } ?>
              <?php
              if (!isset($page)) $page = 1;
              $page = intval($page);
              $start = $page > $section ? $page - $section : 1;
              $end = $page + $section > $view_total_page ? $view_total_page : $page + $section;
              for ($i = $start; $i <= $end; $i++) {
                echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='Go to page' href='$href_url?page=$i'>$i</a></li>";
              }
              ?>
              <?php if ($page < $view_total_page - $section - 1) { ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
              <?php } ?>
              <?php if ($page < $view_total_page - $section) { ?>
                <li class="page-item"><a href="<?php echo $href_url ?>?page=<?php echo $view_total_page ?>"><?php echo $view_total_page ?></a></li>
              <?php } ?>
            </ul>
          </small>
        </nav>
      </center>

    </div>
  </div>
  <?php include("template/js.php"); ?>

  <script>
    var diff = new Date("<?php echo date("Y/m/d H:i:s") ?>").getTime() - new Date().getTime();
    clock(diff);
  </script>
</body>

</html>