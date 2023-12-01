<?php
require_once("../include/check_post_key.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

//contest_id
$title = $_POST['title'];
$content = $_POST['content'];

$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];

$title = stripslashes($title);
$content = stripslashes($content);

$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br>", $content);
$content = str_replace(",", "&#44;", $content);

$private = isset($_POST['private']) ? ($_POST['private'] === "1" ? "Y" : "N") : 'N';

$sql = "INSERT INTO news(`user_id`,`title`,`content`,`time`,`private`) VALUES(?,?,?,now(),?)";
$news_id = pdo_query($sql, $user_id, $title, $content, $private);

if ($private && isset($_POST["gid"]) && !in_array(-1, $_POST['gid'])) {
  $glist = $_POST['gid'];
  if ($glist) {
    foreach ($glist as $i) {
      $sql = "INSERT INTO `privilege_group`(`gid`,`rightstr`) VALUES (?,?)";
      $result = pdo_query($sql, trim($i), "n$news_id");
    }
  }
}

$ip = getRealIP();
$sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
pdo_query($sql, "n$news_id", $_SESSION[$OJ_NAME . '_' . 'user_id'], "add", $ip);

header("Location: news_list.php");
