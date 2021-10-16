<?php

$dir = basename(getcwd());
if ($dir == "discuss3" || $dir == "admin") $path_fix = "../";
else $path_fix = "";
?>


<link rel="stylesheet" href="<?php echo $OJ_CDN_URL . "template/$OJ_TEMPLATE/" ?>bootstrap.min.css">
<link rel="stylesheet" href="/template/bs3/index.min.css">