<?php
include __DIR__ . "/../../../logic/database.php";
include __DIR__ . "/../../../logic/registration/admin/buildEvaluateData.php";

include __DIR__ . "/../../verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = "N/a";

if(isset($data['search'])){
    if(is_string($data['search'])){
        $search = (string)$data['search'];

        try{
            $buildEvaluateData = new BuildEvaluateData();
            $verifiedData = $buildEvaluateData->getVerifiedDataByNameSearch(
                $database,
                $search
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