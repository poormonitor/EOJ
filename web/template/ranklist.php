<?php if (stripos($_SERVER['REQUEST_URI'], "template")) exit(); ?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $MSG_RANKLIST . " - " . $OJ_NAME ?></title>
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
            <form class=form-inline action=ranklist.php>
              <input class="form-control" name='prefix' value="<?php echo htmlentities(isset($_GET['prefix']) ? $_GET['prefix'] : "", ENT_QUOTES, "utf-8") ?>" placeholder="<?php echo $MSG_USER ?>">
              <button class="form-control" type='submit'><?php echo $MSG_SEARCH ?></button>
            </form>
          </td>
        </tr>
      </table>

      <br>
      <table align=right>
        <tr>
          <td>
            <a class='label label-info' href=ranklist.php?scope=d>Day</a>
            <a class='label label-info' href=ranklist.php?scope=w>Week</a>
            <a class='label label-info' href=ranklist.php?scope=m>Month</a>
            <a class='label label-info' href=ranklist.php?scope=y>Year</a>
            &nbsp;
          </td>
        <tr>
      </table>
      <br>

      <table class="table table-striped content-box-header" align=center>
        <thead>
          <tr class='toprow'>
            <th class='text-center'><?php echo $MSG_Number ?></th>
            <th class='text-center'><?php echo $MSG_USER ?></th>
            <th class='text-center'><?php echo $MSG_NICK ?></th>
            <th class='text-center'><?php echo $MSG_SOVLED ?></th>
            <th class='text-center'><?php echo $MSG_SUBMIT ?></th>
            <th class='text-center'><?php echo $MSG_RATIO ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $cnt = 0;
          foreach ($view_rank as $row) {
            if ($cnt)
              echo "<tr class='oddrow'>";
            else
              echo "<tr class='evenrow'>";

            $i = 0;
            foreach ($row as $table_cell) {
              echo "<td class='text-center'>";
              echo $table_cell;
              echo "</td>";
              $i++;
            }
            echo "</tr>";
            $cnt = 1 - $cnt;
          }
          ?>
        </tbody>
      </table>
      <?php
      $qs = "";
      if (isset($_GET['prefix'])) {
        $qs .= "&prefix=" . htmlentities($_GET['prefix'], ENT_QUOTES, "utf-8");
      }
      if (isset($scope)) {
        $qs .= "&scope=" . htmlentities($scope, ENT_QUOTES, "utf-8");
      }
      ?>
      <nav id="page" class="center">
        <small>
          <ul class="pagination">
            <?php if ($start > 0) { ?>
              <li class="page-item"><a href="ranklist.php?start=0">&lt;&lt;</a></li>
              <li class="page-item"><a href="ranklist.php?start=<?php echo $start - $page_size ?>">&lt;</a></li>
            <?php }
            for ($i = 0; $i < $view_total; $i += $page_size) {
              if ($start == $i) {
                echo "<li class='active page-item'><a href='./ranklist.php?start=" . strval($i) . $qs . "'>";
                echo strval($i + 1);
                echo "-";
                echo strval($i + $page_size);
                echo "</a></li>";
              } else if ($i <= $start + 2 * $page_size and $i >= $start - 2 * $page_size) {
                echo "<li class='page-item'><a href='./ranklist.php?start=" . strval($i) . $qs . "'>";
                echo strval($i + 1);
                echo "-";
                echo strval($i + $page_size);
                echo "</a></li>";
              }
            }
            if ($start < $view_total - $page_size) { ?>
              <li class="page-item"><a href="ranklist.php?start=<?php echo strval($start + $page_size) . $qs ?>">&gt;</a></li>
              <li class="page-item"><a href="ranklist.php?start=<?php echo strval($i - $page_size) . $qs ?>">&gt;&gt;</a></li>
            <?php } ?>
          </ul>
        </small>
      </nav>

    </div>
  </div>
  <?php include("template/js.php"); ?>
</body>

</html>