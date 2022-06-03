<?php
//cache foot start      
if (isset($file)) {
    if ($OJ_MEMCACHE) {
        $mem->set($file, ob_get_contents(), $cache_time);
    } else {
        // if(!file_exists("cache")) mkdir("cache");
        //      file_put_contents($file,ob_get_contents());
    }
}
//cache foot stop
?>