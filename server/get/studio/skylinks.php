<?php
if ($_SESSION['id'] != 1) {
	exit('Access denied');
}

$stmt = $db->prepare("SELECT streamid FROM `stream` WHERE visibility = 'public'");
if (!$stmt->execute()) {
    exit('Database error');
}

$public_ids = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$public_ids[] = $row['streamid'];
}

$public_ids_str = implode(', ', $public_ids);

$stmt = $db->prepare("SELECT skylink, streamid, resolution, id FROM `chunks` WHERE streamid IN (" . $public_ids_str . ")");
if (!$stmt->execute()) {
    exit('Database error');
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo 'siac skynet pin ' . $row['skylink'] . ' skylive/' . $row['streamid'] . '_' . $row['resolution'] . '/' . $row['id'] . '<br>';
}