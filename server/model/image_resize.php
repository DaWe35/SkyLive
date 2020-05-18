<?php
function resize($filename, $uniqid, $upload_folder, $source, $size) {
    list($width, $height) = getimagesize($filename);
    if ($width > $size) { $newwidth = $size; } else { $newwidth = $width; $sizestop = 1; }
    $size_ratio = $width / $newwidth;
    $newheight = $height / $size_ratio;
    if ($newheight > 2000) { $newheight = 2000; }
    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $whiteBackground = imagecolorallocate($thumb, 255, 255, 255);
    imagefill($thumb,0,0,$whiteBackground);

    $target_file = $upload_folder . $uniqid . '_'.$size.'.jpg';
    // Resize
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    if (imagejpeg($thumb, $target_file, '96')) {
        // echo $size . " ok. ";
        $imgshowsize = '_'.$size.'.jpg';
    } else {
        echo $size." error. ";
    }
}

function save_image($name, $upload_folder) {
    if (!empty($_FILES)) {
            
        $imageFileType = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "bmp" && $imageFileType != "webp" ) {
            exit('Sorry, only JPG, PNG, WEBP, BMP & GIF files are allowed.');
        }

        if ($_FILES["file"]["tmp_name"]) {
            $filename = $_FILES['file']['tmp_name'];
        } else {
            exit('Upload failed - configuration error');
        }

        if ($imageFileType == 'jpg' or $imageFileType == 'jpeg') {
            $source = imagecreatefromjpeg($filename);
        } else  if ($imageFileType == 'png') {
            $source = imagecreatefrompng($filename);
        } else  if ($imageFileType == 'gif') {
            $source = imagecreatefromgif($filename);
        } else  if ($imageFileType == 'webp') {
            $source = imagecreatefromwebp($filename);
        } else  if ($imageFileType == 'bmp') {
            $source = imagecreatefromwbmp($filename);
        } else {
            $source = 0;
        }

        resize($filename, $name, $upload_folder, $source, '150');
        resize($filename, $name, $upload_folder, $source, '300');
        resize($filename, $name, $upload_folder, $source, '600');
        resize($filename, $name, $upload_folder, $source, '1920');
        return true;

    } else {
        header("HTTP/1.0 400 Bad Request");
        exit('empty files');
    }
}