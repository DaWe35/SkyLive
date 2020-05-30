<?php

if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    $portal = htmlspecialchars($_GET['portal']);
} else {
    $portal = '';
}

$stmt = $db->prepare("SELECT streamid, title, description, scheule_time, visibility FROM stream WHERE streamid = ? LIMIT 1");
if (!$stmt->execute([$_GET['s']])) {
    exit('Database error');
}
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

$pagetitle = $stream['title'];
$ogimage = image_print($stream['streamid'], 600);
include 'model/display.php';