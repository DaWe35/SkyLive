<?php
if (!isset($displayPage)) {
    $displayPage = SUBPAGE;
} ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title><?= isset($pagetitle) ? $pagetitle : 'SkyLive' ?></title>
        <?php include "view/include_studio/head.php"; ?>
    </head>
    <body>
        <div class="wrap">
            <?php include "view/include_studio/nav.php";
            if (file_exists('view/studio/'.$displayPage.'.php')) {
                include "view/studio/".$displayPage.".php";
            } else {
                header("HTTP/1.0 404 Not Found");
                include 'view/404.php';
            }
            ?>
        </div>
    </body>
</html>