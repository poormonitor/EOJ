<?php
$cache_time = 1;
$OJ_CACHE_SHARE = false;

require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');

$view_title = $OJ_NAME;

require_once("./include/const.inc.php");

if (isset($OJ_OI_MODE) && $OJ_OI_MODE) {
  header("location:index.php");
  exit();
}

if (isset($_GET["cid"])) {
  $cid = intval($_GET["cid"]);
  $num = ord($_GET['id']) - ord("A");
  $sql = "SELECT `problem_id` from `contest_problem` where `contest_id` = ? AND `num` = ?";
  $row = pdo_query($sql, $cid, $num);
  if ($row)
    $id = $row[0][0];
} else {
  $id = intval($_GET['id']);
}

if (!isset($id)) {
  $view_swal = "$MSG_NOT_EXISTED";
  require("template/error.php");
  exit(0);
}

if (isset($_GET['page']))
  $page = strval(intval($_GET['page']));
else
  $page = 0;



$now = date("Y-m-d H:i");

$sql = "SELECT 1 FROM `contest_problem` WHERE `problem_id` = ? AND `contest_id` IN (SELECT `contest_id` FROM `contest` WHERE `start_time`<? AND `end_time`>? AND `title` LIKE ?)";

$rrs = pdo_query($sql, $id, $now, $now, "%$OJ_NOIP_KEYWORD%");

$flag = count($rrs) > 0;

if ($flag) {
  $view_errors = "<h2> $MSG_NOIP_WARNING </h2>";
  require("template/error.php");
  exit(0);
}

$sql = "SELECT * FROM problem WHERE problem_id = ?";
$problem_info = pdo_query($sql, $id)[0];

$view_problem = array();

// total submit
$sql = "SELECT count(*) FROM solution WHERE problem_id=?";
$result = pdo_query($sql, $id);
$row = $result[0];
$view_problem[0][0] = $MSG_SUBMIT;
$view_problem[0][1] = $row[0];
$total = intval($row[0]);

// total users
$sql = "SELECT count(DISTINCT user_id) FROM solution WHERE problem_id=?";
$result = pdo_query($sql, $id);
$row = $result[0];
$view_problem[1][0] = "$MSG_USER($MSG_SUBMIT)";
$view_problem[1][1] = $row[0];

// ac users
$sql = "SELECT count(DISTINCT user_id) FROM solution WHERE problem_id=? AND result='4'";
$result = pdo_query($sql, $id);
$row = $result[0];
$acuser = intval($row[0]);
$view_problem[2][0] = "$MSG_USER($MSG_SOVLED)";
$view_problem[2][1] = $row[0];

//for ($i=4;$i<12;$i++){
$i = 3;
$sql = "SELECT result, count(1) FROM solution WHERE problem_id=? AND result>=4 GROUP BY result ORDER BY result";
$result = pdo_query($sql, $id);

foreach ($result as $row) {
  $view_problem[$i][0] = $jresult[$row[0]];
  $view_problem[$i][1] = "<a href=status.php?problem_id=$id&jresult=" . $row[0] . ">" . $row[1] . "</a>";
  $i++;
}
//}
?>

<?php
$pagemin = 0;
$pagemax = intval(($acuser - 1) / 20);

if ($page < $pagemin)
  $page = $pagemin;

if ($page > $pagemax)
  $page = $pagemax;

$start = $page * 20;
$sz = 20;

if ($start + $sz > $acuser)
  $sz = $acuser - $start;

//check whether the problem in a contest
$now = date("Y-m-d H:i");
$sql = "SELECT 1 FROM `contest_problem` WHERE `problem_id`=$id AND `contest_id` IN (SELECT `contest_id` FROM `contest` WHERE `start_time`<? AND `end_time`>?)";
$rrs = pdo_query($sql, $now, $now);
$flag = count($rrs) == 0 || isset($_SESSION[$OJ_NAME . '_' . 'administrator']);

// check whether the problem is ACed by user
$AC = false;
if (isset($OJ_AUTO_SHARE) && $OJ_AUTO_SHARE && isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  $sql = "SELECT 1 FROM solution WHERE result=4 AND problem_id=? AND user_id=?";
  $rrs = pdo_query($sql, $id, $_SESSION[$OJ_NAME . '_' . 'user_id']);
  $AC = (intval(count($rrs)) > 0);
}

//check whether user has the right of view solutions of this problem
//echo "checking...";
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  if (isset($_SESSION[$OJ_NAME . '_' . 's' . $id])) {
    $AC = true;
    //echo "Yes";
  } else {
    $sql = "SELECT count(1) FROM privilege WHERE user_id=? AND rightstr=?";
    $count = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], "s" . $id);

    if ($count && $count[0][0] > 0) {
      $AC = true;
    } else {
      //echo "not right";
    }
  }
}

$sql = "SELECT * FROM (
  SELECT COUNT(*) att, user_id, min(10000000000000000000 + time * 100000000000 + memory * 100000 + code_length) score
  FROM solution
  WHERE problem_id =? AND result =4
  GROUP BY user_id
  ORDER BY score 
  )c
    inner JOIN (
    SELECT solution_id, user_id, language, 10000000000000000000 + time * 100000000000 + memory * 100000 + code_length score, in_date
    FROM solution 
    WHERE problem_id =? AND result =4  
    ORDER BY score
    )b ON b.user_id=c.user_id AND b.score=c.score ORDER BY c.score, solution_id ASC LIMIT $start, $sz;";

//echo $sql;

$result = pdo_query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
$result = pdo_query($sql, $id, $id);

$view_solution = array();
$j = 0;
$last_user_id = '';
$i = $start + 1;

foreach ($result as $row) {
  if ($row['user_id'] == $last_user_id)
    continue;

  $sscore = strval($row['score']);
  $s_time = intval(substr($sscore, 1, 8));
  $s_memory = intval(substr($sscore, 9, 6));
  $s_cl = intval(substr($sscore, 15, 5));

  $view_solution[$j][0] = $i;
  $view_solution[$j][1] = $row['solution_id'];

  if (intval($row['att']) > 1)
    $view_solution[$j][1] .= "(" . $row['att'] . ")";

  $view_solution[$j][2] = "<a href='userinfo.php?user=" . $row['user_id'] . "'>" . $row['user_id'] . "</a>";

  if ($flag)
    $view_solution[$j][3] = "$s_memory KB";
  else
    $view_solution[$j][3] = "-";

  if ($flag)
    $view_solution[$j][4] = "$s_time ms";
  else
    $view_solution[$j][4] = "-";

  if (!(isset($_SESSION[$OJ_NAME . '_' . 'user_id']) && !strcasecmp($row['user_id'], $_SESSION[$OJ_NAME . '_' . 'user_id']) ||
    isset($_SESSION[$OJ_NAME . '_' . 'source_browser']) || (isset($OJ_AUTO_SHARE) && $OJ_AUTO_SHARE && $AC))) {
    $view_solution[$j][5] = $language_name[$row['language']];
  } else {
    $view_solution[$j][5] = "<a target=_blank href=showsource.php?id=" . $row['solution_id'] . ">" . $language_name[$row['language']] . "</a>";
  }

  if ($flag)
    $view_solution[$j][6] = "$s_cl Bytes";
  else
    $view_solution[$j][6] = "-";

  $view_solution[$j][7] = $row['in_date'];
  $j++;

  $last_user_id = $row['user_id'];
  $i++;
}


$view_recommand = array();

if (isset($_SESSION[$OJ_NAME . '_' . 'user_id']))
  $user_id = ($_SESSION[$OJ_NAME . '_' . 'user_id']);

$sql = "SELECT source FROM problem WHERE problem_id=?";
$result = pdo_query($sql, $id);
$source = $result[0][0];
$source = explode(" ", $source);
$pattern = "source LIKE '%" . join("%' OR source LIKE '%", $source) . "%'";

$sql = "SELECT problem_id FROM problem WHERE $pattern AND problem_id != ? LIMIT 10";
$result = pdo_query($sql, $id);

$i = 0;
foreach ($result as $row) {
  $view_recommand[$i][0] = $row['problem_id'];
  $i++;
}

if ($problem_info["blank"] && isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
  $blank = $problem_info["blank"];
  if (isset($_GET["cid"])) {
    $sql = "SELECT * FROM solution JOIN source_code ON `source_code`.`solution_id` = `solution`.`solution_id` WHERE problem_id = ? AND contest_id = ?";
    $result = pdo_query($sql, $id, $cid);
  } else {
    $sql = "SELECT * FROM solution JOIN source_code ON `source_code`.`solution_id` = `solution`.`solution_id` WHERE problem_id = ?";
    $result = pdo_query($sql, $id);
  }
  $blank_analysis = array();
  $strip_map = array();

  foreach ($result as $row) {

    $view_src = $row["source"];
    $result = $row["result"];
    $c = $result == 4 ? 0 : 1;
    $user_id = $row["user_id"];
    $nick = $row["nick"];
    $user_info = $user_id . "(" . $nick . ")";
    $res = getBlankCodeSingle($blank, $view_src);

    if (!$res)
      continue;

    for ($i = 0; $i < count($res); $i++) {

      if (!$res[$i] || !$res[$i][0] || ctype_space($res[$i][0])) {
        continue;
      }

      $stripped = stripBlank($res[$i]);

      if (!isset($blank_analysis[$i])) {
        $strip_map[$i] = array();
        $blank_analysis[$i] = array(0 => array(), 1 => array());
      }

      if (
        in_array($res[$i], array_keys($blank_analysis[$i][0]))
        || in_array($stripped, array_keys($blank_analysis[$i][0]))
      ) {
        $c = 0;
      }

      $target = $res[$i];
      if (isset($strip_map[$i][$stripped]))
        $target = $strip_map[$i][$stripped];
      if (isset($strip_map[$i][$res[$i]]))
        $target = $strip_map[$i][$res[$i]];
      if (!isset($blank_analysis[$i][$c][$target])) {
        $blank_analysis[$i][$c][$target] = array($user_info);
        $strip_map[$i][$stripped] = $target;
      } else {
        if (!in_array($user_info, $blank_analysis[$i][$c][$target])) {
          array_push($blank_analysis[$i][$c][$target], $user_info);
        }
      }
    }

    for ($i = 0; $i < count($blank_analysis); $i++) {
      $correct = array_keys($blank_analysis[$i][0]);
      foreach ($blank_analysis[$i][1] as $item => $value) {

        if (in_array($item, $correct)) {
          $blank_analysis[$i][0][$item] = array_merge($blank_analysis[$i][0][$item], $value);
          unset($blank_analysis[$i][1][$item]);
        }

        if (isset($strip_map[$i][$item]) && in_array($strip_map[$i][$item], $correct)) {
          $blank_analysis[$i][0][$strip_map[$i][$item]] = array_merge($blank_analysis[$i][0][$strip_map[$i][$item]], $value);
          unset($blank_analysis[$i][1][$item]);
        }

        $stripped = stripBlank($item);
        if (in_array($stripped, $correct)) {
          $blank_analysis[$i][0][$stripped] = array_merge($blank_analysis[$i][0][$stripped], $value);
          unset($blank_analysis[$i][1][$item]);
        }
      }

      foreach ($blank_analysis[$i][0] as $key => $value) {
        $blank_analysis[$i][0][$key] = array_unique($blank_analysis[$i][0][$key]);
      }
    }
  }

  preg_match("/\n.*\*%\*/m", $blank, $matches);
  $len = $matches ? max(strlen($matches[0]) - 4, 0) : 0;
  $blank_show = htmlentities($blank, ENT_QUOTES, "UTF-8");
  $blank_show = str_replace("*%*\r\n", "...\r\n" . str_repeat(" ", $len) . "...\r\n", $blank_show);
  $blank_show = str_replace("%*%", "__________", $blank_show);
}

require("template/problemstatus.php");

if (file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>

