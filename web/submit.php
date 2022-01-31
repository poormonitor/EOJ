<?php
session_start();
require_once "include/db_info.inc.php";
require_once "include/my_func.inc.php";

if (isset($OJ_CSRF) && $OJ_CSRF && !isset($_SESSION[$OJ_NAME . '_' . 'http_judge']))
  require_once(dirname(__FILE__) . "/include/csrf_check.php");

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  require_once "oj-header.php";
  echo "<a href='loginpage.php'>$MSG_Login</a>";
  require_once "oj-footer.php";
  exit(0);
}

require_once "include/memcache.php";
require_once "include/const.inc.php";

$now = strftime("%Y-%m-%d %H:%M", time());
$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
$language = intval($_POST['language']);

if (!$OJ_BENCHMARK_MODE) {
  $sql = "SELECT count(1) FROM `solution` WHERE result<4";
  $result = mysql_query_cache($sql);
  $row = $result[0];

  if ($row[0] > 50) {
    $OJ_VCODE = true;
  }

  if ($OJ_VCODE) {
    $vcode = $_POST["vcode"];
  }

  $err_str = "";
  $err_cnt = 0;

  if ($OJ_VCODE && ($_SESSION[$OJ_NAME . '_' . "vcode"] == null || $vcode != $_SESSION[$OJ_NAME . '_' . "vcode"] || $vcode == "" || $vcode == null)) {
    $_SESSION[$OJ_NAME . '_' . "vcode"] = null;
    $err_str = $err_str . $MSG_VCODE_WRONG;
    $err_cnt++;
    $view_errors = "<h3>" . $err_str . "</h3>";
    $view_errors_js .= "swal('$MSG_VCODE_WRONG！').then((onConfirm)=>{history.go(-1);});";
    if (isset($_GET['ajax'])) {
      echo "-1";
    } else {
      require "template/error.php";
    }
    exit(0);
  }
}

if (isset($_POST['cid'])) {
  $pid = intval($_POST['pid']);
  $cid = abs(intval($_POST['cid']));

  $sql = "SELECT `problem_id`,'N' FROM `contest_problem` WHERE `num`='$pid' AND contest_id=$cid";
} else {
  $id = intval($_POST['id']);
  $sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id`='$id' ";

  if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator']))
    $sql .= " and defunct='N'";
}
//echo $sql;

$res = mysql_query_cache($sql);
if (isset($res) && count($res) < 1 && !isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && !((isset($cid) && $cid <= 0) || (isset($id) && $id <= 0))) {
  $view_errors = $MSG_LINK_ERROR . "<br />";
  require "template/error.php";
  exit(0);
}

if (false && $res[0][1] != 'N' && !isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
  //  echo "res:$res,count:".count($res);
  //  echo "$sql";
  $view_errors = $MSG_PROBLEM_RESERVED . "<br />";

  if (isset($_POST['ajax'])) {
    echo $view_errors;
  } else {
    require "template/error.php";
  }
  exit(0);
}

$test_run = false;

$title = "";

if (isset($_POST['id'])) {
  $id = intval($_POST['id']);
  $test_run = $id <= 0;
  $langmask = $OJ_LANGMASK;
} else if (isset($_POST['pid']) && isset($_POST['cid']) && $_POST['cid'] != 0) {
  $pid = intval($_POST['pid']);
  $cid = intval($_POST['cid']);
  $test_run = $cid < 0;

  if ($test_run) {
    $cid = -$cid;
  }

  //check user if private
  $sql = "SELECT `private`,langmask,title FROM `contest` WHERE `contest_id`=$cid AND `start_time`<='$now' AND `end_time`>'$now'";
  //"SELECT `private`,langmask FROM `contest` WHERE `contest_id`=? AND `start_time`<=? AND `end_time`>?";
  //$result = pdo_query($sql, $cid, $now, $now);

  $result = mysql_query_cache($sql);
  $rows_cnt = count($result);

  if ($rows_cnt != 1) {
    $view_errors .= $MSG_NOT_IN_CONTEST;

    require "template/error.php";
    exit(0);
  } else {
    $row = $result[0];
    $isprivate = intval($row[0]);
    $langmask = $row[1];
    $title = $row[2];

    if ($isprivate == 1 && !isset($_SESSION[$OJ_NAME . '_' . 'c' . $cid])) {
      $sql = "SELECT count(*) FROM `privilege` WHERE `user_id`=? AND `rightstr`=?";
      $result = pdo_query($sql, $user_id, "c$cid");

      $row = $result[0];
      $ccnt = intval($row[0]);

      if ($ccnt == 0 && !isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
        $view_errors = $MSG_NOT_INVITED . "\n";
        require "template/error.php";
        exit(0);
      }
    }
  }

  $sql = "SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? AND `num`=?";
  $result = pdo_query($sql, $cid, $pid);

  $rows_cnt = count($result);

  if ($rows_cnt != 1) {
    $view_errors = $MSG_NO_PROBLEM . "\n";
    require "template/error.php";
    exit(0);
  } else {
    $row = $result[0];
    $id = intval($row['problem_id']);

    if ($test_run) {
      $id = -$id;
    }
  }
} else {
  $id = 0;
  /*
  $view_errors= "No Such Problem!\n";
  require("template/error.php");
  exit(0);
  */
  $langmask = $OJ_LANGMASK;
  $test_run = true;
}

if ($language > count($language_name) || $language < 0) {
  $language = 0;
}

$language = strval($language);

if ($langmask & (1 << $language)) {
  $view_errors = $MSG_NO_PLS . "\n[$language][$langmask][" . ($langmask & (1 << $language)) . "]";
  require "template/error.php";
  exit(0);
}

$c_pid = $id;
if ($c_pid < 0) {
  $c_pid = -$c_pid;
}
$code = pdo_query("select blank from problem where problem_id=?", $c_pid);
if (count($code) != 0) {
  $code = $code[0][0];
  if (isset($_POST['code0']) || isset($_POST['multiline0'])) {
    $code = str_replace("\t", "    ", $code);
    for ($i = 0; isset($_POST['code' . $i]); $i++) {
      $code = str_replace_limit("%*%", $_POST['code' . $i], $code, 1);
    }
    for ($i = 0; isset($_POST['multiline' . $i]); $i++) {
      preg_match("/\n.*\*%\*/m", $code, $matches);
      $len = strlen($matches[0]) - 4;
      $multiline = str_replace("\n", str_repeat(" ", $len), $_POST['multiline' . $i]);
      $code = str_replace_limit("*%*", $multiline, $code, 1);
    }
    $source = $code;
    $input_text = $code;
  } else {
    $code = preg_quote($code);
    $code = str_replace("%\*%", ".*", $code);
    $code = str_replace("\*%\*", "[\s\S]*", $code);
    if (preg_match("#" . $code . "#", $_POST['source'])) {
      $source = $_POST['source'];
      $input_text = "";
    } else {
      $err_str = $err_str . "您的代码不符合填空格式！";
      $err_cnt++;
      $view_error_title = $err_str;
      $view_errors_js .= "swal('$err_str').then((onConfirm)=>{history.go(-1);});";
      if (isset($_GET['ajax'])) {
        echo "-2";
      } else {
        require "template/error.php";
      }
      exit(0);
    }
  }
} else {
  $source = $_POST['source'];
  $input_text = "";
}

$row = pdo_query('select allow,block from problem where problem_id=?', $c_pid)[0];
$allow = $row[0];
$block = $row[1];

$flag2 = True;
$flag1 = True;

if ($allow != NULL) {
  foreach (explode(" ", $allow) as $i) {
    if (substr_count($i, "||") != 0) {
      $temp_flag = False;
      foreach (explode("||", $i) as $j) {
        if (substr_count($source, $j) != 0) {
          $temp_flag = True;
        }
      }
      if (!$temp_flag) {
        $flag1 = False;
      }
    } else {
      if (substr_count($source, $i) == 0) {
        $flag1 = False;
      }
    }
  }
}

if ($block != NULL) {
  foreach (explode(" ", $block) as $i) {
    if (substr_count($i, "||") != 0) {
      $temp_flag = False;
      foreach (explode($i, "||") as $j) {
        if (substr_count($source, $j) == 0) {
          $temp_flag = True;
        }
      }
      if (!$temp_flag) {
        $flag2 = False;
      }
    } else {
      if (substr_count($source, $i) != 0) {
        $flag2 = False;
      }
    }
  }
}

if (!$flag1 or !$flag2) {
  $err_str = $err_str . "代码中有禁用的关键词或没有使用必须的关键词！";
  $err_cnt++;
  $view_error_title = $err_str;
  $view_errors_js .= "swal('$err_str').then((onConfirm)=>{history.go(-1);});";
  if (isset($_GET['ajax'])) {
    echo "-3";
  } else {
    require "template/error.php";
  }
  exit(0);
}
unset($c_pid);

if (isset($_POST['input_text'])) {
  $input_text = $_POST['input_text'];
}


if (isset($_POST['encoded_submit'])) {
  $source = base64_decode($source);
}

$input_text = preg_replace("(\r\n)", "\n", $input_text);
$source_user = $source;

if ($test_run) {
  $id = -$id;
}

//use append Main code
$prepend_file = "$OJ_DATA/$id/prepend." . $language_ext[$language];

if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file)) {
  $source = file_get_contents($prepend_file) . "\n" . $source;
}

$append_file = "$OJ_DATA/$id/append." . $language_ext[$language];
//echo $append_file;

if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file)) {
  $source .= "\n" . file_get_contents($append_file);
  //echo "$source";
}
//end of append

if ($language == 6) {
  $source = "# coding=utf-8\n" . $source;
}

if ($test_run) {
  $id = 0;
}

$len = strlen($source);
//echo $source;

setcookie('lastlang', $language, time() + 360000);

$ip = $_SERVER['REMOTE_ADDR'];

if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
  $tmp_ip = explode(',', $REMOTE_ADDR);
  $ip = htmlentities($tmp_ip[0], ENT_QUOTES, "UTF-8");
}

if ($len < 2) {
  $view_errors = $MSG_TOO_SHORT . "<br />";
  require "template/error.php";
  exit(0);
}

if ($len > 65536) {
  $view_errors = $MSG_TOO_LONG . "<br />";
  require "template/error.php";
  exit(0);
}

if (!$OJ_BENCHMARK_MODE) {
  // last submit
  $now = strftime("%Y-%m-%d %X", time() - 10);
  $sql = "SELECT `in_date` FROM `solution` WHERE `user_id`=? AND in_date>? ORDER BY `in_date` DESC LIMIT 1";
  $res = pdo_query($sql, $user_id, $now);

  if (count($res) == 1) {
    $view_swal = $MSG_BREAK_TIME;
    require "template/error.php";
    exit(0);
  }
}

if (~$OJ_LANGMASK & (1 << $language)) {
  $sql = "SELECT nick FROM users WHERE user_id=?";
  $nick = pdo_query($sql, $user_id);

  if ($nick) {
    $nick = $nick[0][0];
  } else {
    $nick = "Guest";
  }

  if (!isset($pid)) {
    $sql = "INSERT INTO solution(problem_id,user_id,nick,in_date,language,ip,code_length,result) VALUES(?,?,?,NOW(),?,?,?,14)";
    $insert_id = pdo_query($sql, $id, $user_id, $nick, $language, $ip, $len);
  } else {
    $sql = "INSERT INTO solution(problem_id,user_id,nick,in_date,language,ip,code_length,contest_id,num,result) VALUES(?,?,?,NOW(),?,?,?,?,?,14)";

    if ((stripos($title, $OJ_NOIP_KEYWORD) !== false) && isset($OJ_OI_1_SOLUTION_ONLY) && $OJ_OI_1_SOLUTION_ONLY) {
      $delete = pdo_query("DELETE FROM solution WHERE contest_id=? AND user_id=? AND num=?", $cid, $user_id, $pid);

      if ($delete > 0) {
        $sql_fix = "UPDATE problem p INNER JOIN (SELECT problem_id pid ,count(1) ac FROM solution WHERE problem_id=? AND result=4) s ON p.problem_id=s.pid SET p.accepted=s.ac;";
        $fixed = pdo_query($sql_fix, $id);
        $sql_fix = "UPDATE problem p INNER JOIN (SELECT problem_id pid ,count(1) submit FROM solution WHERE problem_id=?) s ON p.problem_id=s.pid SET p.submit=s.submit;";
        $fixed = pdo_query($sql_fix, $id);
      }
    }

    $insert_id = pdo_query($sql, $id, $user_id, $nick, $language, $ip, $len, $cid, $pid);
  }

  $sql = "INSERT INTO `source_code_user`(`solution_id`,`source`) VALUES(?,?)";
  pdo_query($sql, $insert_id, $source_user);

  $sql = "INSERT INTO `source_code`(`solution_id`,`source`) VALUES(?,?)";
  pdo_query($sql, $insert_id, $source);

  if ($test_run) {
    $sql = "INSERT INTO `custominput`(`solution_id`,`input_text`) VALUES(?,?)";
    pdo_query($sql, $insert_id, $input_text);
  } else {
    $sql = "UPDATE problem SET submit=submit+1 WHERE problem_id=?";
    pdo_query($sql, $id);

    if (isset($cid) && $cid > 0) {
      $sql = "UPDATE contest_problem SET c_submit=c_submit+1 WHERE contest_id=? AND num=?";
      pdo_query($sql, $cid, $pid);
    }
  }

  $sql = "UPDATE solution SET result=0 WHERE solution_id=?";
  pdo_query($sql, $insert_id);

  //using redis task queue
  if ($OJ_REDIS) {
    $redis = new Redis();
    $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);

    if (isset($OJ_REDISAUTH)) {
      $redis->auth($OJ_REDISAUTH);
    }

    $redis->lpush($OJ_REDISQNAME, $insert_id);
    $redis->close();
  }
}

if (isset($OJ_UDP) && $OJ_UDP) {
  $JUDGE_SERVERS = explode(",", $OJ_UDPSERVER);
  $JUDGE_TOTAL = count($JUDGE_SERVERS);

  $select = $insert_id % $JUDGE_TOTAL;
  $JUDGE_HOST = $JUDGE_SERVERS[$select];

  if (strstr($JUDGE_HOST, ":") !== false) {
    $JUDGE_SERVERS = explode(":", $JUDGE_HOST);
    $JUDGE_HOST = $JUDGE_SERVERS[0];
    $OJ_UDPPORT = $JUDGE_SERVERS[1];
  }

  send_udp_message($JUDGE_HOST, $OJ_UDPPORT, $insert_id);
}

if ((!isset($_GET['ajax']))) { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset='utf-8'>
    <title><?php echo $MSG_SUBMIT ?></title>
  </head>
  <style>
    @media(prefers-color-scheme: dark) {
      body {
        height: auto;
        background: #242424;
      }
    }
  </style>

  <body></body>

  </html>

  <?php }

if ($OJ_BENCHMARK_MODE) {
  echo $insert_id;
  exit(0);
}

$statusURI = strstr($_SERVER['REQUEST_URI'], "submit", true) . "status.php";

if (isset($cid)) {
  $statusURI .= "?cid=$cid";
}

$sid = "";
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  $sid .= session_id() . $_SERVER['REMOTE_ADDR'];
}

if (isset($_SERVER["REQUEST_URI"])) {
  $sid .= $statusURI;
}
//echo $statusURI."<br />";

$sid = md5($sid);
$file = "cache/cache_$sid.html";
//echo $file;

if ($OJ_MEMCACHE) {
  $mem = new Memcached();
  $mem->addServer($OJ_MEMSERVER,  $OJ_MEMPORT);

  $mem->delete($file, 0);
} elseif (file_exists($file)) {
  unlink($file);
}
//echo $file;

$statusURI = "status.php?user_id=" . $_SESSION[$OJ_NAME . '_' . 'user_id'];

if (isset($cid)) {
  $statusURI .= "&cid=$cid&fixed=";
}

if (!$test_run) {
  header("Location: $statusURI");
} else {
  if (isset($_GET['ajax'])) {
    echo $insert_id;
  } else {
  ?>
    <script>
      window.parent.setTimeout("fresh_result('<?php echo $insert_id; ?>')", 1000);
    </script>
<?php
  }
} ?>