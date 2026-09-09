<?php
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/enum/updateUserDataEnum.php";
require_once __DIR__ . "/../logic/account/updateDBData.php";

require_once __DIR__ . "/verifyHelper.php";

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$result = false;
$msg = 'N/A';

if(isset($data['userId']) && isset($data['requestId']) && isset($data['detailId'])
&& isset($data['field']) && isset($data['value']) && isset($data['status']))
{
    if(is_numeric($data['userId']) && is_numeric($data['requestId']) 
        && is_numeric($data['detailId']) && is_string($data['field']) 
        && is_string($data['value']) && is_string($data['status']))
    {
        $userId = (int)$data['userId'];
        $requestId = (int)$data['requestId'];
        $detailId = (int)$data['detailId'];
        $value = (string)$data['value'];

        try{
            $updateDb = new UpdateDbData();
            $status = Status::tryFrom($data['status']);
            $field = UserField::tryFrom($data['field']);

            $data = $updateDb->update(
                $database,
                $userId,
                $requestId,
                $detailId,
                $field,
                $value,
                $status
            );
            $result = $data['success'];
            $msg = $data['message'];
        }catch(Exception $e){
            $result = false;
            $msg = $e->getMessage();
        }
    }else{
        $result = false;
        $msg = 'Invalid Data Type';
    }
}else{
    $result = false;
    $msg = 'Missing Required Data';
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
        'message' => 'Gagal Memperbarui Data: ' . $msg,
    ]);
}
?>