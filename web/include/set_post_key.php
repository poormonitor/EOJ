<?php
$post_key_new = strtoupper(substr(MD5($_SESSION[$OJ_NAME . '_' . 'user_id'] . rand(0, 9999999)), 0, 10));
if (
    !isset($_SESSION[$OJ_NAME . '_' . 'postkey'])
    || !is_array($_SESSION[$OJ_NAME . '_' . 'postkey'])
) {
    $_SESSION[$OJ_NAME . '_' . 'postkey'] = array($post_key_new);
} else {
    $_SESSION[$OJ_NAME . '_' . 'postkey'] = array_push($_SESSION[$OJ_NAME . '_' . 'postkey'], $post_key_new);
}
?>
<input type=hidden name="postkey" value="<?php echo $post_key_new ?>">