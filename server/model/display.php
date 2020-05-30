<?php
if (!isset($displayPage)) {
    $displayPage = PAGE;
} ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <title><?= isset($pagetitle) ? $pagetitle : 'SkyLive' ?></title>
        <meta property="og:image" content="<?= isset($ogimage) ? $ogimage : URL . 'assets/logos/logo1.jpg' ?>" />
        <?php include "view/include/head.php"; ?>
    </head>
    <body>
        <div class="wrap">
            <?php include "view/include/header.php";
            
            if (file_exists('view/'.$displayPage.'.php')) {
                include "view/".$displayPage.".php";
            } else {
                header("HTTP/1.0 404 Not Found");
                include 'view/404.php';
            }
            ?>
        </div>
        <?php include "view/include/footer.php"; ?>
    </body>
</html>