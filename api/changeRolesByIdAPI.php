<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/account/changeRole.php";

$admin = true;$csrf = true;$login = true;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = 'N/A';

if(isset($data['roleId']) && isset($data['userId'])){
    if(is_numeric($data['roleId']) && is_numeric($data['userId'])){
        $userId = (int)$data['userId'];
        $id = (int)$data['roleId'];
        $changeRole = new changeRole($database, $userId);

        $result = $changeRole->changeById($id);
        if($result){
            $msg = "Berhasil Memperbarui Data";
        }else{
            $msg = "Kesalahan Internal";
        }
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