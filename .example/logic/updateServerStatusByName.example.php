<?php
# ==================================================================
# | If you want to use this code change the file name by removing  |
# | ".example" in it and move it to actual folder outside .example |
# ==================================================================

function updateServerStatusByName($database, $name, $statusCode){
    $query = '
        UPDATE YourTable<SaveServerStatus>
        SET YourTable.YourColumn<statusId> = (SELECT YourTable.YourColumn<statusId> FROM YourTable<status> WHERE YourTable.YourColumn<code> = ?)
        WHERE YourTable.YourColumn = ?
    ';

    $statement = $database->prepare($query);
    $statement->bind_param('ss', $statusCode, $name);
    return $statement->execute();
}
?>