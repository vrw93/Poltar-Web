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

if(isset($data['name']) && isset($data['priority']) 
    && isset($data['kuota']) && isset($data['icons'])){
    if(is_string($data['name']) && is_numeric($data['priority'])
        && is_numeric($data['kuota']) && is_string($data['icons'])){
        $kuota = (int)$data['kuota'];
        $priority = (int)$data['priority'];
        $name = (string)htmlspecialchars($data['name']);
        $icons = (string)htmlspecialchars($data['icons']);
        $reject = false;

        if($kuota <= 0 || $priority <= 0){
            $reject = true;
            $result = false;
            $msg = "Kuota/Priority Tidak Boleh Kurang Dari 1";
        }

        if(mb_strlen($name, 'UTF-8') > 100 || mb_strlen($icons, 'UTF-8') > 30){
            $reject = true;
            $result = false;
            $msg = "Data Terlalu Besar";
        }

        if(!$reject){
            try{
                $jabatanMgr = new jabatanManager($database);

                $result = $jabatanMgr->createJabatan(
                    $name,
                    $kuota,
                    $priority,
                    $icons
                );
                if($result){
                    $msg = "Berhasil Membuat Jabatan";
                }else{ 
                    $msg = "Gagal Membuat Jabatan";
                }
            }catch(Throwable $e){
                $result = false;
                $msg = "Gagal Membuat Jabatan";
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