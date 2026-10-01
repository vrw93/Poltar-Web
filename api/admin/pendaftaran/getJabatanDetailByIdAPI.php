<?php
include __DIR__ . "/../../../logic/database.php";
include __DIR__ . "/../../../logic/registration/admin/jabatanManager.php";

$admin=true;$login=true;$csrf=true;
include __DIR__ . "/../../verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;

if(isset($data['id'])){
    if(is_numeric($data['id'])){
        $id = (int)$data['id'];

        try{
            $jabatanMgr = new jabatanManager($database);

            $detailData = $jabatanMgr->getJabatanDetailById($id);
            $result = true;
            $msg = "Berhasil Mengambil Data Dari Database";
        }catch(Throwable $e){
            $result = false;
            $msg = "Gagal Mengambil Data Dari Database";
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
        'data' => $detailData
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>