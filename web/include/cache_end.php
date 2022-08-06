<?php  
if (isset($pageCacheKey) && isset($write_cache) && isset($cache_time)) {
    $ns_key = $memcache->get($OJ_NAME . "_ns_key");
    if ($ns_key === false) {
        $ns_key = time();
        $memcache->set($OJ_NAME . "_ns_key", $ns_key);
    }
    $real_key = $OJ_NAME . "_key_" . $ns_key . $pageCacheKey;
    if ($OJ_MEMCACHE) {
        $mem->set($real_key, ob_get_contents(), $cache_time);
    }
}
