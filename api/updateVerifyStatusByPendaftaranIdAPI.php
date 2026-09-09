<?php
include __DIR__ . "/../logic/registration/admin/updateUserStatusByPendaftarId.php";
include __DIR__ . "/../logic/database.php";

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
if(isset($data['id']) && isset($data['status_id'])){
    if(is_numeric($data['id']) && is_numeric($data['status_id'])){
        $id = (int)$data['id'];
        $state = (int)$data['status_id'];
        
        $result = updateUserStatusByPendaftarId($database, $id, $state);
    }else{
        $result = false;
    }
}else{
    $result = false;
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => 'Berhasil Memperbarui Data'
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Gagal Memperbarui Data'
    ]);
}
?>