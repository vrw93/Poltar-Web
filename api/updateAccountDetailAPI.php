<?php
include __DIR__ . "/../logic/account/updateAccountInfo.php";
include __DIR__ . "/../logic/database.php";

$admin = false;
include __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$result = false;
$Msg = 'N/A';
if(isset($data['email']) && isset($data['username']))
{
    if(is_string($data['email']) && is_string($data['username']))
    {
        $update = new UpdateAccountInfo();
        $id = $_SESSION['user_id'];
        $readyData['email'] = htmlspecialchars((string)$data['email'], ENT_QUOTES, 'UTF-8');
        $readyData['username'] = htmlspecialchars((string)$data['username'], ENT_QUOTES, 'UTF-8');

        $return = $update->update($database, $id, $readyData);
        $result = $return['success'];
        $Msg = $return['message'];
    }else{
        $result = false;
        $Msg = 'Tipe Data Tidak Didukung';
    }
}else{
    $result = false;
    $Msg = 'Data Tidak Lengkap';
}

header('Content-Type: application/json');
if($result){
    echo json_encode([
        'success' => true,
        'message' => $Msg
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Gagal: ' . $Msg
    ]);
}
?>