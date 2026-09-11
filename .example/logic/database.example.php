<?php
# ==================================================================
# | If you want to use this code change the file name by removing  |
# | ".example" in it and move it to actual folder outside .example |
# ==================================================================

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$database = new mysqli(
    "Your Host",
    "Your Username",
    "Your Password",
    "Your Database"
);

if ($database->connect_error) {

    die("Connection failed");

}

?>
