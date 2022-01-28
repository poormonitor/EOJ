<?php require_once("admin-header.php"); ?>
<br />
<table class="table" style='width:95%;margin:0 auto;'>
    <tbody>
        <tr>
            <td><a class='btn btn-default btn-block btn-sm' href="../status.php" target="_top"><b><?php echo $MSG_SEEOJ ?></b></a></td>
            <td>
                <p><?php echo $MSG_HELP_SEEOJ ?></p>
            </td>
        </tr>

        <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
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
                    <p>根据规则批量修改用户的班级。</p>
                </td>
            </tr>
            <tr>
                <td>
                    <center><a class='btn btn-primary btn-sm' href="user_set_nick.php"><b><?php echo $MSG_USER . "-" . $MSG_ADD . $MSG_NICK ?></b></a></center>
                </td>
                <td>
                    <p>批量修改用户的昵称。</p>
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
                    <p>查看已有用户组，并可添加新的用户组。</p>
                </td>
            </tr>
            <tr>
                <td>
                    <center><a class='btn btn-primary btn-sm' href="group_add.php"><b><?php echo $MSG_USER . "-" . $MSG_GROUP . "-" . $MSG_ADD ?></b></a></center>
                </td>
                <td>
                    <p>将用户批量加入用户组，或批量删除用户的用户组信息。</p>
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
                    <center><a class='btn btn-success btn-sm' href="problem_list_2.php"><b><?php echo $MSG_PROBLEM . "-" . "第二题库" ?></b></a></center>
                </td>
                <td>
                    <p>存放一些题目但并不在前台展示，可以随时转换到前台。</p>
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
                    <center><a class='btn btn-danger btn-sm' href="pip.php"><b><?php echo $MSG_SYSTEM . "-" . "模块安装" ?></b></a></center>
                </td>
                <td>
                    <p><?php echo $MSG_MODULE_INSTALL ?></p>
                </td>
            </tr>
            <tr>
                <td><a class='btn btn-default btn-block btn-sm' href="https://github.com/zhblue/hustoj/" target="_blank"><b>HUSTOJ</b></a></td>
                <td>
                    <p>HUSTOJ</p>
                </td>
            </tr>
            <tr>
                <td>
                    <center><a class='btn btn-default btn-block btn-sm' target='_blank' href="https://github.com/zhblue/hustoj/blob/master/wiki/FAQ.md"><?php echo $MSG_ADMIN . " " . $MSG_FAQ ?></a></center>
                </td>
                <td>
                    <p><?php echo $MSG_ADMIN . " " . $MSG_FAQ ?></p>
                </td>
            </tr>
            <tr>
                <td><a class='btn btn-default btn-block btn-sm' href="https://github.com/zhblue/freeproblemset/" target="_blank"><b>FreeProblemSet</b></a></td>
                <td>
                    <p>FreeProblemSet</p>
                </td>
            </tr>
            <tr>
                <td><a class='btn btn-default btn-block btn-sm' href="http://tk.hustoj.com" target="_blank"><b>自助题库</b></a></td>
                <td>
                    <p></p>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<br />
<?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) { ?>
    <a href="problem_changeid.php" title="Danger,Use it on your own risk">
        <font color="eeeeee">ReOrderProblem</font>
    </a>

<?php }
require_once("admin-footer.php");
?>

<?php require_once("admin-footer.php"); ?>