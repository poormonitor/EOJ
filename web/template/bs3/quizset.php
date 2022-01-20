<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/$OJ_TEMPLATE/css.php"); ?>


</head>

<body>
  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <br /><br />
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
      <br />

      <center>
        <h3><?php echo $MSG_SERVER_TIME ?> <span id=nowdate></span></h3>
      </center><br />
      <div class="table-responsive">
        <table class='table table-striped' width=90%>
          <thead>
            <tr class=toprow align=center>
              <td><?php echo $MSG_QUIZ_ID ?></td>
              <td><?php echo $MSG_QUIZ_NAME ?></td>
              <td><?php echo $MSG_QUIZ_STATUS ?></td>
              <td><?php echo $MSG_QUIZ_OPEN ?></td>
              <td><?php echo $MSG_QUIZ_CREATOR ?></td>
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
            <li class="page-item"><a href="quiz.php?page=1">&lt;&lt;</a></li>
            <?php
            if ($page != 1) { ?>
              <li class="page-item"><a href="quiz.php?page=<?php echo $page - 1 ?>">&lt;</a></li>
            <?php } ?>
            <?php
            if (!isset($page)) $page = 1;
            $page = intval($page);
            $section = 8;
            $start = $page > $section ? $page - $section : 1;
            $end = $page + $section > $view_total_page ? $view_total_page : $page + $section;
            for ($i = $start; $i <= $end; $i++) {
              echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='go to page' href='contest.php?page=" . $i . (isset($_GET['my']) ? "&my" : "") . "'>" . $i . "</a></li>";
            }
            ?>
            <?php
            if ($page != $view_total_page) { ?>
              <li class="page-item"><a href="quiz.php?page=<?php if ($page != $view_total_page) echo $page + 1 ?>">&gt;</a></li>
            <?php } ?>
            <li class="page-item"><a href="quiz.php?page=<?php echo $view_total_page ?>">&gt;&gt;</a></li>
          </ul>
        </small>
      </nav>
      </center>

    </div>
  </div>
  <?php include("template/$OJ_TEMPLATE/js.php"); ?>

  <script>
    var diff = new Date("<?php echo date("Y/m/d H:i:s") ?>").getTime() - new Date().getTime();
    clock(diff);
  </script>
</body>

</html>