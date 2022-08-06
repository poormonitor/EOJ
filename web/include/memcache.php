<?php

require_once(dirname(__FILE__) . "/db_info.inc.php");

# Connect to memcache:
global $memcache, $OJ_MEMCACHE;
if ($OJ_MEMCACHE) {
    $memcache = new Memcached();
    $memcache->addServer($OJ_MEMSERVER,  $OJ_MEMPORT);
}

//下面两个函数首先都会判断是否有使用memcache，如果有使用，就会调用memcached的set/get命令来保存和获取数据
//否则简单地返回false
# Gets key / value pair into memcache … called by mysql_query_cache()
function getCache($key)
{
    global $memcache, $OJ_NAME;
    $ns_key = $memcache->get($OJ_NAME . "_ns_key");
    if ($ns_key === false) return false;
    $real_key = $OJ_NAME . "_key_" . $ns_key . $key;
    return ($memcache) ? $memcache->get($real_key) : false;
}

# Puts key / value pair into memcache … called by mysql_query_cache()
function setCache($key, $object, $timeout = 60)
{
    global $memcache, $OJ_NAME;
    $ns_key = $memcache->get($OJ_NAME . "_ns_key");
    if ($ns_key === false) {
        $ns_key = time();
        $memcache->set($OJ_NAME . "_ns_key", $ns_key);
    }
    $real_key = $OJ_NAME . "_key_" . $ns_key . $key;
    return ($memcache) ? $memcache->set($real_key, $object, $timeout) : false;
}

function updateCache() {
    global $memcache, $OJ_NAME;
    $ns_key = time();
    $memcache->set($OJ_NAME . "_ns_key", $ns_key);
}

# Caching version of pdo_query()
function mysql_query_cache($sql)
{
    global $OJ_NAME, $OJ_MEMCACHE;
    $target = array("update", "insert", "delete");
    $tag = true;
    foreach ($target as $tg) {
        if (stripos($sql, $tg) !== false) {
            $tag = false;
        }
    }
    if ($OJ_MEMCACHE && $tag) {
        $timeout = 4;
        $query_info = array($OJ_NAME, $_SERVER['HTTP_HOST'], "mysql_query");
        $query_info = array_merge($query_info, func_get_args());
        $key = md5(implode(" ", $query_info));
        if (!($cache = getCache($key))) {
            $cache = false;
            $cache = call_user_func_array("pdo_query", func_get_args());
            setCache($key, $cache, $timeout);
        }
    } else {
        $cache = call_user_func_array("pdo_query", func_get_args());
    }
    return $cache;
}
