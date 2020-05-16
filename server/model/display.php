<!DOCTYPE html>

<html lang="en">
    <head>
        <title><?= isset($pagetitle) ? $pagetitle : 'SkyLive' ?></title>
        <?php include "view/include/head.php"; ?>
    </head>
    <body>
        <div class="wrap">
            <?php include "view/include/header.php";
            
            if (file_exists('view/'.PAGE.'.php')) {
                include "view/".PAGE.".php";
            } else {
                header("HTTP/1.0 404 Not Found");
                include 'view/404.php';
            }
            ?>
        </div>
        <?php include "view/include/footer.php"; ?>
    </body>
</html>