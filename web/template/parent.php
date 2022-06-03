<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>



</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class='main-container'>
        <h2><?php echo $MSG_PARENT_SEARCH ?></h2>
        <?php
        if (isset($no_query)) {
        ?>
          <br><br>
          <table align=center width=80%>
            <tr align='center'>
              <td>
                <form method="get" action="parent.php" class="form-inline" <?php if (!isset($_SESSION[$OJ_NAME . '_' . "administrator"])) echo "onsubmit='return vcode_required(this)';" ?>>
                  <input class='form-control' id='parent_user' name='user' placeholder='<?php echo $MSG_ID_OR_NICK ?>' value="<?php if (isset($_GET["user"])) echo (htmlentities($_GET['user'])); ?>">
                  <input class='form-control' id='vcode' name='vcode' type='hidden'>
                  <button class='form-control' type='submit'><?php echo $MSG_PARENT_SEARCH ?></button>
                </form>
              </td>
            </tr>
          </table>
          <br><br>
      </div>
    </div>
  </div>
  <?php include("template/js.php"); ?>
</body>

</html>
<?php
          exit(0);
        }
?>

<table align=center width=80%>
  <tr align='center'>
    <td>
      <form method="get" action="parent.php" class="form-inline" onsubmit='return vcode_required(this);'>
        <input class='form-control' id='parent_user' name='user' placeholder='<?php echo $MSG_ID_OR_NICK ?>' value="<?php echo (htmlentities($_GET['user'])); ?>">
        <input class='form-control' id='vcode' name='vcode' type='hidden'>
        <button class='form-control' type='submit'><?php echo $MSG_PARENT_SEARCH ?></button>
      </form>
    </td>
  </tr>
</table>
<br>
<div class='table-responsive'>
  <?php
  if (is_array($user)) {
    if (count($user) == 0) {
      echo "<h3>$MSG_NOT_FOUND</h3><br>";
      include("template/js.php");
      echo ("</body></html>");
      exit(0);
    } elseif (count($user) != 1) {
      echo ("<h3>$MSG_MULTIPLE_USER_CHOICE</h3><br>");
      for ($i = 0; $i < count($user); $i++) {
        echo ("<table class='table'><tbody><tr>");
        $uid = $user[$i][0];
        echo ("<td><a href='parent.php?user=$uid'>$MSG_STUDENT_ID: $uid</a></td>");
        echo ("<td>");
        echo ("$MSG_STUDENT_NAME: " . $nick[$i][0]);
        echo ("</td>");
        echo ("<td>");
        echo ("$MSG_STUDENT_ADMINISTRATIVE_CLASS: " . $school[$i][0]);
        echo ("</td>");
        echo ("<td>");
        echo ("$MSG_STUDENT_TEACHING_CLASS: " . $group[$i][0]);
        echo ("</td></tr></tbody></table>");
      }
    }
    echo ("</div></div></div>");
    include("template/js.php"); ?>
    <script>
      <?php if ($view_error != "") { ?>
        swal('<?php echo $view_error; ?>');
      <?php } ?>
    </script>
  <?php
    echo ("</body></html>");
    exit(0);
  } else {
    $nick = $nick[0];
    $school = $school[0];
    $group = $group[0];
  }
  ?>
  <td>
    <table class='table'>
      <thead class='toprow'>
        <tr>
          <th>
            <?php echo $MSG_STUDENT_ID ?>
          </th>
          <th>
            <?php echo $MSG_STUDENT_NAME ?>
          </th>
          <th>
            <?php echo $MSG_STUDENT_ADMINISTRATIVE_CLASS ?>
          </th>
          <th>
            <?php echo $MSG_STUDENT_TEACHING_CLASS ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <td>
          <?php echo $user; ?>
        </td>
        <td>
          <?php echo $nick[0]; ?>
        </td>
        <td>
          <?php echo $school[0]; ?>
        </td>
        <td>
          <?php echo $group[0]; ?>
        </td>
      </tbody>
    </table>
  </td>
</div>
<div class='table-responsive'>
  <table class='table'>
    <thead class='toprow'>
      <tr>
        <th><?php echo $MSG_CONTEST_NAME ?></th>
        <th><?php echo $MSG_END_TIME ?></th>
        <th><?php echo $MSG_PROBLEM_ID ?></th>
        <th><?php echo $MSG_IS_FINISHED_IN_TIME ?></th>
        <th><?php echo $MSG_IS_FINISHED ?></th>
        <th><?php echo $MSG_IS_SIM_CHECKED ?></th>
      <tr>
    </thead>
    <tbody>
      <?php

      foreach ($contests as $i) {
        echo ("<tr>");
        $cid = $i[0];
        $contests_name = $i[1];
        $end_time = $i[2];
        $problem_id = $i[3];
        echo ("<td><a href='contest.php?cid=$cid'>" . $contests_name . "</a></td>");
        echo ("<td>" . $end_time . "</td>");
        echo ("<td><table class='table-condensed'><tbody>");
        foreach ($problem_id as $i) {
          echo ("<tr>");
          $pid = $i[0];
          echo ("<td><a href='problem.php?id=$pid'>" . $pid . "</td>");
          echo ("</tr>");
        }
        echo ("</tbody></table></td>");
        echo ("<td><table class='table-condensed'><tbody>");
        foreach ($problem_id as $i) {
          echo ("<tr>");
          $pid = $i[0];
          $before_ac = $i[1];
          $before = $i[2];
          if ($before == 0) {
            echo ("<td><span class='label label-danger'>$MSG_UNFINISHED[true]</span></td>");
          } else {
            if ($before_ac == 0) {
              echo ("<td><span class='label label-warning'>$MSG_NOT_PASS</span></td>");
            } else {
              echo ("<td><span class='label label-success'>$MSG_FINISHED</span></td>");
            }
          }
          echo ("</tr>");
        }
        echo ("</tbody></table></td>");
        echo ("<td><table class='table-condensed'><tbody>");
        foreach ($problem_id as $i) {
          echo ("<tr>");
          $pid = $i[0];
          $after_ac = $i[3];
          $after = $i[4];
          if ($after == 0) {
            echo ("<td><span class='label label-danger'>$MSG_UNFINISHED</span></td>");
          } else {
            if ($after_ac == 0) {
              echo ("<td><span class='label label-warning'>$MSG_NOT_PASS</span></td>");
            } else {
              echo ("<td><span class='label label-success'>$MSG_FINISHED</span></td>");
            }
          }
          echo ("</tr>");
        }
        echo ("</tbody></table></td>");
        echo ("<td><table class='table-condensed'");
        foreach ($problem_id as $i) {
          echo ("<tr>");
          $problem_id = $i[0];
          $after_ac = $i[3];
          if ($after_ac == 0) {
            echo ("<td>---</td>");
          } else {
            $count = $i[5];
            if ($count != 0) {
              echo ("<td><a href='status.php?user_id=$user&showsim=80&problem_id=$problem_id' class='label label-warning'>$MSG_SIM_YES</a></td>");
            } else {
              echo ("<td><span class='label label-success'>$MSG_SIM_NO</span></td>");
            }
          }
        }
        echo ("</tbody></table></td>");
        echo ("</tr>");
        echo ("</tr>");
      }
      ?>
    </tbody>
  </table>
</div>
<?php echo $MSG_PARENT_EXPLAIN ?>
</div>
</div>
</div>
<?php include("template/js.php"); ?>
<script src="<?php echo $OJ_CDN_URL . "template/" ?>watermark.js"></script>
<?php
$info = time() . " " . $_COOKIE["PHPSESSID"];
?>
<script>
  window.onload = function() {
    watermark.init({
      watermark_txt: "<?php echo $info; ?>",
      watermark_width: 300,
      watermark_alpha: 0.08
    });
  }
</script>
</body>

</html>