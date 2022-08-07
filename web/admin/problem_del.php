<?php
require_once("admin-header.php"); ?>
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
                <div class='col-md-10 p-0'>
                    <?php
                    ini_set("display_errors", "On");
                    require_once("../include/check_get_key.php");

                    ?>
                    <?php
                    if (function_exists('system')) {
                        $id = intval($_GET['id']);

                        $basedir = "$OJ_DATA/$id";
                        if (strlen($basedir) > 16) {
                            system("rm -rf $basedir");
                        }
                        $sql = "delete FROM `problem` WHERE `problem_id`=?";
                        pdo_query($sql, $id);
                        $sql = "delete from `privilege` where `rightstr`=? ";
                        pdo_query($sql, "p$id");
                        $sql = "update solution set problem_id=0 where `problem_id`=? ";
                        pdo_query($sql, $id);

                        $sql = "select max(problem_id) FROM `problem`";
                        $result = pdo_query($sql);
                        $row = $result[0];
                        $max_id = $row[0];
                        $max_id++;
                        if ($max_id < 1000) $max_id = 1000;

                        $sql = "ALTER TABLE problem AUTO_INCREMENT = $max_id";
                        pdo_query($sql);
                    ?>
                        <script language=javascript>
                            history.go(-1);
                        </script>
                    <?php
                    } else {


                    ?>
                        <script language=javascript>
                            alert("Nees enable system() in php.ini");
                            history.go(-1);
                        </script>
                    <?php

                    }

                    ?>

                    <br>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("../template/js.php"); ?>
</body>

</html>