<?php
date_default_timezone_set("asia/taipei");

$serverHost = "mysql";

function SQL($db, $sql)
{
    return $db->prepare($sql);
}

try {
    $db = new PDO("mysql:host=$serverHost;dbname=final", "test", "admin");
    $db->exec("set names utf8");
} catch (PDOException $e) {
    echo "Error :" . $e->getMessage();
}
