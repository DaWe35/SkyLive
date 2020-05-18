<?php

$stmt = $db->prepare("UPDATE stream SET finished = 1 WHERE streamid = ? AND userid = ?");
if (!$stmt->execute([$_POST['streamid'], $_SESSION['id']])) {
    exit('Database error');
}
$stmt = null;
echo 'ok';