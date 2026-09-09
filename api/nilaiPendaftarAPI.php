<?php
include __DIR__ . "/../logic/registration/admin/nilaiPendaftarByPendaftarId.php";
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/registration/calculateStatus.php";

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;

if(isset($data['pendaftaranId']) && isset($data['sekor'])){
    if(is_numeric($data['pendaftaranId']) && is_numeric($data['sekor'])){
        $id = (int)$data['pendaftaranId'];
        $sekor = (float)$data['sekor'];

        if($sekor > 0 && $sekor <= 100){
            $result = nilaiPendaftarByPendaftaranId($database, $id, $sekor);
        }else{
            $result = false;
        }
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