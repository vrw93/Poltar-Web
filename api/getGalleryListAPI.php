<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/Gallery/getGalleryPost.php";

$admin = false;
$login = false;
$csrf = false;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = "N/a";

if(isset($data['limit']) && isset($data['offset'])){
    if(is_numeric($data['limit']) && is_numeric($data['offset'])){
        $limit = (int)$data['limit'];
        $offset = (int)$data['offset'];

        try{
            $blogPosts = getGalleryPostWithLimit(
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
        'data' => $blogPosts
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
}
?>