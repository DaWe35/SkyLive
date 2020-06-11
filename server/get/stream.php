<?php
header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
if (!isset($_GET['streamid']) || strlen($_GET['streamid']) < 1) {
    exit('Empty GET value: streamid');
}
$streamid = htmlspecialchars($_GET['streamid']);

//backward compatibility
switch ($streamid) {
    case 'skylive':
        $streamid = 1;
        break;
    case 'starlink':
        $streamid = 2;
        break;
    case 'obws':
        $streamid = 3;
        break;
    case 'podcast':
        $streamid = 4;
        break;
    case 'workshop':
        $streamid = 5;
        break;
    case 'obws2':
        $streamid = 6;
        break;
    case 'obws3':
        $streamid = 7;
        break;
}


$stmt = $db->prepare("SELECT `resolution` FROM `chunks` WHERE streamid = ? GROUP BY `resolution`");
if (!$stmt->execute([$streamid])) {
    exit('Database error');
}

if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    $portal = filter_var($_GET['portal'], FILTER_SANITIZE_URL);
} else {
    $portal = 'https://helsinki.siasky.net';
}


echo "#EXTM3U\n\n";
$rowcount = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    switch ($row['resolution']) {
        case 360:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=577610,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=640x360' . "\n";
            echo "../stream_resolution?streamid=" . $streamid . "&resolution=360&portal={$portal}\n\n";
            break;
        case 720:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1030138,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1280x720' . "\n";
            echo "../stream_resolution?streamid=" . $streamid . "&resolution=720&portal={$portal}\n\n";
            break;
        case 1080:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1924009,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1920x1080' . "\n";
            echo "../stream_resolution?streamid=" . $streamid . "&resolution=1080&portal={$portal}\n\n";
            break;
        case 'original':
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1924009,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1920x1080' . "\n";
            echo "../stream_resolution?streamid=" . $streamid . "&resolution=original&portal={$portal}\n\n";
            break;
    }
    $rowcount += 1;
}

// print original if stream not started
if ($rowcount === 0) {
    echo '#EXT-X-STREAM-INF:BANDWIDTH=1924009,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1920x1080' . "\n";
    echo "../stream_resolution?streamid=" . $streamid . "&resolution=original&portal={$portal}\n\n";
}

$stmt = null;