<?php
if (isset($pageCacheKey) && isset($write_cache) && isset($cache_time)) {
    if ($OJ_MEMCACHE) {
        setCache($pageCacheKey, ob_get_contents(), $cache_time);
    }
}
