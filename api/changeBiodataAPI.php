<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/account/changeBiodata.php";

$admin = false;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = 'N/A';

if(isset($data['name']) && isset($data['noAbsen']) && isset($data['kelasId'])){
    if(is_string($data['name']) && is_numeric($data['noAbsen']) && is_numeric($data['kelasId'])){
        $userId = (int)$_SESSION['user_id'];
        $changeBiodata = new ChangeBiodata($database, $userId);
        
        #Html Escape
        $name = htmlspecialchars((string)$data['name'], ENT_QUOTES, 'UTF-8');
        $noAbsen = htmlspecialchars((string)$data['noAbsen'], ENT_QUOTES, 'UTF-8');
        $kelasId = htmlspecialchars((string)$data['kelasId'], ENT_QUOTES, 'UTF-8');

        $data = $changeBiodata->change(
            $name,
            $noAbsen,
            $kelasId
        );
        $result = $data['success'];
        $msg = $data['message'];
    }else{
        $result = false;
        $msg = 'Tipe Data Tidak Didukung';
    }
}else{
    $result = false;
    $msg = 'Data Tidak Lengkap';
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => $msg
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Gagal: ' . $msg
    ]);
}
?>