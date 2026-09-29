<?php
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/registration/admin/getFullJabatanData.php";

$admin=true;$login=true;$csrf=true;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = "N/a";

try{
    $jabatanMgr = new JabatanManager($database);
    $fullJabatanData = $jabatanMgr->getFullActiveData();
    
    $result = true;
    $msg = "Berhasil Mengambil Data Dari Database";
}catch(Throwable $e){
    $result = false;
    $msg = "Terjadi Kesalahan Internal";
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => $msg,
        'data' => $fullJabatanData
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>