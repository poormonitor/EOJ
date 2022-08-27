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

$sql = "INSERT INTO news(`user_id`,`title`,`content`,`time`) VALUES(?,?,?,now())";
$news_id = pdo_query($sql, $user_id, $title, $content)[0][0];

if (!in_array(-1, $_POST['gid'])) {
  $glist = $_POST['gid'];
  if ($glist) {
    foreach ($glist as $i) {
      $sql = "INSERT INTO `privilege_group`(`gid`,`rightstr`) VALUES (?,?)";
      $result = pdo_query($sql, trim($i), "n$news_id");
    }
  }
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
        <div class='col-md-9 col-lg-10 p-0'>
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