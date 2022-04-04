<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;

require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');

$view_title = "Welcome To Online Judge";

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  header("location:loginpage.php");
  exit(0);
}

require_once("./include/const.inc.php");

if (!isset($_GET['sid'])) {
  echo "No such code!\n";
  require_once("oj-footer.php");
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
$lang = $row['language'];
$contest_id = intval($row['contest_id']);
$isRE = $row['result'] == 10;
$allow = pdo_query("select `group`.allow_view from `group` join users on users.gid=`group`.gid where users.user_id=?", $_SESSION[$OJ_NAME . '_' . 'user_id']);
$allow = isset($allow[0]) ? $allow[0]['allow_view'] : "N";
if ($allow == "Y") {
  $result = pdo_query("select user_id from solution where solution_id=?", $id)[0][0];
  if ($result != $_SESSION[$OJ_NAME . '_' . 'user_id']) {
    $allow = "N";
  }
}
if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser']) or $allow == "Y") {
  $ok = true;
}

$view_reinfo = "";
if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser']) or $allow == "Y") {

  if ($row['user_id'] != $_SESSION[$OJ_NAME . '_' . 'user_id']) {
    $view_mail_link = "<a href='mail.php?to_user=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "&title=$MSG_SUBMIT $id'>Mail the auther</a>";
  }
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



if ($OJ_SHOW_DIFF == false) {
  $view_swal = $MSG_WARNING_ACCESS_DENIED;
  require("template/error.php");
  exit(0);
} else {
  require("template/reinfo.php");
}

if (file_exists('./include/cache_end.php')) {
  require_once('./include/cache_end.php');
}
