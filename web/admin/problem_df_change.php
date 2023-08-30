<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  require_once("../include/check_post_key.php");
} else {
  require_once("../include/check_get_key.php");
}

$plist = "";
sort($_POST['pid']);
foreach ($_POST['pid'] as $i) {
  if ($plist)
    $plist .= ',' . intval($i);
  else
    $plist = $i;
}
//echo "===".$plist;

if (isset($_POST['enable']) && $plist) {
  $sql = "UPDATE `problem` SET defunct='N' WHERE `problem_id` IN ($plist)";
  pdo_query($sql);
} else if (isset($_POST['disable']) && $plist) {
  $sql = "UPDATE `problem` SET defunct='Y' WHERE `problem_id` IN ($plist)";
  pdo_query($sql);
} else {
  $id = intval($_GET['id']);
  $sql = "SELECT `defunct` FROM `problem` WHERE `problem_id`=?";
  $result = pdo_query($sql, $id);

  $row = $result[0];
  $defunct = $row[0];
  echo $defunct;

  if ($defunct == 'Y') $sql = "UPDATE `problem` SET `defunct`='N' WHERE `problem_id`=?";
  else $sql = "UPDATE `problem` SET `defunct`='Y' WHERE `problem_id`=?";
  pdo_query($sql, $id);
}

$page = intval($_GET['page']);
$page = $page ? $page : 1;

$ip = getRealIP();
$sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
pdo_query($sql, "p$id", $_SESSION[$OJ_NAME . '_' . 'user_id'], "df change", $ip);

header("Location: problem_list.php?page=$page");
