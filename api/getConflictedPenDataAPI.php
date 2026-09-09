<?php
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/registration/admin/buildEvaluateData.php";

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = "N/a";

try{
    $buildEvaluateData = new BuildEvaluateData();
    $conflictedData = $buildEvaluateData->getConflictedData($database);
    
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
        'data' => $conflictedData
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>