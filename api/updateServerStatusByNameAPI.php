<?php
include __DIR__ . '/../logic/updateServerStatusByName.php';
include __DIR__ . '/../logic/database.php';

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$state = $data['statusCode'] == 'reg_opened' ? 'Membuka' : 'Menutup';

$result = false;
if(isset($data['name']) && isset($data['statusCode'])){
    if(is_string($data['name']) && is_string($data['statusCode'])){
        $name = (string)$data['name'];
        $code = (string)$data['statusCode'];

        $result = updateServerStatusByName($database, $name, $code);
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
        'message' => "Berhasil $state Pendaftaran"
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => "Gagal $state Pendaftaran"
    ]);
}
?>