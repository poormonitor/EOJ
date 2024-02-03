<?php
$get_key_new = strtoupper(substr(MD5($_SESSION[$OJ_NAME . '_' . 'user_id'] . rand(0, 9999999)), 0, 10));
if (!isset($_SESSION[$OJ_NAME . '_' . 'getkey'])) {
    $_SESSION[$OJ_NAME . '_' . 'getkey'] = array($get_key_new);
} else {
    $_SESSION[$OJ_NAME . '_' . 'getkey'] = array_push($_SESSION[$OJ_NAME . '_' . 'getkey'], $get_key_new);
}
