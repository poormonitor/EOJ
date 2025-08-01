<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="`">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title>
    <?php echo $view_title . " - " . $OJ_NAME ?>
  </title>

  <?php include("template/css.php"); ?>

</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <div>
          <h3><?php echo $MSG_CONTEST_ID ?> : <?php echo $view_cid ?> - <?php echo $view_title ?></h3>
          <p>
            <?php echo $view_description ?>
          </p>
          <br>
          <?php echo $MSG_SERVER_TIME ?> : <span id=nowdate> <?php echo date("Y-m-d H:i:s") ?></span>
          <br>

          <?php if (isset($OJ_RANK_LOCK_PERCENT) && $OJ_RANK_LOCK_PERCENT != 0) { ?>
            Lock Board Time: <?php echo date("Y-m-d H:i:s", $view_lock_time) ?><br>
          <?php } ?>

          <?php if ($now > $end_time) {
            echo "<span class=text-muted>$MSG_Ended</span>";
          } else if ($now < $start_time) {
            echo "<span class=text-success>$MSG_NotStarted</span>&nbsp;";
            echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
          } else {
            echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
            echo "<span class='text-danger'>$MSG_LeftTime</span> <span class='time-left'>" . formatTimeLength($end_time - $now) . "</span>";
          }
          ?>

          <br><br>

          <?php echo $MSG_CONTEST_STATUS ?> :

          <?php
          if ($now > $end_time)
            echo "<span class=text-muted>" . $MSG_End . "</span>";
          else if ($now < $start_time)
            echo "<span class=text-success>" . $MSG_NotStarted . "</span>";
          else
            echo "<span class=text-danger>" . $MSG_Running . "</span>";
          ?>
          &nbsp;&nbsp;

          <?php echo $MSG_CONTEST_OPEN ?> :

          <?php if ($view_private == '0')
            echo "<span class=text-primary>" . $MSG_Public . "</span>";
          else
            echo "<span class=text-danger>" . $MSG_Private . "</span>";
          ?>

          <br>

          <?php echo $MSG_START_TIME ?> : <?php echo $view_start_time ?>
          <br>
          <?php echo $MSG_END_TIME ?> : <?php echo $view_end_time ?>
          <br><br>

          <div class="btn-group">
            <a href="contest.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_PROBLEMS ?></a>
            <a href="status.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_SUBMIT ?></a>
            <a href="contestrank.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STANDING ?></a>
            <a href="contestrank-oi.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo "OI" . $MSG_STANDING ?></a>
            <a href="conteststatistics.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STATISTICS ?></a>
            <a href="suspect_list.php?cid=<?php echo $view_cid ?>" class="btn btn-warning btn-sm"><?php echo $MSG_IP_VERIFICATION ?></a>
            <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
              <a href="user_set_ip.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_SET_LOGIN_IP ?></a>
              <a target="_blank" href="admin/contest_edit.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_EDIT ?></a>
            <?php } ?>
          </div>
        </div>

        <div class="table-responsive">
          <table id='problemset' class='table table-striped' width='90%'>
            <thead>
              <tr align=center class='toprow'>
                <th class='center'></th>
                <th class='center' style="cursor:hand" onclick="sortTable('problemset', 1, 'int');"><?php echo $MSG_PROBLEM_ID ?></th>
                <th class='center'><?php echo $MSG_TITLE ?></th>
                <th class='center'><?php echo $MSG_SOURCE ?></th>
                <th class='center' style="cursor:hand" onclick="sortTable('problemset', 4, 'int');"><?php echo $MSG_SOVLED ?></th>
                <th class='center' style="cursor:hand" onclick="sortTable('problemset', 5, 'int');"><?php echo $MSG_SUBMIT ?></th>
              </tr>
            </thead>
            <tbody align='center'>
              <?php
              $cnt = 0;
              foreach ($view_problemset as $row) {
                if ($cnt)
                  echo "<tr class='oddrow'>";
                else
                  echo "<tr class='evenrow'>";

                foreach ($row as $table_cell) {
                  echo "<td>";
                  echo "\t" . $table_cell;
                  echo "</td>";
                }
                echo "</tr>";
                $cnt = 1 - $cnt;
              }
              ?>
            </tbody>
          </table>
        </div>
      </center>
    </div>
  </div>

  <?php include("template/js.php"); ?>

  <script src="<?php echo $OJ_CDN_URL ?>include/sortTable.min.js"></script>

  <script>
    var diff = new Number("<?php echo round(microtime(true) * 1000) ?>") - new Date().getTime();
    setTimeout("clock()", diff > 0 ? diff % 1000 : 1000 + diff % 1000)
  </script>

</body>

</html>