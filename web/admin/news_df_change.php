<?php 
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/check_get_key.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
    $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
    $error_location = "../index.php";
    require("../template/error.php");
    exit(0);
}

$id = intval($_GET['id']);
$page = intval($_GET['page']);
$page = $page ? $page : 1;

$sql = "SELECT `defunct` FROM `news` WHERE `news_id`=?";
$result = pdo_query($sql, $id);
$row = $result[0];
$defunct = $row[0];

if ($defunct == 'Y') $sql = "update `news` set `defunct`='N' where `news_id`=?";
else $sql = "update `news` set `defunct`='Y' where `news_id`=?";
pdo_query($sql, $id);

$ip = getRealIP();
$sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
pdo_query($sql, "n$id", $_SESSION[$OJ_NAME . '_' . 'user_id'], "df change", $ip);

header("Location: news_list.php?page=$page");