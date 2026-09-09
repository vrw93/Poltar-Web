<?php
include __DIR__ . "/../logic/registration/getDetailPendaftaranDataByPendaftaranId.php";
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/registration/calculateStatus.php";

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;

if(isset($data['pendaftaranId'])){
    if(is_numeric($data['pendaftaranId'])){
        $id = (int)$data['pendaftaranId'];

        try{
            $data = getDetailPendaftaranDataByPendaftaranId($database, $id);
            if($data !== null){
                $result = true;
            }else{
                $result = false;
            }
        }catch(Exception $e){
            $data = [];
            $result = false;
        }
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
        'message' => 'Berhasil Mendapatkan Data',
        'data' => $data
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Gagal Mendapatkan Data. Silahkan hubungi admin',
        'data' => $data
    ]);
}
?>