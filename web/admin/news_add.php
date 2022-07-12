<?php
require_once("../include/check_post_key.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

//contest_id
$title = $_POST['title'];
$content = $_POST['content'];

$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];

$title = stripslashes($title);
$content = stripslashes($content);

$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br>", $content);
$content = str_replace(",", "&#44;", $content);

$sql = "INSERT INTO news(`user_id`,`title`,`content`,`time`) VALUES(?,?,?,now())";
$news_id = pdo_query($sql, $user_id, $title, $content)[0][0];

if (!in_array(-1, $_POST['gid'])) {
  $privilege = $_POST['gid'];
  $sql = "INSERT INTO privilege (`user_id`,`rightstr`,`valuestr`,`defunct`) SELECT user_id, 'n$news_id','true','N' from users where gid in (?)";
  pdo_query($sql, join(",", $privilege));
}

require_once("admin-header.php");
?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">
  <?php include("../template/css.php"); ?>
  <title><?php echo $OJ_NAME ?></title>
</head>

<body>
  <div class='container'>
    <?php include("../template/nav.php") ?>
    <div class='jumbotron'>
      <div class='row lg-container'>
        <?php require_once("sidebar.php") ?>
        <div class='col-md-10'>
          <script>
            window.location.href = 'news_list.php';
          </script>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>