<?php
function bruteforce_check_ip() {
    /*
    $banned = ip_attempts > 10
    if ($banned) {
        header('HTTP/1.0 403 Forbidden');
        exit('Access denied');
    } else {
        return $number;
    }
    */
}

function bruteforce_increase_failed_attempts() {
    /*
    increase ip attempts in DB. 
    */
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}