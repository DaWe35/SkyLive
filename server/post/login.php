<?php
session_start();

if (empty($_POST['password'])):
    exit('Password is empty');
elseif (empty($_POST['email'])):
    exit('Email is empty');
endif;
$stmt = $db->prepare("SELECT id, password, name, rank, badlogin, loginban FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$_POST['email']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    exit('User not found');
} else if ($row['rank'] == 'unverified') {
    exit('This account is not verified');
} else if ($row['loginban'] > date("Y-m-d H:i:s")) {
    exit('Account suspended until ' . $row['loginban']);
} else if (password_verify($_POST['password'], $row['password'])) {
    echo $row['password'];
    $_SESSION['id'] = $row['id'];
    $_SESSION['rank'] = $row['rank'];
    $_SESSION['name'] = $row['name'];

    $stmt = $db->prepare("UPDATE users SET badlogin = 0 WHERE id = ?");
    if (!$stmt->execute([$row['id']])) {
        exit('Database error');
    }
    $stmt = null;

    header("Location: studio");
    
} else { // after 5 wrong login -> 1 hour ban, after 15 -> 10 hour ban.
    $stmt = $db->prepare("UPDATE users SET badlogin = ? WHERE id = ?");
    if (!$stmt->execute([$row['badlogin']+1, $row['id']])) {
        exit('Database error');
    }
    $stmt = null;
    $havetry = -($row['badlogin']+1-5);

    if ($row['badlogin']+1 >= 15) {
        $time_hour = date("Y-m-d H:i:s", strtotime('+10 hours'));
        $stmt = $db->prepare("UPDATE users SET loginban = ?, badlogin = 0 WHERE id = ?");
        if (!$stmt->execute([$time_hour, $row['id']])) {
            exit('Database error');
        }
        $stmt = null;
        echo 'Account suspended until ' . $time_hour;
    } else if ($row['badlogin']+2 >= 5) {
        $time_hour = date("Y-m-d H:i:s", strtotime('+1 hours'));
        $stmt = $db->prepare("UPDATE users SET loginban = ? WHERE id = ?");
        if (!$stmt->execute([$time_hour, $row['id']])) {
            exit('Database error 2');
        }
        $stmt = null;
        echo 'Account suspended until ' . $time_hour;
    } else {
        echo 'Wrong passwords, you can retry ' . $havetry . ' more time.';
    }

}
$stmt = null;