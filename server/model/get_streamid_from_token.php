<?php
function get_streamid_from_token($token) {
    global $db;
    $stmt = $db->prepare("SELECT streamid FROM stream WHERE token = ? LIMIT 1");
    if (!$stmt->execute([$token])) {
        exit('Database error');
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;

    if (isset($row['streamid'])) {
        return $row['streamid'];
    } else {
        bruteforce_increase_failed_attempts();
    }
}