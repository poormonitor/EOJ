<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
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
            <form method="get" action="parent.php" class="form-inline" <?php if (!isset($_SESSION[$OJ_NAME . '_' . "user_id"])) echo "onsubmit='return vcode_required(this)';" ?>>
              <input class='form-control' id='parent_user' name='user' placeholder='<?php echo $MSG_ID_OR_NICK ?>' value="<?php if (isset($_GET['user'])) echo (htmlentities($_GET['user'])); ?>">
              <input class='form-control' id='vcode' name='vcode' type='hidden'>
              <button class='form-control' type='submit'><?php echo $MSG_PARENT_SEARCH ?></button>
            </form>
          </td>
        </tr>
      </table>
      <br>
      <table align=center width=80%>
        <tr align='center'>
          <td>
            <form class=form-inline method=post action=contest.php>
              <input class="form-control" name=keyword value="<?php if (isset($_POST['keyword'])) echo htmlentities($_POST['keyword'], ENT_QUOTES, "UTF-8") ?>" placeholder="<?php echo $MSG_CONTEST_NAME ?>">
              <button class="form-control" type=submit><?php echo $MSG_SEARCH ?></button>
            </form>
          </td>
        </tr>
      </table>
      <br>

      <center>
        <h3><?php echo $MSG_SERVER_TIME ?> <span id=nowdate></span></h3>
      </center><br>
      <div class="table-responsive">
        <table class='table table-striped' width=90%>
          <thead>
            <tr class=toprow>
              <th class='center'><?php echo $MSG_CONTEST_ID ?></th>
              <th class='center'><?php echo $MSG_CONTEST_NAME ?></th>
              <th><?php echo $MSG_CONTEST_STATUS ?></th>
              <th class='center'><?php echo $MSG_CONTEST_OPEN ?></th>
              <th class='center'><?php echo $MSG_CONTEST_CREATOR ?></th>
            </tr>
          </thead>
          <tbody align='center'>
            <?php
            $cnt = 0;
            foreach ($view_contest as $row) {
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
      <center>
        <nav class="center">
          <small>
            <ul class="pagination">
              <?php
              $section = 2;
              $href_url = isset($_GET["my"]) ? "contest.php?my&" : "contest.php?";
              ?>
              <?php if ($page > $section + 1) { ?>
                <li class="page-item"><a href="<?php echo $href_url ?>page=1">1</a></li>
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
                echo "<li class='" . ($page == $i ? "active " : "") . "page-item'><a title='Go to page' href='" . $href_url . "page=$i'>$i</a></li>";
              }
              ?>
              <?php if ($page < $view_total_page - $section - 1) { ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
              <?php } ?>
              <?php if ($page < $view_total_page - $section) { ?>
                <li class="page-item"><a href="<?php echo $href_url ?>page=<?php echo $view_total_page ?>"><?php echo $view_total_page ?></a></li>
              <?php } ?>
            </ul>
          </small>
        </nav>
      </center>

    </div>
  </div>
  <?php include("template/js.php"); ?>

  <script>
    var diff = new Number("<?php echo round(microtime(true) * 1000) ?>") - new Date().getTime();
    setTimeout("clock()", diff > 0 ? diff % 1000 : 1000 + diff % 1000);
  </script>
</body>

</html>