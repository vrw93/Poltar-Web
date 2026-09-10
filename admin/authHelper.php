<?php
$url = $_SERVER['REQUEST_URI'];

if (isset($_SESSION['user_id'])){
    if($_SESSION['role_id'] !== 1){
        $type = 'no_admin';
        include __DIR__ . '/../errorpage.php';
        exit();
    }
}else if(!isset($_SESSION['user_id'])){
    header("Location: /login?url=$url");
    exit();
}
?>