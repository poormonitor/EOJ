<?php
require_once("admin-header.php");
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
<?php
require_once("admin-footer.php");
?>