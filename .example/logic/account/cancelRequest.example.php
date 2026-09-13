<?php
function cancelRequestByRequestId(mysqli $database,int $userId, int $requestId){
    $query = "UPDATE YourTable<update request> 
    SET YourColumn <active state> = 0,
        YourColumn <id status> = (SELECT YourColumn FROM Status WHERE YourColumn = 'cancel') 
        WHERE YourColumn < user id > = ? AND id = ?
    ";

    $statement = $database->prepare($query);
    $statement->bind_param(
        'ii',
        $userId,
        $requestId
    );
    $statement->execute();
    $statement->close();

    $query = "UPDATE YourTable <update detail> 
        SET YourColumn = (SELECT YourColumn FROM YourTable WHERE YourColumn = 'cancel')
        WHERE YourColumn = ? 
        AND YourColumn = (SELECT YourColumn FROM YourTable WHERE YourColumn = 'pending')
    ";
    $statement = $database->prepare($query);
    $statement->bind_param(
        'i',
        $requestId
    );
    $statement->execute();
    $statement->close();
}
?>