<?php require_once("admin-header.php"); ?>

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
                    <br>
                    <div class="table-responsive">
                        <table class="table m-0 text-md-wrap" style='width:95%;'>
                            <tbody>
                                <tr>
                                    <td><a class='btn btn-default btn-block' href="../status.php" target="_top"><b><?php echo $MSG_SEEOJ ?></b></a></td>
                                    <td>
                                        <p><?php echo $MSG_HELP_SEEOJ ?></p>
                                    </td>
                                </tr>

                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-info btn-sm' href="setmsg.php" target="main"><b><?php echo $MSG_NEWS . "-" . $MSG_SETMESSAGE ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_SETMESSAGE ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-info btn-sm' href="news_list.php"><b><?php echo $MSG_NEWS . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_NEWS_LIST ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-info btn-sm' href="news_add_page.php"><b><?php echo $MSG_NEWS . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_ADD_NEWS ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="user_list.php"><b><?php echo $MSG_USER . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_USER_LIST ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="user_add.php"><b><?php echo $MSG_USER . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_USER_ADD ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="changepass.php"><b><?php echo $MSG_USER . "-" . $MSG_SETPASSWORD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_SETPASSWORD ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="class_update.php"><b><?php echo $MSG_USER . "-" . $MSG_EDIT . $MSG_SCHOOL ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_EDIT_SCHOOL ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="user_set_nick.php"><b><?php echo $MSG_USER . "-" . $MSG_ADD . $MSG_NICK ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_EDIT_NICK ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="privilege_list.php"><b><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_PRIVILEGE_LIST ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="privilege_add.php"><b><?php echo $MSG_USER . "-" . $MSG_PRIVILEGE . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_ADD_PRIVILEGE ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="group_list.php"><b><?php echo $MSG_USER . "-" . $MSG_GROUP . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_LIST_GROUP ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-primary btn-sm' href="group_add.php"><b><?php echo $MSG_USER . "-" . $MSG_GROUP . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_CHANGE_GROUP ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-success btn-sm' href="problem_list.php"><b><?php echo $MSG_PROBLEM . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_PROBLEM_LIST ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-success btn-sm' href="problem_add_page.php"><b><?php echo $MSG_PROBLEM . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_ADD_PROBLEM ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-success btn-sm' href="problem_list_2.php"><b><?php echo $MSG_PROBLEM . "-" . $MSG_PROBLEM_2 ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_PROBLEM_2 ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-success btn-sm' href="problem_import.php"><b><?php echo $MSG_PROBLEM . "-" . $MSG_IMPORT ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_IMPORT_PROBLEM ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-success btn-sm' href="problem_export.php"><b><?php echo $MSG_PROBLEM . "-" . $MSG_EXPORT ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_EXPORT_PROBLEM ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-warning btn-sm' href="contest_list.php"><b><?php echo $MSG_CONTEST . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_CONTEST_LIST ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-warning btn-sm' href="contest_add.php"><b><?php echo $MSG_CONTEST . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_ADD_CONTEST ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-warning btn-sm' href="user_set_ip.php"><b><?php echo $MSG_CONTEST . "-" . $MSG_SET_LOGIN_IP ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_SET_LOGIN_IP ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-warning btn-sm' href="team_generate.php"><b><?php echo $MSG_CONTEST . "-" . $MSG_TEAMGENERATOR ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_TEAMGENERATOR ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-info btn-sm' href="quiz_list.php"><b><?php echo $MSG_QUIZ . "-" . $MSG_LIST ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_QUIZ ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-info btn-sm' href="quiz_add.php"><b><?php echo $MSG_QUIZ . "-" . $MSG_ADD ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_QUIZ_ADD ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-danger btn-sm' href="rejudge.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_REJUDGE ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_REJUDGE ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-danger btn-sm' href="source_give.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_GIVESOURCE ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_GIVESOURCE ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-danger btn-sm' href="../online.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_HELP_ONLINE ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_ONLINE ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-danger btn-sm' href="update_db.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_UPDATE_DATABASE ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_UPDATE_DATABASE ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><a class='btn btn-danger btn-sm' href="pip.php"><b><?php echo $MSG_SYSTEM . "-" . $MSG_MODULE_INSTALL ?></b></a></center>
                                        </td>
                                        <td>
                                            <p><?php echo $MSG_HELP_MODULE ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
                        <a href="problem_changeid.php" title="Danger,Use it on your own risk">
                            <font color="eeeeee">ReOrderProblem</font>
                        </a>

                    <?php } ?>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("../template/js.php"); ?>
</body>

</html>