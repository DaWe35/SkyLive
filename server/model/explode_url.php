<?php
function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function exploge_geturl($geturl) {

	$expgeturl = explode("/", $geturl);
	$geturl = preg_replace('/[^a-z0-9\.]/', '', strtolower($geturl));

	for ($i=0; $i < sizeof($expgeturl); $i++) { 
		$expgeturl[$i] = preg_replace("/(.+)\.php$/", "$1", $expgeturl[$i]); // remove .php from url cause its fckn buggy
	}

	if (isset($expgeturl[0]) && $expgeturl[0] != '') {
		if (endsWith($expgeturl[0], '.m3u8')) {
			$expgeturl[0] = substr_replace($expgeturl[0], "", -5);
		}
		define("PAGE", $expgeturl[0]);
	} else if (isset($_GET['page'])) {
		define("PAGE", $_GET['page']);
	} else {
		define("PAGE", 'index');
	}

	if (isset($expgeturl[1]) && $expgeturl[1] != '') {
		define("SUBPAGE", $expgeturl[1]);
	} else if (isset($_GET['subpage'])) {
		define("SUBPAGE", $_GET['subpage']);
	} else {
		define("SUBPAGE", '');
	}

	if (isset($expgeturl[2]) && $expgeturl[2] != '') {
		define("SSPAGE", $expgeturl[2]);
	} else if (isset($_GET['sspage'])) {
		define("SSPAGE", $_GET['sspage']);
	} else {
		define("SSPAGE", '');
	}

	if (isset($expgeturl[3]) && $expgeturl[3] != '') {
		define("SSSPAGE", $expgeturl[3]);
	} else if (isset($_GET['ssspage'])) {
		define("SSSPAGE", $_GET['ssspage']);
	} else {
		define("SSSPAGE", '');
	}
}
