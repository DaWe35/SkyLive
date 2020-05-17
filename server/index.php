<?php
require_once "config.php";
require_once "model/explode_url.php";
require_once "model/image_print.php";

exploge_geturl($_GET['geturl']);
/*
Schema:
domain.com / PAGE / SUBPAGE / SSPAGE / SSSPAGE
*/

require_once "model/initialize.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$enable_CSRF = array('write');

	if (!in_array(PAGE, $enable_CSRF)) {
		require_once "model/referer.php";
		referer(); // Check http referrer - so hackers can't send external forms to chainscore
	}
	require_once "model/bruteforce_protection.php";
	bruteforce_check_ip();
	if (file_exists('post/'.PAGE.'.php')) { include 'post/'.PAGE.'.php'; } //INCLUDE CONTROLLER
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (file_exists('get/'.PAGE.'.php')) { include 'get/'.PAGE.'.php'; } //INCLUDE CONTROLLER
}