<?php

require_once(dirname(__FILE__) . "/db_info.inc.php");
# Connect to memcache:
global $memcache;
if ($OJ_MEMCACHE) {
    $memcache = new Memcached();
    $memcache->addServer($OJ_MEMSERVER,  $OJ_MEMPORT);
}

//下面两个函数首先都会判断是否有使用memcache，如果有使用，就会调用memcached的set/get命令来保存和获取数据
//否则简单地返回false
# Gets key / value pair into memcache … called by mysql_query_cache()
function getCache($key)
{
    global $memcache;
    //	if ($memcache->get($key)) echo "true";
    return ($memcache) ? $memcache->get($key) : false;
}

# Puts key / value pair into memcache … called by mysql_query_cache()
function setCache($key, $object, $timeout = 60)
{
    global $memcache;
    return ($memcache) ? $memcache->set($key, $object, $timeout) : false;
}

# Caching version of pdo_query()
function mysql_query_cache($sql)
{
    global $OJ_NAME, $OJ_MEMCACHE;
    if ($OJ_MEMCACHE) {
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
