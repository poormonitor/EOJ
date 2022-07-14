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
      <div class='main-container'>
        <h2><?php echo $MSG_PARENT_SEARCH ?></h2>
        <br><br>
        <table align=center width=80%>
          <tr align='center'>
            <td>
              <form method="get" action="parent.php" class="form-inline vertical-center" <?php if (!isset($_SESSION[$OJ_NAME . '_' . "administrator"])) echo "onsubmit='return vcode_required(this)';" ?>>
                <input class='form-control' id='parent_user' name='user' placeholder='<?php echo $MSG_ID_OR_NICK ?>' value="<?php if (isset($_GET["user"])) echo (htmlentities($_GET['user'])); ?>">
                <input class='form-control' id='vcode' name='vcode' type='hidden'>
                <button class='form-control' type='submit'><?php echo $MSG_PARENT_SEARCH ?></button>
              </form>
            </td>
          </tr>
        </table>
        <br><br>
        <?php if (!isset($no_query)) { ?>
          <?php if (count($user) == 0) { ?>
            <h2 class="text-center"><?php echo $MSG_NOT_FOUND ?></h2><br>
            <?php } elseif (is_array($user)) {
            if (count($user) != 1) {
            ?>
              <div class='table-responsive'>
                <h3><?php echo $MSG_MULTIPLE_USER_CHOICE ?></h3><br>
                <?php for ($i = 0; $i < count($user); $i++) {
                  $uid = $user[$i][0]; ?>
                  <table class='table'>
                    <tbody>
                      <tr>
                        <td><a href='parent.php?user=<?php echo $uid ?>'><?php echo $MSG_STUDENT_ID ?>: <?php echo $uid ?></a></td>
                        <td>
                          <?php echo $MSG_STUDENT_NAME ?>: <?php echo $nick[$i][0] ?>
                        </td>
                        <td>
                          <?php echo $MSG_STUDENT_ADMINISTRATIVE_CLASS ?>: <?php echo $school[$i][0] ?>
                        </td>
                        <td>
                          <?php echo $MSG_STUDENT_TEACHING_CLASS ?>: <?php echo $group[$i][0] ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </div>
          <?php }
              } ?>

        <?php
          } else {
            $nick = $nick[0];
            $school = $school[0];
            $group = $group[0];
        ?>
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
                  $cid = $i[0];
                  $contests_name = $i[1];
                  $end_time = $i[2];
                  $problem_id = $i[3];
                ?>
                  <tr>
                    <td><a href='contest.php?cid=<?php echo $cid ?>'><?php echo $contests_name ?></a></td>
                    <td><?php echo $end_time ?></td>
                    <td>
                      <table class='table-condensed'>
                        <tbody>
                          <?php
                          foreach ($problem_id as $i) {
                            $pid = $i[0];
                          ?>
                            <tr>
                              <td><a href='problem.php?id=<?php echo $pid ?>'><?php echo $pid ?></td>
                            </tr>
                          <?php
                          }
                          ?>
                        </tbody>
                      </table>
                    </td>
                    <td>
                      <table class='table-condensed'>
                        <tbody>
                          <?php
                          foreach ($problem_id as $i) {
                            $pid = $i[0];
                            $before_ac = $i[1];
                            $before = $i[2]; ?>
                            <tr>
                              <?php
                              if ($before == 0) {
                              ?>
                                <td><span class='label label-danger'><?php echo $MSG_UNFINISHED ?></span></td>
                                <?php
                              } else {
                                if ($before_ac == 0) { ?>
                                  <td><span class='label label-warning'><?php echo $MSG_NOT_PASS ?></span></td>
                                <?php
                                } else { ?>
                                  <td><span class='label label-success'><?php echo $MSG_FINISHED ?></span></td>
                              <?php
                                }
                              } ?>
                            </tr>
                          <?php
                          } ?>
                        </tbody>
                      </table>
                    </td>
                    <td>
                      <table class='table-condensed'>
                        <tbody>
                          <?php
                          foreach ($problem_id as $i) {
                            $pid = $i[0];
                            $after_ac = $i[3];
                            $after = $i[4];
                          ?>
                            <tr>
                              <?php
                              if ($after == 0) { ?>
                                <td><span class='label label-danger'><?php echo $MSG_UNFINISHED ?></span></td>
                                <?php
                              } else {
                                if ($after_ac == 0) { ?>
                                  <td><span class='label label-warning'><?php echo $MSG_NOT_PASS ?></span></td>
                                <?php
                                } else { ?>
                                  <td><span class='label label-success'><?php echo $MSG_FINISHED ?></span></td>
                              <?php
                                }
                              } ?>
                            </tr>
                          <?php
                          } ?>
                        </tbody>
                      </table>
                    </td>
                    <td>
                      <table class='table-condensed'>
                        <?php
                        foreach ($problem_id as $i) {
                          $problem_id = $i[0];
                          $after_ac = $i[3];
                        ?>
                          <tr>
                            <?php
                            if ($after_ac == 0) { ?>
                              <td> --- </td>
                              <?php
                            } else {
                              $count = $i[5];
                              if ($count != 0) { ?>
                                <td>
                                  <a href='status.php?user_id=<?php echo $user ?>&showsim=80&problem_id=<?php echo $problem_id ?>' class='label label-warning'>
                                    <?php echo $MSG_SIM_YES ?>
                                  </a>
                                </td>
                              <?php
                              } else { ?>
                                <td><span class='label label-success'><?php echo $MSG_SIM_NO ?></span></td>
                          <?php
                              }
                            }
                          } ?>
                          </tr>
                      </table>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php echo $MSG_PARENT_EXPLAIN ?>
          <?php } ?>
        <?php } ?>
          </div>
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
  <script>
    <?php if ($view_error) { ?>
      swal('<?php echo $view_error; ?>');
    <?php } ?>
  </script>
</body>

</html>