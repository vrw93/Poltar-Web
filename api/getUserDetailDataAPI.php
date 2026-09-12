<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/getDataFromDB.php";

$getDB = new getDbData();

$admin = true;$csrf = true;$login = true;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = "N/a";

if(isset($data['userId'])){
    if(is_numeric($data['userId'])){
        $userId = (int)$data['userId'];

        try{
            $userDetailData = $getDB->getUserDetailData($database, $userId);
            
            $result = true;
            $msg = "Berhasil Mengambil Data Dari Database";
        }catch(Throwable $e){
            $result = false;
            $msg = "Terjadi Kesalahan Internal";
        }
    }else{
        $result = false;
        $msg = "Tipe Data Tidak Didukung";
    }
}else{
    $result = false;
    $msg = "Data Tidak Lengkap";
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => $msg,
        'data' => $userDetailData
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>