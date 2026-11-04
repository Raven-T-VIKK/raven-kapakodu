<?php

$host = "localhost";
$user = "kapakodu_app";
$pw   = "Passw0rd";
$db   = "kapakodu";

$conn = new mysqli($host, $user, $pw, $db);

if ($conn->connect_error) {
    die("Ühenduse loomine ebaõnnestus: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
date_default_timezone_set("Europe/Tallinn");

// Abifunktsioon: kaitseb väljundi XSS-i eest
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
