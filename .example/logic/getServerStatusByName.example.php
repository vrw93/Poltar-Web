<?php
# ==================================================================
# | If you want to use this code change the file name by removing  |
# | ".example" in it and move it to actual folder outside .example |
# ==================================================================

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