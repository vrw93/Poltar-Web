<?php
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/getDataFromDB.php";

include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = "N/a";

if(isset($data['offset']) && isset($data['limit']) && isset($data['criteria'])){
    if(is_numeric($data['offset']) && is_numeric($data['limit']) && is_string($data['criteria'])){
        $offset = (int)$data['offset'];
        $limit = (int)$data['limit'];
        $criteria = (string)$data['criteria'];

        try{
            $getDBData = new getDbData();
            $inactiveData = $getDBData->getUserDataUpdateRequest(
                $database,
                $criteria,
                $limit,
                $offset
            );
            
            $result = true;
            $msg = "Berhasil Mengambil Data Dari Database";
        }catch(Throwable $e){
            $result = false;
            $msg = "Terjadi Kesalahan Internal";
            error_log($e);
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
        'data' => $inactiveData
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg,
    ]);
}
?>