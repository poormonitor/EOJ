<?php
function getLocation($ip)
{
    if (strpos($ip, ":") !== false) {
        require_once("./include/ipdbv6.func.php");
        $db6 = new ipdbv6("./include/ipv6wry.db");
        $result = $db6->query($ip);
    } else {
        require_once("./include/ipdbv4.func.php");
        $db4 = new ipdbv4("./include/ipv4wry.dat");
        $result = $db4->getlocation($ip);
    }
    return $result;
}
