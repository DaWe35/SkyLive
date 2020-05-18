<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: /login');
    exit();
}

if (SUBPAGE == '') {
    include 'post/studio/index.php';
} else {
    include 'post/studio/' . SUBPAGE . '.php';
}