<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
    $view_swal_params = "{title:'$MSG_NOT_LOGINED',icon:'error'}";
    $error_location = "../loginpage.php";
    require("../template/error.php");
    exit(0);
}
$url = basename($_SERVER['REQUEST_URI']);
$ACTIVE = "class='active'";
$_SESSION[$OJ_NAME . '_' . 'profile_csrf'] = rand();
header("Cache-control: private");
$prefix = "../";
?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $OJ_NAME?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <?php include("../template/css.php"); ?>
    <title><?php echo $OJ_NAME ?></title>
</head>

<body>
    <div class='container'>
        <?php include("../template/nav.php") ?>
        <div class='jumbotron'>
            <div class='row lg-container'>
                <div class='col-md-2'>
                    <hr>
                    <a class='btn btn-default btn-block' href="./" title="<?php echo $MSG_ADMIN ?>"><b><?php echo $MSG_ADMIN ?></b></a>
                    <a class='btn btn-default btn-block' href="../status.php" title="<?php echo $MSG_HELP_SEEOJ ?>"><b><?php echo $MSG_SEEOJ ?></b></a><br>
                    <div class='middle'>
                        <div class="btn-group-vertical" role="menu">
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_NEWS . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                        <li><a class="dropdown-item" href="setmsg.php" title="<?php echo $MSG_HELP_SETMESSAGE?>"><b><?php echo $MSG_NEWS."-".$MSG_SETMESSAGE?></b></a></li>
                                        <li><a class="dropdown-item" href="news_list.php" title="<?php echo $MSG_HELP_NEWS_LIST ?>"><b><?php echo $MSG_NEWS . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item" href="news_add_page.php" title="<?php echo $MSG_HELP_ADD_NEWS ?>"><b><?php echo $MSG_NEWS . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_USER . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) { ?>
                                        <li><a class="dropdown-item" href="user_list.php" title="<?php echo $MSG_HELP_USER_LIST ?>"><b><?php echo $MSG_USER . "-" . $MSG_LIST ?></b></a></li>
                                    <?php } ?>
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                        <li><a class="dropdown-item" href="user_add.php" title="<?php echo $MSG_HELP_USER_ADD ?>"><b><?php echo $MSG_USER . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) { ?>
                                        <li><a class="dropdown-item" href="changepass.php" title="<?php echo $MSG_HELP_SETPASSWORD ?>"><b><?php echo $MSG_USER . "-" . $MSG_SETPASSWORD ?></b></a></li>
                                    <?php } ?>
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                        <li><a class="dropdown-item" href="class_update.php" title="<?php echo $MSG_HELP_EDIT_SCHOOL ?>"><b><?php echo $MSG_USER . "-" . $MSG_EDIT . "-" . $MSG_SCHOOL ?></b></a></li>
                                        <li><a class="dropdown-item" href="user_set_nick.php" title="<?php echo $MSG_HELP_EDIT_NICK ?>"><b><?php echo $MSG_USER . "-" . $MSG_NICK ?></b></a>
                                        <li><a class="dropdown-item" href="privilege_list.php" title="<?php echo $MSG_HELP_PRIVILEGE_LIST ?>"><b><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item" href="privilege_add.php" title="<?php echo $MSG_HELP_ADD_PRIVILEGE ?>"><b><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_ADD ?></b></a></li>
                                        <li><a class="dropdown-item" href="group_list.php" title="<?php echo $MSG_HELP_LIST_GROUP ?>"><b><?php echo $MSG_GROUP . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item" href="group_add.php" title="<?php echo $MSG_HELP_CHANGE_GROUP ?>"><b><?php echo $MSG_GROUP . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_PROBLEM . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                        <li><a class="dropdown-item" href="problem_list.php" title="<?php echo $MSG_HELP_PROBLEM_LIST ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_LIST ?></b></a></li>
                                    <?php }
                                    if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) { ?>
                                        <li><a class="dropdown-item" href="problem_add_page.php" title="<?php echo html_entity_decode($MSG_HELP_ADD_PROBLEM) ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_ADD ?></b></a></li>
                                        <li><a class="dropdown-item" href="problem_list_2.php" title="<?php echo $MSG_HELP_PROBLEM_2 ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_PROBLEM_2 ?></b></a></li>
                                        <li><a class="dropdown-item" href="problem_import.php" title="<?php echo $MSG_HELP_IMPORT_PROBLEM ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_IMPORT ?></b></a></li>
                                        <li><a class="dropdown-item" href="problem_export.php" title="<?php echo $MSG_HELP_EXPORT_PROBLEM ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_EXPORT ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_CONTEST . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                        <li><a class="dropdown-item" href="contest_list.php" title="<?php echo $MSG_HELP_CONTEST_LIST ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item" href="contest_add.php" title="<?php echo $MSG_HELP_ADD_CONTEST ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_ADD ?></b></a></li>
                                        <li><a class="dropdown-item" href="user_set_ip.php" title="<?php echo $MSG_SET_LOGIN_IP ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_SET_LOGIN_IP ?></b></a></li>
                                        <li><a class="dropdown-item" href="team_generate.php" title="<?php echo $MSG_HELP_TEAMGENERATOR ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_TEAMGENERATOR ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_QUIZ . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                        <li><a class="dropdown-item" href="quiz_list.php" title="<?php echo $MSG_HELP_QUIZ ?>"><b><?php echo $MSG_QUIZ . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item" href="quiz_add.php" title="<?php echo $MSG_HELP_QUIZ_ADD ?>"><b><?php echo $MSG_QUIZ . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                <div class="btn-group" role="menu">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo $MSG_SYSTEM . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="rejudge.php" title="<?php echo $MSG_HELP_REJUDGE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_REJUDGE ?></b></a></li>
                                        <li><a class="dropdown-item" href="source_give.php" title="<?php echo $MSG_HELP_GIVESOURCE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_GIVESOURCE ?></b></a></li>
                                        <li><a class="dropdown-item" href="../online.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_HELP_ONLINE ?></b></a></li>
                                        <li><a class="dropdown-item" href="update_db.php" title="<?php echo $MSG_HELP_UPDATE_DATABASE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_UPDATE_DATABASE ?></b></a></li>
                                        <li><a class="dropdown-item" href="pip.php" title="<?php echo $MSG_HELP_MODULE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_MODULE_INSTALL ?></b></a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                        <br><a class='btn btn-default btn-block' href="https://github.com/poormonitor/hoj/" target="_blank"><b>HOJ</b></a>
                    <?php } ?>
                    <br>
                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                        <a href="problem_changeid.php" title="Danger,Use it on your own risk">
                            <font color="eeeeee">ReOrderProblem</font>
                        </a>
                    <?php } ?>
                    <hr>
                </div>
                <div class='col-md-10'>