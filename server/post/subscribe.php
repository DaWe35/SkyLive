<?php
if (!isset($_POST['email']) || empty($_POST['email'])) {
    exit('Email is empty');
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!$email || empty($email)) {
    exit('Wrong email format');
}
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

$stmt = $db->prepare("INSERT INTO `newsletter` (`email`, `subscribtion_time`, `ip`) VALUES (?, ?, ?)");
if (!$stmt->execute([ $email, time(), ip2long($_SERVER['REMOTE_ADDR']) ])) {
    print_r($db->errorInfo());
    exit('Database error');
}
$stmt = null;

header('Location: ' . URL . '?subscribed');