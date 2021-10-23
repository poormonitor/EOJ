<?php

$dir = basename(getcwd());
if ($dir == "discuss3" || $dir == "admin") $path_fix = "../";
else $path_fix = "";
?>


<link rel="stylesheet" href="<?php echo $OJ_CDN_URL . $path_fix . "template/$OJ_TEMPLATE/" ?>bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $OJ_CDN_URL . $path_fix . "template/$OJ_TEMPLATE/" ?>index.min.css">