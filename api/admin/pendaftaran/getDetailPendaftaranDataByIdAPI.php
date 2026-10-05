<?php
include __DIR__ . "/../../../logic/registration/getDetailPendaftaranDataByPendaftaranId.php";
include __DIR__ . "/../../../logic/database.php";
include __DIR__ . "/../../../logic/registration/calculateStatus.php";

$admin=true;$csrf=true;$login=true;
include __DIR__ . "/../../verifyHelper.php";

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
                $msg = 'Berhasil Mendapatkan Data';
            }else{
                $result = false;
                $msg = 'Kesalahan Internal';
            }
        }catch(Exception $e){
            $data = [];
            $result = false;
            $msg = 'Kesalahan Internal';
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
        'data' => $data
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>