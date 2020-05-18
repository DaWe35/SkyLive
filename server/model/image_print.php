<?php
function image_print($image, $size = NULL) {
	if ($size == NULL) {
		$size = 300;
	}
	return URL . 'thumbnails/' . $image . '_' . $size . '.jpg';
}