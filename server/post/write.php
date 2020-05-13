<?php

if (!isset($_POST['token']) || empty($_POST['token'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}

require 'model/get_streamid_from_token.php';
$streamid = get_streamid_from_token($_POST['token']);

if (!isset($_POST['length']) || floatval($_POST['length']) <= 0) {
    header('HTTP/1.0 400 Bad Request');
    exit('Wrong value for length');
}

if (!isset($_POST['url']) || strlen($_POST['url']) <= 0) {
    header('HTTP/1.0 400 Bad Request');
    exit('Wrong value for url');
}


$url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
if (isset($_POST['is_first_chunk']) && $_POST['is_first_chunk'] == 1) {
    $is_first_chunk = 1;
} else {
    $is_first_chunk = 0;
}

$stmt = $db->prepare("INSERT INTO chunks (`streamid`, `length`, `skylink`, `is_first_chunk`, `resolution`) VALUES (?, ?, ?, ?, 'original')");
if (!$stmt->execute([$streamid, $_POST['length'], $url, $is_first_chunk])) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Database error');
}
$stmt = null;

// Update stream status: started
$stmt = $db->prepare("UPDATE stream SET started = 1 WHERE token = ?");
if (!$stmt->execute([$_POST['token']])) {
    exit('Database error 2');
}
$stmt = null;

echo 'ok';