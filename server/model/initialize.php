<?php

if (DEBUG_MODE == true) {
    define('SecureSessionCookie', false);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    define('SecureSessionCookie', true);
}

date_default_timezone_set('UTC');
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

// Connect to database
try {
    $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWD);
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    }
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit("Hiba: nem sikerült csatlakozni az adatbázishoz");
}