<?php

// Check is user logged in
if (isset($_SESSION['id'])) {
    echo 'logged in';
} else {
    echo 'out';
}

exit();