<?php require_once("./include/db_info.inc.php"); ?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <title><?php echo $OJ_NAME; ?></title>
</head>

<body>
    <script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
    <script src="<?php echo $OJ_CDN_URL .  "include/" ?>sweetalert.min.js"></script>
    <?php
    require_once('./include/setlang.php');
    $view_title = "Welcome To Online Judge";

    require_once("./include/const.inc.php");
    require_once("./include/my_func.inc.php");
    $lost_user_id = $_POST['user_id'];
    $lost_email = $_POST['email'];
    if (isset($_POST['vcode'])) $vcode = trim($_POST['vcode']);
    if ($lost_user_id && ($vcode != $_SESSION[$OJ_NAME . '_' . "vcode"] || $vcode == "" || $vcode == null)) {
        $view_swal = "$MSG_VCODE_WRONG";
        require("template/error.php");
        exit(0);
    }
    $lost_user_id = stripslashes($lost_user_id);
    $lost_email = stripslashes($lost_email);
    $sql = "SELECT `email` FROM `users` WHERE `user_id`=?";
    $result = pdo_query($sql, $lost_user_id);
    $row = $result[0];
    if ($row && $row['email'] == $lost_email && strpos($lost_email, '@')) {
        $_SESSION[$OJ_NAME . '_' . 'lost_user_id'] = $lost_user_id;
        $_SESSION[$OJ_NAME . '_' . 'lost_key'] = getToken(16);


        require_once("include/email.class.php");
        //******************** 配置信息 ********************************
        //在include/db_info.inc.php中配置
        $smtpemailto = $row['email']; //发送给谁
        $mailtitle = $OJ_NAME . "密码重置激活"; //邮件主题
        $mailcontent = "$lost_user_id:\n您好！\n您在" . $OJ_NAME . "选择了找回密码服务,为了验证您的身份,请将下面字串输入口令重置页面以确认身份:\n" . $_SESSION[$OJ_NAME . '_' . 'lost_key'] . "\n请注意，这则密码将会在重置成功后成为您的临时密码。\n\n" . $OJ_NAME; //邮件内容
        $mailtype = "TXT"; //邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false; //是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype, $mail, $mailuser = $OJ_NAME);
        require("template/lostpassword2.php");
    } else {
        if ($_POST['user_id'] != "" && $_POST['email'] != "") {
            $view_swal = $MSG_PARAMS_ERROR;
            require("template/error.php");
        } else {
            require("template/lostpassword.php");
        }
    }
    ?>
</body>

</html>