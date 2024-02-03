<?php
if (
    !isset($_SESSION[$OJ_NAME . '_' . 'getkey'])
    || !is_array($_SESSION[$OJ_NAME . '_' . 'getkey'])
) {
    $_SESSION[$OJ_NAME . '_' . 'getkey'] = array();
} elseif (count($_SESSION[$OJ_NAME . '_' . 'getkey']) > 10) {
    array_shift($_SESSION[$OJ_NAME . '_' . 'getkey']);
}

$get_key_new = strtoupper(substr(MD5($_SESSION[$OJ_NAME . '_' . 'user_id'] . rand(0, 9999999)), 0, 10));
$_SESSION[$OJ_NAME . '_' . 'getkey'][] = $get_key_new;
