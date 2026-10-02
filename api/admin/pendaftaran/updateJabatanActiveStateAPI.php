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

if(isset($data['id']) && isset($data['code'])){
    if(is_numeric($data['id']) && is_string($data['code'])){
        $id = (int)$data['id'];
        $code = (string)$data['code'];
        $reject = false;

        if($code !== 'active' || $code !== 'inactive'){
            $reject = true;
            $result = false;
            $msg = "Tipe State Tidak Didukung";
        }

        if(!$reject){
            try{
                $jabatanMgr = new jabatanManager($database);

                $jabatanMgr->updateActiveStateByCode(
                    $id,
                    $code
                );
                $result = true;
                $msg = "Berhasil Memperbarui Data";
            }catch(Throwable $e){
                $result = false;
                $msg = "Gagal Memperbarui Data";
            }
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
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>