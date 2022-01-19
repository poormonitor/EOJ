<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="refresh" content="30">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title>
    <?php echo $OJ_NAME ?>
  </title>

  <?php include("template/$OJ_TEMPLATE/css.php"); ?>
</head>

<body>
  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">

      <?php
      if (isset($_GET['cid'])) {
        $cid = intval($_GET['cid']);
        $view_cid = $cid;
        //print $cid;

        //check contest valid
        $sql = "SELECT * FROM `contest` WHERE `contest_id`=?";
        $result = pdo_query($sql, $cid);

        $rows_cnt = count($result);
        $contest_ok = true;
        $password = "";

        if (isset($_POST['password']))
          $password = $_POST['password'];
        $password = stripslashes($password);

        if ($rows_cnt == 0) {
          $view_title = "比赛已经关闭!";
        } else {
          $row = $result[0];
          $view_private = $row['private'];

          if ($password != "" && $password == $row['password'])
            $_SESSION[$OJ_NAME . '_' . 'c' . $cid] = true;

          if ($row['private'] && !isset($_SESSION[$OJ_NAME . '_' . 'c' . $cid]))
            $contest_ok = false;

          if ($row['defunct'] == 'Y')
            $contest_ok = false;

          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']))
            $contest_ok = true;

          $now = time();
          $start_time = strtotime($row['start_time']);
          $end_time = strtotime($row['end_time']);
          $view_description = $row['description'];
          $view_title = $row['title'];
          $view_start_time = $row['start_time'];
          $view_end_time = $row['end_time'];
        }
      }
      ?>

      <?php if (isset($_GET['cid'])) { ?>
        <center>
          <div>
            <h3><?php echo $MSG_CONTEST_ID ?> : <?php echo $view_cid ?> - <?php echo $view_title ?></h3>
            <p>
              <?php echo $view_description ?>
            </p>
            <br />
            <?php echo $MSG_SERVER_TIME ?> : <span id=nowdate> <?php echo date("Y-m-d H:i:s") ?></span>
            <br />

            <?php if (isset($OJ_RANK_LOCK_PERCENT) && $OJ_RANK_LOCK_PERCENT != 0) { ?>
              Lock Board Time: <?php echo date("Y-m-d H:i:s", $view_lock_time) ?><br />
            <?php } ?>

            <?php if ($now > $end_time) {
              echo "<span class=text-muted>$MSG_Ended</span>";
            } else if ($now < $start_time) {
              echo "<span class=text-success>$MSG_Start&nbsp;</span>";
              echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
            } else {
              echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
              echo "<span class=text-danger>$MSG_LeftTime</span>" . " " . formatTimeLength($end_time - $now);
            }
            ?>

            <br /><br />

            <?php echo $MSG_CONTEST_STATUS ?> :

            <?php
            if ($now > $end_time)
              echo "<span class=text-muted>" . $MSG_End . "</span>";
            else if ($now < $start_time)
              echo "<span class=text-success>" . $MSG_Start . "</span>";
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

            <br />

            <?php echo $MSG_START_TIME ?> : <?php echo $view_start_time ?>
            <br />
            <?php echo $MSG_END_TIME ?> : <?php echo $view_end_time ?>
            <br /><br />

            <div class="btn-group">
              <a href="contest.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_PROBLEMS ?></a>
              <a href="status.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_SUBMIT ?></a>
              <a href="contestrank.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STANDING ?></a>
              <a href="contestrank-oi.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo "OI" . $MSG_STANDING ?></a>
              <a href="conteststatistics.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STATISTICS ?></a>
              <a href="suspect_list.php?cid=<?php echo $view_cid ?>" class="btn btn-warning btn-sm"><?php echo $MSG_IP_VERIFICATION ?></a>
              <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                <a href="user_set_ip.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_SET_LOGIN_IP ?></a>
                <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo "编辑" ?></a>
              <?php } ?>
            </div>
          </div>
        </center>
      <?php } ?>

      <br />
      <div align=center class="input-append">
        <form id=simform class=form-inline action="status.php" method="get">
          <?php echo $MSG_PROBLEM_ID ?>
          <input class="form-control" type=text size=4 name=problem_id value='<?php echo htmlspecialchars($problem_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;

          <?php echo $MSG_USER ?>
          <input class="form-control" type=text size=8 name=user_id value='<?php echo htmlspecialchars($user_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;
          <?php echo "<input type='hidden' name='cid' value='$cid'>"; ?>

          <?php echo $MSG_LANG ?>
          <select class="form-control" size="1" name="language">
            <option value="-1">All</option>
            <?php
            if (isset($_GET['language'])) {
              $selectedLang = intval($_GET['language']);
            } else {
              $selectedLang = -1;
            }

            $lang_count = count($language_ext);
            $langmask = $OJ_LANGMASK;
            $lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);
            for ($i = 0; $i < $lang_count; $i++) {
              if ($lang & (1 << $i))
                echo "<option value=$i " . ($selectedLang == $i ? "selected" : "") . ">" . $language_name[$i] . "</option>";
            }
            ?>
          </select>&nbsp;&nbsp;

          <?php echo $MSG_RESULT ?>
          <select class="form-control" size="1" name="jresult">
            <?php
            if (isset($_GET['jresult']))
              $jresult_get = intval($_GET['jresult']);
            else
              $jresult_get = -1;

            if ($jresult_get >= 12 || $jresult_get < 0)
              $jresult_get = -1;
            /*if ($jresult_get!=-1){
          $sql=$sql."AND `result`='".strval($jresult_get)."' ";
          $str2=$str2."&jresult=".strval($jresult_get);
          }*/
            if ($jresult_get == -1)
              echo "<option value='-1' selected>All</option>";
            else
              echo "<option value='-1'>All</option>";

            for ($j = 0; $j < 12; $j++) {
              $i = ($j + 4) % 12;
              if ($i == $jresult_get)
                echo "<option value='" . strval($jresult_get) . "' selected>" . $jresult[$i] . "</option>";
              else
                echo "<option value='" . strval($i) . "'>" . $jresult[$i] . "</option>";
            }
            ?>
          </select>&nbsp;&nbsp;

          <?php
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) {
            if (isset($_GET['showsim']))
              $showsim = intval($_GET['showsim']);
            else
              $showsim = 0;

            echo "SIM 
          <select id=\"appendedInputButton\" class=\"form-control\" name=showsim onchange=\"document.getElementById('simform').submit();\">
            <option value=0 " . ($showsim == 0 ? 'selected' : '') . ">All</option>
            <option value=50 " . ($showsim == 50 ? 'selected' : '') . ">50</option>
            <option value=60 " . ($showsim == 60 ? 'selected' : '') . ">60</option>
            <option value=70 " . ($showsim == 70 ? 'selected' : '') . ">70</option>
            <option value=80 " . ($showsim == 80 ? 'selected' : '') . ">80</option>
            <option value=90 " . ($showsim == 90 ? 'selected' : '') . ">90</option>
            <option value=100 " . ($showsim == 100 ? 'selected' : '') . ">100</option>
          </select>&nbsp;&nbsp;&nbsp;&nbsp;";

            /* if (isset($_GET['cid']))
          echo "<input type=hidden name=cid value='".$_GET['cid']."'>";
          if (isset($_GET['language']))
            echo "<input type=hidden name=language value='".$_GET['language']."'>";
          if (isset($_GET['user_id']))
            echo "<input type=hidden name=user_id value='".$_GET['user_id']."'>";
          if (isset($_GET['problem_id']))
            echo "<input type=hidden name=problem_id value='".$_GET['problem_id']."'>";
          //echo "<input type=submit>";
          */
          }
          echo "<input type=submit class='form-control' value='$MSG_SEARCH'>";
          if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
            $href = str_replace("status.php", "statusadmin.php", $_SERVER['REQUEST_URI']);
            echo "<a class='btn btn-default' href='$href' role='button'style='margin:5px;' > Admin $MSG_SEARCH</a>";
          }
          ?>
        </form>
        </form>
      </div>
      <br />

      <div id=center class="table-responsive">
        <table id=result-tab class="table table-striped content-box-header" align=center width=80%>
          <thead>
            <tr class='toprow'>
              <td class="text-center">
                <?php echo $MSG_RUNID ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_USER ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_PROBLEM_ID ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_RESULT ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_MEMORY ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_TIME ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_LANG ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_CODE_LENGTH ?>
              </td>
              <td class="text-center">
                <?php echo $MSG_SUBMIT_TIME ?>
              </td>
              <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
                echo "<th class='text-left'>";
                echo $MSG_JUDGER;
                echo "</th>";
              } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $cnt = 0;
            foreach ($view_status as $row) {
              if ($cnt)
                echo "<tr class='oddrow'>";
              else
                echo "<tr class='evenrow'>";

              $i = 0;
              foreach ($row as $table_cell) {
                if ($i == 2 || $i == 8)
                  echo "<td class='text-center'>";
                else if ($i == 0 || $i == 4 || $i == 5 || $i == 6 || $i == 7)
                  echo "<td class='text-right'>";
                else
                  echo "<td>";

                echo $table_cell;
                echo "</td>";
                $i++;
              }

              echo "</tr>\n";
              $cnt = 1 - $cnt;
            }
            ?>
          </tbody>
        </table>
      </div>

      <div align=center id=center>
        <nav id="page" class="center">
          <small>
            <ul class="pagination">
              <?php
              echo "<li class='page-item'> <a href=status.php?" . $str2 . ">&lt;&lt;&nbsp;首页</a></li>";
              if (isset($_GET['prevtop']))
                echo "<li class='page-item'> <a href=status.php?" . $str2 . "&top=" . intval($_GET['prevtop']) . ">&lt&nbsp;上一页</a></li>";
              else
                echo "<li class='page-item'> <a href=status.php?" . $str2 . "&top=" . ($top + 50) . ">&lt&nbsp;上一页</a></li>";

              echo "<li class='page-item'> <a href=status.php?" . $str2 . "&top=" . $bottom . "&prevtop=$top>下一页&nbsp;&gt;</a></li>";
              ?>
            </ul>
          </small>
        </nav>
      </div>

    </div>

  </div>
  <?php include("template/$OJ_TEMPLATE/js.php"); ?>

  <script>
    var judge_result = [<?php
                        foreach ($judge_result as $result) {
                          echo "'$result',";
                        }
                        echo "''"; ?>];

    var judge_color = [<?php
                        foreach ($judge_color as $result) {
                          echo "'$result',";
                        }
                        echo "''"; ?>];

    var diff = new Date("<?php echo date("Y/m/d H:i:s") ?>").getTime() - new Date().getTime();
    clock(diff);

    var i = 0;
    var interval = 800;

    var hj_ss = "<select class='http_judge form-control' length='2' name='result'>";

    for (var i = 0; i < 10; i++) {
      hj_ss += "	<option value='" + i + "'>" + judge_result[i] + " </option>";
    }

    hj_ss += "</select>";
    hj_ss += "<input name='manual' type='hidden'>";
    hj_ss += "<input class='http_judge form-control' size=5 title='输入判定原因与提示' name='explain' type='text'>";
    hj_ss += "<input type='button' class='http_judge btn' name='manual' value='确定' onclick='http_judge(this)' >";

    $(".http_judge_form").append(hj_ss);

    auto_refresh();

    $(".http_judge_form").hide();
  </script>

</body>

</html>