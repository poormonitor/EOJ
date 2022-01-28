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
  <?php include("template/css.php"); ?>



</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class='main-container'>
        <h2>家长查询</h2>
        <?php
        if (isset($no_query)) {
        ?>
          <br /><br />
          <table align=center width=80%>
            <tr align='center'>
              <td>
                <form method="get" action="parent.php" class="form-inline" <?php if (!isset($_SESSION[$OJ_NAME . '_' . "administrator"])) echo "onsubmit='return vcode_required(this)';" ?>>
                  <input class='form-control' id='parent_user' name='user' placeholder='学生账号(学号)/姓名' value="<?php if (isset($_GET["user"])) echo (htmlentities($_GET['user'])); ?>">
                  <input class='form-control' id='vcode' name='vcode' type='hidden'>
                  <button class='form-control' type='submit'>家长查询</button>
                </form>
              </td>
            </tr>
          </table>
          <br /><br />
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
        <input class='form-control' id='parent_user' name='user' placeholder='学生账号(学号)/姓名' value="<?php echo (htmlentities($_GET['user'])); ?>">
        <input class='form-control' id='vcode' name='vcode' type='hidden'>
        <button class='form-control' type='submit'>家长查询</button>
      </form>
    </td>
  </tr>
</table>
<br />
<div class='table-responsive'>
  <?php
  if (is_array($user)) {
    if (count($user) == 0) {
      echo ("<h3>未找到！</h3><br />");
      echo ("<h4>提示：学生学号组成如下：20（入学年份）+ 1（贡院）+ 01（班级）+01（班内编号）。</h4>");
      echo ("<h4>如 2010101，代表20级贡院1班1号。</h4>");
      echo ("</div></div></div>");
      include("template/js.php");
      echo ("</body></html>");
      exit(0);
    } elseif (count($user) != 1) {
      echo ("<h3>有多个匹配，请选择！</h3><br />");
      for ($i = 0; $i < count($user); $i++) {
        echo ("<table class='table'><tbody><tr>");
        $uid = $user[$i][0];
        echo ("<td><a href='parent.php?user=$uid'>学号：$uid</a></td>");
        echo ("<td>");
        echo ("学生姓名：" . $nick[$i][0]);
        echo ("</td>");
        echo ("<td>");
        echo ("行政班：" . $school[$i][0]);
        echo ("</td>");
        echo ("<td>");
        echo ("教学班：" . $group[$i][0]);
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
          <td>
            学号:
            <?php
            echo ($user);
            ?>
          </td>
          <td>
            学生姓名:
            <?php
            echo ($nick[0]);
            ?>
          </td>
          <td>
            行政班:
            <?php
            echo ($school[0]);
            ?>
          </td>
          <td>
            教学班:
            <?php
            echo ($group[0]);
            ?>
          </td>
        </tr>
      </thead>
    </table>
  </td>
</div>
<div class='table-responsive'>
  <table class='table'>
    <thead class='toprow'>
      <tr>
        <th>作业名称</th>
        <th>结束时间</th>
        <th>题目编号</th>
        <th>是否按时完成</th>
        <th>是否完成</th>
        <th>是否被查重</th>
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
            echo ("<td><span class='label label-danger'>未提交</span></td>");
          } else {
            if ($before_ac == 0) {
              echo ("<td><span class='label label-warning'>未通过</span></td>");
            } else {
              echo ("<td><span class='label label-success'>已完成</span></td>");
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
            echo ("<td><span class='label label-danger'>未提交</span></td>");
          } else {
            if ($after_ac == 0) {
              echo ("<td><span class='label label-warning'>未通过</span></td>");
            } else {
              echo ("<td><span class='label label-success'>已完成</span></td>");
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
              echo ("<td><a href='status.php?user_id=$user&showsim=80&problem_id=$problem_id' class='label label-warning'>被查重</a></td>");
            } else {
              echo ("<td><span class='label label-success'>未被查重</span></td>");
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
<div class='status_explain'>
  <p> 状态解释：
    <li>未提交，意为用户并未在系统中提交任何代码。</li>
    <li>未通过，意为用户提交过代码，但没能完全通过测评。</li>
    <li>已完成，意为用户的代码通过测评。</li>
    <li>被查重，仅代表提交的代码在已提交中有相似者，并不能作为抄袭的依据。</li>
  </p>
</div>
<p>本页面所提供之数据，仅为被查询用户在本系统中的使用情况之显示，并不构成任何意思表示。</p>
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