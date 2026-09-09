<?php
session_start();

$token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
$needCSRF = $csrf ?? true;
$needAdmin = $admin ?? true;
$needLogin = $login ?? true;

if($needCSRF){
    
    if (
        !isset($_SESSION['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $token
        )
    ){
        http_response_code(404);
        exit();
    }
}

if($needLogin){
    if(isset($_SESSION['user_id'])){
        if($_SESSION['role_id'] !== 1 && $needAdmin){
            http_response_code(403);
            exit();
        }
    }else if(!isset($_SESSION['user_id'])){
        http_response_code(404);
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit();
}
?>