<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/account/changePassword.php";

$admin = false;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = 'N/A';

if(isset($data['oldPassword']) && isset($data['newPassword']) && isset($data['confirmPassword'])){
    if(is_string($data['oldPassword']) && is_string($data['newPassword']) && is_string($data['confirmPassword'])){
        $changePassword = new ChangeUserPassword($database);
        $userId = (int)$_SESSION['user_id'];

        $data = $changePassword->changePassword($userId, 
            $data['oldPassword'], $data['newPassword'], 
            $data['confirmPassword']);
        $result = $data['success'];
        $msg = $data['message'];
    }else{
        $result = false;
        $msg = 'Tipe Data Tidak Didukung';
    }
}else{
    $result = false;
    $msg = 'Data Tidak Lengkap';
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => $msg
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Gagal: ' . $msg
    ]);
}
?>