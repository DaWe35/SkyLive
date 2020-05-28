<?php
if ($_SESSION['id'] != 1) {
	exit('Access denied');
}
$stmt = $db->prepare("SELECT skylink, streamid, resolution, id FROM `chunks` WHERE 1");
if (!$stmt->execute()) {
    exit('Database error');
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo 'siac skynet pin ' . $row['skylink'] . ' skylive/' . $row['streamid'] . '_' . $row['resolution'] . '/' . $row['id'] . '<br>';
}