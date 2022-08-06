<?php
require_once("./include/geoip2.phar");

use GeoIp2\Database\Reader;

$IP_READER = new Reader('./include/GeoLite2-City.mmdb');

function getItemAuto($item, $key)
{
    if (isset($item->names[$key])) {
        return $item->names[$key];
    }
    return $item->name;
}

function getLocation($ip)
{
    global $IP_READER, $OJ_LANG;
    $lang = $OJ_LANG;
    if ($lang == "zh") {
        $lang = "zh-CN";
    }
    try {
        $record = $IP_READER->city($ip);
        $city = getItemAuto($record->city, $lang);
        $division = getItemAuto($record->mostSpecificSubdivision, $lang);
        $country = getItemAuto($record->country, $lang);
        $iso = $record->country->isoCode;
    } catch (Exception $e) {
        return array("city" => "", "division" => "Not Found", "country" => "", "country_iso" => "");
    }
    return array("city" => $city, "division" => $division, "country" => $country, "country_iso" => $iso);
}

function getLocationShort($ip)
{
    $record = getLocation($ip);

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
