<?php
$file = "mail_list.php";
if (!isset($_POST['email']) || empty($_POST['email'])) {
    exit('Email is empty');
}

if (!file_exists($file)) {
    touch($file);
    file_put_contents($file,"<?php\n", FILE_APPEND);
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (empty($email)) {
    exit('Wrong email format');
}
file_put_contents($file, $email . "\n", FILE_APPEND);
header('Location: index.php?subscribed');