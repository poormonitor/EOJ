<?php
if (
    !isset($_SESSION[$OJ_NAME . '_' . 'getkey'])
    || !isset($_GET['getkey'])
    || !in_array($_GET['getkey'], $_SESSION[$OJ_NAME . '_' . 'getkey'])
) {
    $view_errors_js = "history.go(-1)";
    require_once("../template/error.php");
    exit(1);
} else {
    $key = array_search($_GET['getkey'], $_SESSION[$OJ_NAME . '_' . 'getkey']);
    unset($_SESSION[$OJ_NAME . '_' . 'getkey'][$key]);
}
