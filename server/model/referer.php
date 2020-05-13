<?php
function referer() {
	if (isset($_SERVER['HTTP_REFERER'])) {
		$referer = parse_URL($_SERVER['HTTP_REFERER'], PHP_URL_SCHEME) . '://' . parse_URL($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
		$urlparse = parse_URL(URL, PHP_URL_SCHEME) . '://' . parse_URL(URL, PHP_URL_HOST);
		if ($referer !== $urlparse) { exit('referrer error: '.$referer.'  != '.$urlparse); }
	} else {
		exit('referrer error: no referrer');
	}
}