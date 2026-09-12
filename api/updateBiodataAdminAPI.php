<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/account/changeBiodataAdmin.php";

$admin = true;$csrf = true;$login = true;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = 'N/A';

if(isset($data['name']) && isset($data['noAbsen']) 
    && isset($data['kelasId']) && isset($data['userId'])
){
    if(is_string($data['name']) && is_numeric($data['noAbsen']) 
        && is_numeric($data['kelasId']) && is_numeric($data['userId'])
    ){
        $userId = (int)$userId;    

        #Html Escape
        $name = htmlspecialchars((string)$data['name'], ENT_QUOTES, 'UTF-8');
        $noAbsen = (int)htmlspecialchars($data['noAbsen'], ENT_QUOTES, 'UTF-8');
        $kelasId = (int)htmlspecialchars($data['kelasId'], ENT_QUOTES, 'UTF-8');

        $result = updateBiodataAdmin(
            $database,
            $name,
            $noAbsen,
            $kelasId,
            $userId
        );
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