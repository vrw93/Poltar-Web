<?php
include __DIR__ . "/../logic/getDataFromDB.php";
include __DIR__ . "/../logic/database.php";

$admin = false;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$result = false;
$errMsg = 'N/A';
if(isset($data['id'])){
    if(is_numeric($data['id'])){
        $getdata = new getDbData();
        $reqId = (int)$data['id'];

        $datas = $getdata->getUserUpdateRequestDetail($database, $reqId);
        $result = true;
    }else{
        $result = false;
        $errMsg = 'Tipe Data Tidak Didukung';
    }
}else{
    $result = false;
    $errMsg = 'Data Tidak Lengkap';
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => 'Berhasil Memperbarui Data',
        'data' => $datas
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Gagal Error Code: ' . $errMsg
    ]);
}
?>