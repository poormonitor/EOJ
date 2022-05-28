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
      <?php
      $rank = 1;
      ?>
      <center>
        <h3>比赛排名 -- <?php echo $title ?></h3>
        <h4><?php if (isset($locked_msg)) echo $locked_msg; ?></h4>
        <a href="contestrank.xls.php?cid=<?php echo $cid ?>">下载表格</a>
      </center>
      <br>
      <table id="rank" class="table-hover table-striped" align=center width=80%>
        <thead>
          <tr class=toprow align=center>
            <td class="{sorter:'false'}" width=10% style='margin-right:3px;'>排名
            <th width=10% style='margin-right:3px;'>用户</th>
            <th width=10% style='margin-right:3px;'>昵称</th>
            <th width=10% style='margin-right:3px;'>解决</th>
            <th width=20% style='margin-right:3px;'>罚时</th>
            <?php
            for ($i = 0; $i < $pid_cnt; $i++)
              echo "<td><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
            echo "</tr></thead>\n<tbody>";
            if (false) for ($i = 0; $i < $user_cnt; $i++) {
              if ($i & 1) echo "<tr class=oddrow align=center>\n";
              else echo "<tr class=evenrow align=center>\n";
              echo "<td>";
              $uuid = $U[$i]->user_id;
              $nick = $U[$i]->nick;
              if ($nick[0] != "*")
                echo $rank++;
              else
                echo "*";
              $usolved = $U[$i]->solved;
              if (isset($_GET['user_id']) && $uuid == $_GET['user_id']) echo "<td bgcolor=#ffff77>";
              else echo "<td>";
              echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
              echo "<td><a href=userinfo.php?user=$uuid>" . htmlentities($U[$i]->nick, ENT_QUOTES, "UTF-8") . "</a>";
              echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
              echo "<td>" .  sec2str($U[$i]->time);
              for ($j = 0; $j < $pid_cnt; $j++) {
                $bg_color = "eeeeee";
                if (isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j] > 0) {
                  $aa = 0x33 + $U[$i]->p_wa_num[$j] * 32;
                  $aa = $aa > 0xaa ? 0xaa : $aa;
                  $aa = dechex($aa);
                  $bg_color = "$aa" . "ff" . "$aa";
                  //$bg_color="aaffaa";
                  if ($uuid == $first_blood[$j]) {
                    $bg_color = "aaaaff";
                  }
                } else if (isset($U[$i]->p_wa_num[$j]) && $U[$i]->p_wa_num[$j] > 0) {
                  $aa = 0xaa - $U[$i]->p_wa_num[$j] * 10;
                  $aa = $aa > 16 ? $aa : 16;
                  $aa = dechex($aa);
                  $bg_color = "ff$aa$aa";
                }
                echo "<td class=well style='background-color:#$bg_color;'>";
                if (isset($U[$i])) {
                  if (isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j] > 0)
                    echo sec2str($U[$i]->p_ac_sec[$j]);
                  if (isset($U[$i]->p_wa_num[$j]) && $U[$i]->p_wa_num[$j] > 0)
                    echo "(-" . $U[$i]->p_wa_num[$j] . ")";
                }
              }
              echo "</tr>\n";
            }
            echo "</tbody></table>";
            ?>
    </div>

  </div>
  <?php include("template/js.php"); ?>
  <script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/jquery.tablesorter.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $.tablesorter.addParser({
        // set a unique id
        id: 'punish',
        is: function(s) {
          // return false so this parser is not auto detected
          return false;
        },
        format: function(s) {
          // format your data for normalization
          var v = s.toLowerCase().replace(/\:/, '').replace(/\:/, '').replace(/\(-/, '.').replace(/\)/, '');
          //alert(v);
          v = parseFloat('0' + v);
          return v > 1 ? v : v + Number.MAX_VALUE - 1;
        },
        // set type, either numeric or text
        type: 'numeric'
      });
      $("#rank").tablesorter({
        headers: {
          4: {
            sorter: 'punish'
          }
          <?php
          for ($i = 0; $i < $pid_cnt; $i++) {
            echo "," . ($i + 5) . ": { ";
            echo " sorter:'punish' ";
            echo "}";
          }
          ?>,
        }
      });
    });

    function metal() {
      var tb = window.document.getElementById('rank');
      var rows = tb.rows;
      try {
        var total = getTotal(rows);
        //alert(total);
        for (var i = 1; i < rows.length; i++) {
          var cell = rows[i].cells[0];
          var acc = rows[i].cells[3];
          var ac = parseInt(acc.innerHTML);
          if (isNaN(ac)) ac = parseInt(acc.textContent);


          if (cell.innerHTML != "*" && ac > 0) {

            var r = i;
            if (r == 1) {
              cell.innerHTML = "Winner";
              //cell.style.cssText="background-color:gold;color:red";
              cell.className = "badge btn-warning";
            } else {
              cell.innerHTML = r;
            }
            if (r > 1 && r <= total * .05 + 1)
              cell.className = "badge btn-warning";
            if (r > total * .05 + 1 && r <= total * .20 + 1)
              cell.className = "badge";
            if (r > total * .20 + 1 && r <= total * .45 + 1)
              cell.className = "badge btn-danger";
            if (r > total * .45 + 1 && ac > 0)
              cell.className = "badge badge-info";
          }
        }
      } catch (e) {
        alert(e);
      }
    }

    <?php if ($OJ_SHOW_METAL) { ?>
      metal();
    <?php } ?>
    replay();
    <?php if (isset($solution_json)) echo "var solutions=$solution_json;" ?>
    var replay_index = 0;


    function add() {
      if (replay_index >= solutions.length) return metal();
      var solution = solutions[replay_index];
      var tab = $("#rank");
      var row = findrow(tab, solution);
      if (row == null)
        tab.append(newrow(tab, solution));
      row = findrow(tab, solution);
      update(tab, row, solution);
      replay_index++;
      sort(tab[0].rows);
      <?php if ($OJ_SHOW_METAL) { ?>
        metal();
      <?php } ?>
      window.setTimeout("add()", 5);
    }
  </script>
  <style>
    #rank,
    .header {
      text-align: center;
    }

    .well {
      background-image: none;
      padding: 1px;
    }

    td {
      white-space: nowrap;
    }

    .red {
      background-color: #ffa0a0;
    }

    .green {
      background-color: #33ff33;
    }
  </style>
</body>

</html>