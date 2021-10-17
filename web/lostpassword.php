<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $OJ_NAME; ?></title>
</head>

<body></body>

</html>
<script src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/template/bs3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = "Welcome To Online Judge";

require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
$lost_user_id = $_POST['user_id'];
$lost_email = $_POST['email'];
if (isset($_POST['vcode'])) $vcode = trim($_POST['vcode']);
if ($lost_user_id && ($vcode != $_SESSION[$OJ_NAME . '_' . "vcode"] || $vcode == "" || $vcode == null)) {
	$view_swal = "验证码错误！";
	require("template/" . $OJ_TEMPLATE . "/error.php");
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
    $smtpserver = "ssl://smtp.exmail.qq.com"; //SMTP服务器
    $smtpserverport = 465; //SMTP服务器端口
    $smtpusermail = "oj@oldmonitor.cn"; //SMTP服务器的用户邮箱
    $smtpemailto = $row['email']; //发送给谁
    $smtpuser = "oj@oldmonitor.cn"; //SMTP服务器的用户帐号
    $smtppass = "Poormonitor7923"; //SMTP服务器的用户密码
    $mailtitle = $OJ_NAME . "密码重置激活"; //邮件主题
    $mailcontent = "$lost_user_id:\n您好！\n您在" . $OJ_NAME . "选择了找回密码服务,为了验证您的身份,请将下面字串输入口令重置页面以确认身份:\n" . $_SESSION[$OJ_NAME . '_' . 'lost_key'] . "\n请注意，这则密码将会在重置成功后成为您的临时密码。\n\n" . $OJ_NAME; //邮件内容
    $mailtype = "TXT"; //邮件格式（HTML/TXT）,TXT为文本邮件
    //************************ 配置信息 ****************************
    $smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false; //是否显示发送的调试信息
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype, $mail, $mailuser = $OJ_NAME);
    require("template/" . $OJ_TEMPLATE . "/lostpassword2.php");
} else {
    if ($_POST['user_id'] != "" && $_POST['email'] != "") {
        $view_errors = "
        <h3>错误！</h3>
        <script language='javascript'>
            swal('用户名或邮箱错误！').then((onConfirm)=>{history.go(-1);});
        </script>";
        require("template/" . $OJ_TEMPLATE . "/error.php");
    } else {
        require("template/" . $OJ_TEMPLATE . "/lostpassword.php");
    }
}
?>