<?php

$streams_stmt = $db->prepare("SELECT `streamid`, `title`, `description`, `scheule_time`, `started`, `finished` FROM `stream` WHERE visibility = 'public' ORDER BY scheule_time DESC, streamid DESC  LIMIT 100");
if (!$streams_stmt->execute()) {
    exit('Database error');
}

include 'model/display.php';