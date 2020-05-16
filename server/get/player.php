<?php

if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    $portal = htmlspecialchars($_GET['portal']);
} else {
    $portal = '';
}