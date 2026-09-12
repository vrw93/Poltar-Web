<?php
# ==================================================================
# | If you want to use this code change the file name by removing  |
# | ".example" in it and move it to actual folder outside .example |
# ==================================================================

function getStatusNameByCode(mysqli $database, string $code){
    $query = "SELECT YourColumn FROM YourTable WHERE code = ?";

    $statement = $database->prepare($query);
    $statement->bind_param('s', $code);
    $statement->execute();
    $result = $statement->get_result();
    return $result->fetch_assoc();
}
?>