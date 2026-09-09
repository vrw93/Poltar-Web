<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/account/cancelRequest.php";
require_once __DIR__ . '/../logic/getStatusNameByCode.php';

$admin = false;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = 'N/A';

if(isset($data['requestId'])){
    if(is_numeric($data['requestId'])){
        $userId = (int)$_SESSION['user_id'];
        $requestId = (int)$data['requestId'];

        try{
            cancelRequestByRequestId($database, $userId, $requestId);
            $result = true;
            $msg = 'Berhasil Membatalkan Permintaan #' . $requestId;
            $statusName = getStatusNameByCode($database,'cancel');
        }catch(Throwable $e){
            $result = false;
            $msg = 'Gagal Membatalkan Permintaan #' . $requestId;
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
        'message' => $msg,
        'data' => $statusName
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>