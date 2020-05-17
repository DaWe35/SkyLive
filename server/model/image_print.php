<?php
function image_print($image, $size = NULL) {
	if ($size == NULL) {
		$size = 200;
	}
	// return URL . 'thumbnails/' . $image . '_' . $size . '.jpg';
	return URL . 'thumbnails/' . $image . '.jpg';
}