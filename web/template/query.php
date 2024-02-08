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
        <h2><?php echo $MSG_USER_QUERY ?></h2>
        <br><br>
        <table align=center width=80%>
          <tr align='center'>
            <td>
              <form method="get" action="query.php" class="form-inline" <?php if (!isset($_SESSION[$OJ_NAME . '_' . "administrator"])) echo "onsubmit='return vcode_required(this)';" ?>>
                <input class='form-control' id='query_user' name='user' placeholder='<?php echo $MSG_ID_OR_NICK ?>' value="<?php if (isset($_GET["user"])) echo (htmlentities($_GET['user'])); ?>">
                <input class='form-control' id='vcode' name='vcode' type='hidden'>
                <button class='form-control' type='submit'><?php echo $MSG_USER_QUERY ?></button>
              </form>
            </td>
          </tr>
        </table>
        <br><br>
        <?php if (count($users) === 0) { ?>
          <h2 class="text-center"><?php echo $MSG_NOT_FOUND ?></h2><br>
        <?php } elseif (count($users) > 1) { ?>
          <div class='table-responsive'>
            <h3><?php echo $MSG_MULTIPLE_USER_CHOICE ?></h3>
            <table class='table'>
              <tbody>
                <?php for ($i = 0; $i < count($users); $i++) { ?>
                  <?php $uid = $users[$i][0]; ?>
                  <tr>
                    <td><a href='query.php?user=<?php echo $uid ?>'><?php echo $MSG_USER ?>: <?php echo $uid ?></a></td>
                    <td>
                      <?php echo $MSG_NICK ?>: <?php echo $users[$i][2] ?>
                    </td>
                    <td>
                      <?php echo $MSG_SCHOOL ?>: <?php echo $users[$i][3] ?>
                    </td>
                    <td>
                      <?php echo $MSG_GROUP ?>: <?php echo $users[$i][1] ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } else { ?>
          <div class="table-responsive">
            <table class='table'>
              <thead class='toprow'>
                <tr>
                  <th>
                    <?php echo $MSG_USER ?>
                  </th>
                  <th>
                    <?php echo $MSG_NICK ?>
                  </th>
                  <th>
                    <?php echo $MSG_SCHOOL ?>
                  </th>
                  <th>
                    <?php echo $MSG_GROUP ?>
                  </th>
                </tr>
              </thead>
              <tbody>
                <td>
                  <?php echo $user; ?>
                </td>
                <td>
                  <?php echo $nick; ?>
                </td>
                <td>
                  <?php echo $school; ?>
                </td>
                <td>
                  <?php echo $group; ?>
                </td>
              </tbody>
            </table>
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
          </div>
        <?php } ?>
        <br>
        <?php echo $MSG_QUERY_EXPLAIN ?>
      </div>
    </div>
  </div>
  <?php include("template/js.php"); ?>
  <script>
    var watermark_config_query = {
      contentType: 'multi-line-text',
      content: `<?php echo $info ?>`,
      fontSize: '16px',
      fontFamily: "HarmonySans",
      lineHeight: 16,
      globalAlpha: 0.05,
      rotate: 45,
      width: 180,
      height: 180,
    }
    if (isDarkMode) watermark_config_query.fontColor = '#fff'
    watermark.changeOptions(watermark_config_query);
  </script>
</body>

</html>