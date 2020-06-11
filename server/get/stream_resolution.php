<?php

header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
if (!isset($_GET['streamid']) || strlen($_GET['streamid']) < 1) {
    exit('Empty GET value: streamid');
}

if (!isset($_GET['resolution']) || empty($_GET['resolution'])) {
    exit('Empty GET value: resolution');
}

$stmt = $db->prepare("SELECT `streamid`, `userid`, `title`, `description`, `scheule_time`, `started`, `finished` FROM `stream` WHERE streamid = ? LIMIT 1");
if (!$stmt->execute([$_GET['streamid']])) {
    exit('Database error');
}
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

if (!isset($stream['streamid'])) {
    http_response_code(404);
    exit('Stream not found');
}

if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    $portal = filter_var($_GET['portal'], FILTER_SANITIZE_URL);
} else {
    $portal = 'https://helsinki.siasky.net';
}

?>
#EXTM3U
#EXT-X-VERSION:3
#EXT-X-TARGETDURATION:10
#EXT-X-MEDIA-SEQUENCE:0
#EXT-X-PLAYLIST-TYPE:VOD

<?php
if ($stream['finished'] == 0) {
    echo "#EXTINF:10.000000,\n";
    echo $portal . "/AABa67V-0McynqzloU6CBvHECTY-R8mm6SaB1Smr2pkU1g\n";
    echo "#EXTINF:10.000000,\n";
    echo $portal . "/_B2H5ZepchMfdYj1BLjlkZqsGUEhXEA-rxi--80i-1mEGA\n";
    echo "#EXTINF:4.000000,\n";
    echo $portal . "/PAO6OSO_yznBInoTofjORkLxBwxIRuPMzg3C3QwFaDcjKg\n";
    echo "#EXT-X-DISCONTINUITY\n";
}

$stmt = $db->prepare("SELECT `id`, `streamid`, `length`, `skylink`, `is_first_chunk` FROM `chunks` WHERE streamid = ? AND resolution = ? ORDER BY id ASC");
if (!$stmt->execute([$stream['streamid'], $_GET['resolution']])) {
    exit('Database error');
}
$start_chunk = true;
while ($chunk = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($chunk['is_first_chunk'] == 1 && $start_chunk == false) {
        echo "#EXT-X-DISCONTINUITY\n";
    }
    echo "#EXTINF:{$chunk['length']},\n";
    echo "{$portal}/{$chunk['skylink']}\n";
    $start_chunk = false;
}
$stmt = null;


if ($stream['finished'] == 1) {
    echo "#EXT-X-ENDLIST\n";
}