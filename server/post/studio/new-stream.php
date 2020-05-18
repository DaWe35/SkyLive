<?php

function random_str(int $length = 128, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

$token = random_str();

$stmt = $db->prepare("INSERT INTO `stream`(`token`, `userid`, `title`, `description`, `scheule_time`, `visibility`) VALUES (?, ?, ?, ?, ?,  ?)");

if (!$stmt->execute([$token, $_SESSION['id'], $_POST['title'], $_POST['description'], $_POST['scheule_time'], $_POST['visibility']])) {
    // print_r($stmt->errorInfo());
    exit('Database error');
}

require('model/image_resize.php');
save_image($db->lastInsertId(), $upload_folder);
header('Location: /studio');