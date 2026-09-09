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

if(isset($data['offset']) && isset($data['limit'])){
    if(is_numeric($data['offset']) && is_numeric($data['limit'])){
        $offset = (int)$data['offset'];
        $limit = (int)$data['limit'];

        try{
            $buildEvaluateData = new BuildEvaluateData();
            $verifiedData = $buildEvaluateData->getVerifiedData(
                $database,
                $limit,
                $offset
            );
            
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
        'data' => $verifiedData
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>