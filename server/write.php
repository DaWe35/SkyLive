<?php
require 'config.php';
if (PASSWORD == '') {
    exit('Password not found in config.php');
}
if (!isset($_POST['password']) || $_POST['password'] !== PASSWORD) {
    exit('Access denied');
}

if (!isset($_POST['length']) || floatval($_POST['length']) <= 0) {
    exit('Wrong value for length');
}

if (!isset($_POST['url']) || strlen($_POST['url']) <= 0) {
    exit('Wrong value for url');
}

if (!isset($_POST['url']) || strlen($_POST['url']) <= 0) {
    exit('Wrong value for url');
}

if (!isset($_POST['streamid']) || strlen($_POST['streamid']) <= 0) {
    exit('Wrong value for streamid');
}
$streamid = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['streamid']);
if (empty($streamid) || strlen($streamid) <= 1) {
    exit('Wrong stream id');
}

$file = "streams/" . $streamid. ".txt";
touch($file);
file_put_contents($file, "#EXTINF:" . $_POST['length'] . ",\n", FILE_APPEND);
file_put_contents($file, $_POST['url'] . "\n", FILE_APPEND);

echo 'ok';