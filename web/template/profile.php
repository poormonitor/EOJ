<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("content-type:application/javascript");

require_once("../include/db_info.inc.php");

if (isset($_SESSION[$OJ_NAME . '_' . 'profile_csrf']) && $_GET['profile_csrf'] != $_SESSION[$OJ_NAME . '_' . 'profile_csrf']) {
  //    echo "<!--".$_SESSION[$OJ_NAME.'_'.'profile_csrf']."-->";
  //  exit();
} else {
  $_SESSION[$OJ_NAME . '_' . 'profile_csrf'] = "";
}
if (isset($OJ_LANG)) {
  require_once("../lang/$OJ_LANG.php");
} else {
  require_once("../lang/zh.php");
}

function checkmail()
{
  global $OJ_NAME;

  $sql = "SELECT count(1) FROM `mail` WHERE new_mail=1 AND `to_user`=?";
  $result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);

  if (!$result) return false;

  $row = $result[0];
  $retmsg = "<span id=red>(" . $row[0] . ")</span>";

  return $retmsg;
}

$profile = '';
$path_fix = isset($_GET['loc']) && $_GET['loc'] == "admin" ? "../" : "";

if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  $sid = $_SESSION[$OJ_NAME . '_' . 'user_id'];

  $profile .= "<li><a href=" . $path_fix . "modifypage.php>$MSG_REG_INFO</a></li><li><a href='" . $path_fix . "userinfo.php?user=$sid'><span id=red>$MSG_USERINFO</span></a></li>";
  $profile .= "<li><a href='" . $path_fix . "status.php?user_id=$sid'><span id=red>$MSG_MY_SUBMISSIONS</span></a></li>";
  $profile .= "<li><a href='" . $path_fix . "contest.php?my'><span id=red>$MSG_MY_CONTESTS</span></a></li>";
  $profile .= "<li><a href='" . $path_fix . "clipboard.php'><span id=red>$MSG_CLIPBOARD</span></a></li>";
  if (
    (isset($OJ_EXAM_CONTEST_ID) && $OJ_EXAM_CONTEST_ID > 0) ||
    (isset($OJ_ON_SITE_CONTEST_ID) && $OJ_ON_SITE_CONTEST_ID > 0) ||
    (isset($OJ_SHARE_CODE) && !$OJ_SHARE_CODE)
  ) {
  } else {
    $profile .= "<li><a href='" . $path_fix . "sharecodelist.php'>代码分享</a></li>";
  }
  $profile .= "<li><a href='" . $path_fix . "logout.php'>$MSG_LOGOUT</a></li>";
} else {
  $profile .= "<li><a href='" . $path_fix . "loginpage.php'>$MSG_LOGIN</a></li>";

  if ($OJ_LOGIN_MOD == "eoj") {
    if (isset($OJ_REGISTER) && !$OJ_REGISTER) {
    } else {
      $profile .= "<li><a href='" . $path_fix . "registerpage.php'>$MSG_REGISTER</a></li>";
    }
  }
}

if (isset($_SESSION[$OJ_NAME . '_' . 'balloon'])) {
  $profile .= "<li><a href='" . $path_fix . "balloon.php'>$MSG_BALLOON</a></li>";
}

if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) {
  $profile .= "<li><a href='" . $path_fix . "admin/'>$MSG_ADMIN</a></li>";
}

//$profile.="</ul></li>";
?>
document.write("<?php echo ($profile); ?>");
document.getElementById("profile").innerHTML="<?php echo  isset($sid) ? $sid : $MSG_LOGIN  ?>";