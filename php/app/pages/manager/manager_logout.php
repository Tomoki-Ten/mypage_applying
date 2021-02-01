<?php
    session_start();
    session_regenerate_id();

    $_SESSION = [];
 
    if ($_COOKIE[session_name()] == true) {
        setcookie(session_name(),'',time()-42000,'/');
    }
 
    session_destroy();

    $message = 'ログアウトしました。';
     
    header('Location:/index.php?message=' . $message);
    exit();
