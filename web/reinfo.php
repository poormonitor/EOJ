<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;

require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');

$view_title = $OJ_NAME;

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  header("location:loginpage.php");
  exit(0);
}

require_once("./include/const.inc.php");

if (!isset($_GET['sid'])) {
  $view_swal = "No such code!";
  require_once("./template/error.php");
  exit(0);
}

function is_valid($str2)
{
  global $_SESSION, $OJ_NAME;
  if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) return true;
  //return true; // 如果希望能让任何人都查看对比和RE,放开行首注释，并设定$OJ_SHOW_DIFF=true; if you fail to view diff , try remove the // at beginning of this line.
  $n = strlen($str2);
  $str = str_split($str2);
  $m = 1;
  for ($i = 0; $i < $n; $i++) {
    if (is_numeric($str[$i]))
      $m++;
  }
  return $n / $m > 3;
}

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  $view_swal = $MSG_WARNING_ACCESS_DENIED;
  require("template/error.php");
  exit(0);
}

$ok = false;
$id = strval(intval($_GET['sid']));

$sql = "SELECT * FROM `solution` WHERE `solution_id`=?";
$result = pdo_query($sql, $id);
if (!count($result)) {
  $view_swal = $MSG_NOT_EXISTED;
  require("template/error.php");
  exit(0);
}

$row = $result[0];
$show_info = $row;
$lang = $row['language'];
$pid = $row["problem_id"];
$contest_id = intval($row['contest_id']);
$user_id = $row["user_id"];
$isRE = $row['result'] == 10;
$judge_color = array("label label-info", "label label-info", "label label-warning", "label label-warning", "label label-success", "label label-danger", "label label-danger", "label label-warning", "label label-warning", "label label-warning", "label label-warning", "label label-warning", "label label-warning", "label label-info");

if (
  isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])
  || ($_SESSION[$OJ_NAME . '_' . "allow_view"]
    && $user_id == $_SESSION[$OJ_NAME . '_' . 'user_id'])
) {
  $ok = true;
}

$view_reinfo = "";
if ($ok) {
  $sql = "SELECT `error` FROM `runtimeinfo` WHERE `solution_id`=?";
  $result = pdo_query($sql, $id);

  if (isset($result[0])) {
    $row = $result[0];
  } else {
    $view_swal = $MSG_NOT_EXISTED;
    require("template/error.php");
    exit(0);
  }
  if ($OJ_SHOW_DIFF && $row && ($ok || $isRE) && ($OJ_TEST_RUN || is_valid($row['error']) || $ok)) {
    $view_reinfo = htmlentities(str_replace("\n\r", "\n", $row['error']), ENT_QUOTES, "UTF-8");
  } else {
    $view_swal = $MSG_WARNING_ACCESS_DENIED;
    //$view_reinfo = "出于数据保密原因，当前错误提示不可查看，如果希望能让任何人都查看对比和运行错误,请管理员配置\$OJ_SHOW_DIFF=true;<br>然后编辑本文件，开放18行首注释，令is_valid总是返回true。 <br>\n Sorry , not available (RE:".$isRE.",OJ_SHOW_DIFF:".$OJ_SHOW_DIFF.",TR:".$OJ_TEST_RUN.",valid:".is_valid($row['error']).")";
  }
} else {
  $view_swal = $MSG_WARNING_ACCESS_DENIED;
  require("template/error.php");
  exit(0);
}

if (strstr($view_reinfo, "File too large")) {
  preg_match("#\[(.*)\]\n\nInput\nFile too large#m", $view_reinfo, $matches);
  if ($matches) {
    $file = $matches[1];
    $file = str_replace(".out", ".in", $file);
    if (isset($_SESSION[$OJ_NAME . "_" . "testfile"])) {
      array_push($_SESSION[$OJ_NAME . "_" . "testfile"], "$pid/$file");
    } else {
      $_SESSION[$OJ_NAME . "_" . "testfile"] = array("$pid/$file");
    }
    $file = urlencode($file);
    $a_tag = "<a href='./testfile.php?pid=$pid&name=$file'>Download</a>";
    $view_reinfo = str_replace("File too large", "File too large, $a_tag", $view_reinfo);
  }
}

if (!$OJ_SHOW_DIFF) {
  $view_swal = $MSG_WARNING_ACCESS_DENIED;
  require("template/error.php");
  exit(0);
} else {
  require("template/reinfo.php");
}

if (file_exists('./include/cache_end.php')) {
  require_once('./include/cache_end.php');
}
