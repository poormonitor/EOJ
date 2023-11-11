<?php require_once("./include/db_info.inc.php"); ?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <title><?php echo $OJ_NAME; ?></title>
  <?php include("./template/css.php"); ?>
</head>

<body>
  <script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
  <script src="<?php echo $OJ_CDN_URL .  "include/" ?>message.min.js"></script>

  <?php
  require_once('./include/setlang.php');
  $view_title = $OJ_NAME;

  require_once("./include/const.inc.php");
  require_once("./include/my_func.inc.php");
  $lost_user_id = $_POST['user_id'];
  $lost_key = $_POST['lost_key'];
  $vcode = trim($_POST['vcode']);
  if ($lost_user_id == $_SESSION[$OJ_NAME . '_' . 'lost_user_id'] && ($vcode != $_SESSION[$OJ_NAME . '_' . "vcode"])) {
    echo "<script language='javascript'>\n";
    echo "swal('Verify Code Wrong!').then((onConfirm)=>{history.go(-1);});\n";
    echo "</script>";
    exit(0);
  }
  $lost_user_id = stripslashes($lost_user_id);
  $lost_key = stripslashes($lost_key);
  $sql = " update `users` set password=? WHERE `user_id`=?";
  if (

    $_SESSION[$OJ_NAME . '_' . 'lost_user_id'] == $lost_user_id &&
    $_SESSION[$OJ_NAME . '_' . 'lost_key'] == $lost_key
  ) {
    $result = pdo_query($sql, pwGen($lost_key), $lost_user_id);
    $view_errors = "Password Reseted to the key you've just inputed.Click <a href=index.php>Here</a> to login!";
  } else {
    $view_errors = "Password Reset Fail";
  }


  require("template/error.php");
  

  
  ?>
</body>

</html>