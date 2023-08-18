<?php
require_once("./include/maxmind/autoload.php");

use MaxMind\Db\Reader;

$file = dirname(__FILE__) . '/GeoLite2-City.mmdb';
if (file_exists($file)) {
    $IP_READER = new Reader($file);
} else {
    $IP_READER = null;
}

function getLocation($ip)
{
    global $IP_READER, $OJ_LANG;

    if (is_null($IP_READER)) return null;

    $lang = $OJ_LANG;
    if ($lang == "zh") {
        $lang = "zh-CN";
    } else {
        $lang = "en";
    }

    try {
        $record = $IP_READER->get($ip);
        $country = $record["country"]["names"][$lang];
        $iso = $record["country"]["iso_code"];
        $division = isset($record["subdivisions"]) ? $record["subdivisions"][0]["names"][$lang] : "";
        $city = $record["city"]["names"][$lang];
    } catch (Exception $e) {
        return null;
    }

    return array("city" => $city, "division" => $division, "country" => $country, "country_iso" => $iso);
}

function getLocationShort($ip)
{
    global $MSG_UNKNOWN;

    $record = getLocation($ip);
    if (is_null($record)) return $MSG_UNKNOWN;

    if ($record["country_iso"] == "CN") {
        $result = $record["division"];
    } else {
        $result = $record["country"];
    }

    if (!strlen(trim($result))) $result = $MSG_UNKNOWN;

    return $result;
}

function getLocationFull($ip)
{
    global $MSG_UNKNOWN;

    $record = getLocation($ip);
    if (is_null($record)) return $MSG_UNKNOWN;

    if ($record["country_iso"] == "CN") {
        if ($record["division"] != $record["city"])
            $result = $record["division"] . " " . $record["city"];
        else
            $result = $record["division"];
    } else {
        $result = $record["country"] . " " . $record["division"] . " " . $record["city"];
    }

    if (!strlen(trim($result))) $result = $MSG_UNKNOWN;

    return $result;
}
