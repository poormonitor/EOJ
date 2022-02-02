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
header("Cache-control: private");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/favicon.ico">
    <?php include("../template/css.php"); ?>
    <title><?php echo $OJ_NAME ?></title>
</head>

<body>
    <div class='container'>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../<?php echo $OJ_HOME ?>"><i class="icon-home"></i><?php echo $OJ_NAME ?></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li <?php if ($url == "faqs.php") echo " $ACTIVE"; ?>>
                            <a href="../faqs.php"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> <?php echo $MSG_FAQ ?></a>
                        </li>
                        <li <?php if ($url == "problemset.php") echo " $ACTIVE"; ?>>
                            <a href="../problemset.php"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> <?php echo $MSG_PROBLEMS ?></a>
                        </li>
                        <li <?php if ($url == "category.php") echo " $ACTIVE"; ?>>
                            <a href="../category.php"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> <?php echo $MSG_SOURCE ?></a>
                        </li>
                        <li <?php if ($url == "status.php") echo " $ACTIVE"; ?>>
                            <a href="../status.php"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span> <?php echo $MSG_STATUS ?></a>
                        </li>
                        <li <?php if ($url == "ranklist.php") echo " $ACTIVE"; ?>>
                            <a href="../ranklist.php"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> <?php echo $MSG_RANKLIST ?></a>
                        </li>
                        <li <?php if ($url == "contest.php") echo " $ACTIVE"; ?>>
                            <a href="../contest.php"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <?php echo $MSG_CONTEST ?></a>
                        </li>
                        <li <?php if ($url == "quiz.php") echo " $ACTIVE"; ?>>
                            <a href="../quiz.php"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> <?php echo $MSG_QUIZ ?></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span id="profile">Login</span><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <script src="<?php echo "../template/profile.php?loc=admin&profile_csrf=" . $_SESSION[$OJ_NAME . '_' . 'profile_csrf']; ?>"></script>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class='jumbotron'>
            <div class='row'>
                <div class='col-sm-2'>
                    <hr>
                    <a class='btn btn-default btn-block btn-sm' href="./" title="<?php echo $MSG_ADMIN ?>"><b><?php echo $MSG_ADMIN ?></b></a>
                    <a class='btn btn-default btn-block btn-sm' href="../status.php" title="<?php echo $MSG_HELP_SEEOJ ?>"><b><?php echo $MSG_SEEOJ ?></b></a><br />
                    <div class='middle'>
                        <div class="btn-group-vertical" role="menu">
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_NEWS . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="news_list.php" title="<?php echo $MSG_HELP_NEWS_LIST ?>"><b><?php echo $MSG_NEWS . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="news_add_page.php" title="<?php echo $MSG_HELP_ADD_NEWS ?>"><b><?php echo $MSG_NEWS . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_USER . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="user_list.php" title="<?php echo $MSG_HELP_USER_LIST ?>"><b><?php echo $MSG_USER . "-" . $MSG_LIST ?></b></a></li>
                                    <?php } ?>
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="user_add.php" title="<?php echo $MSG_HELP_USER_ADD ?>"><b><?php echo $MSG_USER . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="changepass.php" title="<?php echo $MSG_HELP_SETPASSWORD ?>"><b><?php echo $MSG_USER . "-" . $MSG_SETPASSWORD ?></b></a></li>
                                    <?php } ?>
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="class_update.php" title="<?php echo $MSG_HELP_EDIT_SCHOOL ?>"><b><?php echo $MSG_USER . "-" . $MSG_EDIT . "-" . $MSG_SCHOOL ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="user_set_nick.php" title="<?php echo $MSG_HELP_EDIT_NICK ?>"><b><?php echo $MSG_USER . "-" . $MSG_NICK ?></b></a>
                                        <li><a class="dropdown-item btn-sm" href="privilege_list.php" title="<?php echo $MSG_HELP_PRIVILEGE_LIST ?>"><b><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="privilege_add.php" title="<?php echo $MSG_HELP_ADD_PRIVILEGE ?>"><b><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_ADD ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="group_list.php" title="<?php echo $MSG_HELP_LIST_GROUP ?>"><b><?php echo $MSG_GROUP . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="group_add.php" title="<?php echo $MSG_HELP_CHANGE_GROUP ?>"><b><?php echo $MSG_GROUP . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_PROBLEM . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="problem_list.php" title="<?php echo $MSG_HELP_PROBLEM_LIST ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_LIST ?></b></a></li>
                                    <?php }
                                    if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="problem_add_page.php" title="<?php echo html_entity_decode($MSG_HELP_ADD_PROBLEM) ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_ADD ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="problem_list_2.php" title="<?php echo $MSG_HELP_PROBLEM_2 ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_PROBLEM_2 ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="problem_import.php" title="<?php echo $MSG_HELP_IMPORT_PROBLEM ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_IMPORT ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="problem_export.php" title="<?php echo $MSG_HELP_EXPORT_PROBLEM ?>"><b><?php echo $MSG_PROBLEM . "-" . $MSG_EXPORT ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_CONTEST . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="contest_list.php" title="<?php echo $MSG_HELP_CONTEST_LIST ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="contest_add.php" title="<?php echo $MSG_HELP_ADD_CONTEST ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_ADD ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="user_set_ip.php" title="<?php echo $MSG_SET_LOGIN_IP ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_SET_LOGIN_IP ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="team_generate.php" title="<?php echo $MSG_HELP_TEAMGENERATOR ?>"><b><?php echo $MSG_CONTEST . "-" . $MSG_TEAMGENERATOR ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="menu">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $MSG_QUIZ . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                        <li><a class="dropdown-item btn-sm" href="quiz_list.php" title="<?php echo $MSG_HELP_QUIZ ?>"><b><?php echo $MSG_QUIZ . "-" . $MSG_LIST ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="quiz_add.php" title="<?php echo $MSG_HELP_QUIZ_ADD ?>"><b><?php echo $MSG_QUIZ . "-" . $MSG_ADD ?></b></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                <div class="btn-group" role="menu">
                                    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo $MSG_SYSTEM . "-" . $MSG_ADMIN ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-sm" href="rejudge.php" title="<?php echo $MSG_HELP_REJUDGE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_REJUDGE ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="source_give.php" title="<?php echo $MSG_HELP_GIVESOURCE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_GIVESOURCE ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="../online.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_HELP_ONLINE ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="update_db.php" title="<?php echo $MSG_HELP_UPDATE_DATABASE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_UPDATE_DATABASE ?></b></a></li>
                                        <li><a class="dropdown-item btn-sm" href="pip.php" title="<?php echo $MSG_HELP_MODULE ?>"><b><?php echo $MSG_SYSTEM . "-" . $MSG_MODULE_INSTALL ?></b></a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                        <br /><a class='btn btn-default btn-block btn-sm' href="https://github.com/zhblue/hustoj/" target="_blank"><b>HUSTOJ</b></a>
                        <br />
                        <a class="btn btn-default btn-block btn-sm" target='_blank' href="https://github.com/zhblue/hustoj/blob/master/wiki/FAQ.md"><?php echo $MSG_ADMIN . " " . $MSG_FAQ ?></a>
                        <br /><a class='btn btn-default btn-block btn-sm' href="https://github.com/zhblue/freeproblemset/" target="_blank"><b>FreeProblemSet</b></a>
                        <br /><a class='btn btn-default btn-block btn-sm' href="http://tk.hustoj.com" target="_blank"><b>自助题库</b></a>
                    <?php } ?>
                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                        <a href="problem_changeid.php" title="Danger,Use it on your own risk">
                            <font color="eeeeee">ReOrderProblem</font>
                        </a>
                    <?php } ?>
                    <hr>
                </div>
                <div class='col-sm-10'>