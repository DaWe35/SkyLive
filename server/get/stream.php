<?php
header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
if (!isset($_GET['streamid']) || strlen($_GET['streamid']) < 1) {
    exit('Empty GET value: streamid');
}
$streamid = htmlspecialchars($_GET['streamid']);


$stmt = $db->prepare("SELECT `resolution` FROM `chunks` WHERE streamid = ? GROUP BY `resolution`");
if (!$stmt->execute([$_GET['streamid']])) {
    exit('Database error');
}
echo "#EXTM3U\n\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    switch ($row['resolution']) {
        case 360:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=577610,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=640x360' . "\n";
            echo "stream_resolution?streamid=" . $streamid . "&resolution=360\n\n";
            break;
        case 720:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1030138,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1280x720' . "\n";
            echo "stream_resolution?streamid=" . $streamid . "&resolution=720\n\n";
            break;
        case 1080:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1924009,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1920x1080' . "\n";
            echo "stream_resolution?streamid=" . $streamid . "&resolution=1080\n\n";
            break;
        case 'original':
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1924009,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1920x1080' . "\n";
            echo "stream_resolution?streamid=" . $streamid . "&resolution=original\n\n";
            break;
        
        default:
            echo '#EXT-X-STREAM-INF:BANDWIDTH=1924009,CODECS="mp4a.40.2, avc1.4d401f",RESOLUTION=1920x1080' . "\n";
            echo "stream_resolution?streamid=" . $streamid . "&resolution=1080\n\n";
            break;
    }
}
$stmt = null;


exit(); // Don't include model.display.php