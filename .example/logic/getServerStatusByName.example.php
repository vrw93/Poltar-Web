<?php
function getServerStatusByName($database, $name){
    $query = '
        SELECT 
            YourTable.YourColumn,
            YourTable.YourColumn AS status,
            YourTable.YourColumn AS statusCode
        FROM `YourTable`

        JOIN YourTable
        ON YourTable.id = YourStatusId

        WHERE YourServerStatusTable.name = ?
    ';

    $statement = $database->prepare($query);
    $statement->bind_param('s', $name);
    $statement->execute();
    $result = $statement->get_result();
    return $result->fetch_assoc();
}