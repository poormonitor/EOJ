<?php
if ($_SESSION[$OJ_NAME . '_' . 'getkey'] != $_GET['getkey']) {
    $view_errors_js = "history.go(-1)";
    require_once("../template/error.php");
    exit(1);
} else {
    unset($_SESSION[$OJ_NAME . '_' . 'getkey']);
}
