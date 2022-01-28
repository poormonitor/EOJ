<?php
require_once ("admin-header.php");
require_once("../include/check_post_key.php");

require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

//contest_id
$title = $_POST['title'];
$content = $_POST['content'];

$user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];

  $title = stripslashes($title);
  $content = stripslashes($content);

$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br />", $content);
$content = str_replace(",", "&#44;", $content);

$title=RemoveXSS($title);
$content=RemoveXSS($content);

$sql = "INSERT INTO news(`user_id`,`title`,`content`,`time`) VALUES(?,?,?,now())";
$news_id = pdo_query($sql,$user_id,$title,$content)[0][0];

if (!in_array(-1,$_POST['gid'])){
  $privilege = $_POST['gid'];
  $sql = "INSERT INTO privilege (`user_id`,`rightstr`,`valuestr`,`defunct`) SELECT user_id, 'n$news_id','true','N' from users where gid in (?)";
  pdo_query($sql,join(",",$privilege));
}

echo "<script>window.location.href=\"news_list.php\";</script>";
?>
<?php
require_once("admin-footer.php");
?>