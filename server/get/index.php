<?php

$streams_stmt = $db->prepare("SELECT `streamid`, `title`, `description`, `scheule_time`, `started`, `finished` FROM `stream` WHERE 1 ORDER BY streamid DESC LIMIT 100");
if (!$streams_stmt->execute()) {
    exit('Database error');
}