<?php
#include __DIR__ . "/../logic/registration/components/updatePilihanStatusWithCodeByPendaftaranIdAndJabatanId.php";
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/registration/calculateStatus.php";

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;

if(isset($data['pendaftaranId']) && isset($data['jabatanId']) && isset($data['status'])){
    if(is_numeric($data['pendaftaranId']) && is_numeric($data['jabatanId']) && is_string($data['status'])){
        $id = (int)$data['pendaftaranId'];
        $jabatanId = (int)$data['jabatanId'];
        $status = (string)$data['status'];

        $result = updatePilihanStatusWithCodeByPendaftaranIdAndJabatanId(
            $database,
            $status,
            $id,
            $jabatanId
        );
    }else{
        $result = false;
    }
}else{
    $result = false;
}
$calculate = new CalculatePilihanStatus();
$calculate->main($database);

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