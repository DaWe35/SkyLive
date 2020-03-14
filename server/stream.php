<?php

header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
if (!isset($_GET['streamid']) || strlen($_GET['streamid']) <= 1) {
    exit('Empty GET value: streamid');
}
$streamid = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['streamid']);
if (empty($streamid) || strlen($streamid) <= 1) {
    exit('Wrong stream id');
}

$myfile = fopen("streams/" . $streamid. ".txt", "r") or die("Unable to open file!");

?>
#EXTM3U
#EXT-X-VERSION:3
#EXT-X-TARGETDURATION:12
#EXT-X-MEDIA-SEQUENCE:0

<?php
$content = fread($myfile,filesize("streams/" . $streamid. ".txt"));
if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    
    $content = str_replace("https://siasky.net",$_GET['portal'],$content);

}
echo $content;
fclose($myfile);