<?php
require_once("./include/maxmind/autoload.php");

use MaxMind\Db\Reader;

$IP_READER = new Reader('./include/GeoLite2-City.mmdb');

function getLocation($ip)
{
    global $IP_READER, $OJ_LANG;
    $lang = $OJ_LANG;
    if ($lang == "zh") {
        $lang = "zh-CN";
    } else {
        $lang = "en";
    }
    try {
        $record = $IP_READER->get($ip);
        $country = $record["country"][$lang];
        $iso = $record["country"]["code"];
        $division = $record["region"][$lang];
        $city = $record["city"][$lang];
    } catch (Exception $e) {
        return null;
    }
    return array("city" => $city, "division" => $division, "country" => $country, "country_iso" => $iso);
}

function getLocationShort($ip)
{
    $record = getLocation($ip);
    if (!$record) return "";

    if ($record["country_iso"] == "CN") {
        $result = $record["division"];
    } else {
        $result = $record["country"];
    }
    return $result;
}

function getLocationFull($ip)
{
    $record = getLocation($ip);
    if (!$record) return "";

    if ($record["country_iso"] == "CN") {
        if ($record["division"] != $record["city"])
            $result = $record["division"] . " " . $record["city"];
        else
            $result = $record["division"];
    } else {
        $result = $record["country"] . " " . $record["division"] . " " . $record["city"];
    }

    return $result;
}
