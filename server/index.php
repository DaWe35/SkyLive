<?php
require_once "config.php";
require_once "model/explode_url.php";
require_once "model/session.php";
require_once "model/image_print.php";
exploge_geturl($_GET['geturl']);
$subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

date_default_timezone_set('UTC');
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

/*
Schema:
domain.com / PAGE / SUBPAGE / SSPAGE / SSSPAGE
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require_once "model/referer.php";
	referer(); // Check http referrer - so hackers can't send external forms to chainscore
	if (file_exists('post/'.PAGE.'.php')) { include 'post/'.PAGE.'.php'; } //INCLUDE CONTROLLER
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (file_exists('get/'.PAGE.'.php')) { include 'get/'.PAGE.'.php'; } //INCLUDE CONTROLLER
	include 'model/display.php';
}