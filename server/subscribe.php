<?php
$file = "subscriptions/mail_list.php";
if (!isset($_POST['email']) || empty($_POST['email'])) {
    exit('Email is empty');
}

if (!file_exists($file)) {
    if (!touch($file)) {
        exit('Error: cannot touch the file');
    }
    file_put_contents($file,"<?php /*\n", FILE_APPEND);
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (empty($email)) {
    exit('Wrong email format');
}
if (!file_put_contents($file, $email . "\n", FILE_APPEND)) {
    exit('Sorry, there is an unexpected error');
}
header('Location: index.php?subscribed');