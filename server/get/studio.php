<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: login');
    exit();
}

if (SUBPAGE == '') {
    include 'studio/index.php';
} else {
    include 'studio/' . SUBPAGE . '.php';
}