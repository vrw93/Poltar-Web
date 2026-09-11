<?php
# ==================================================================
# | If you want to use this code change the file name by removing  |
# | ".example" in it and move it to actual folder outside .example |
# ==================================================================

function login($database, $username, $pws){
    $query = "
        your own query
    ";

    $statement = $database->prepare($query);

    /*$statement->bind_param(
        Your Param
    );*/

    $statement->execute();

    $result = $statement->get_result();

    $user = $result->fetch_assoc();

    if (!$user){
        return false;
    }

    if (password_verify($pws, $user['passwords'])){

        $_SESSION['role_id'] = $user['your column'];
        $_SESSION['user_id'] = $user['your column'];
        $_SESSION['username'] = $user['your column'];

        return true;
    }

    return false;
}
?>