<?php
require_once("./include/db_info.inc.php");
if (isset($_GET['lang']) && in_array($_GET['lang'], array("zh", "en"))) {
    $_SESSION[$OJ_NAME . '_' . 'OJ_LANG'] = $_GET['lang'];
    setcookie("lang", $_GET['lang'], time() + 604800, $secure = true);
}
?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <title><?php echo $OJ_NAME; ?></title>
    <style>
        @media (prefers-color-scheme: dark) {
            body {
                height: auto;
                background: #242424;
            }
        }
    </style>
</head>

<body>
    <script>
        window.location.href = document.referrer;
    </script>
</body>

</html>