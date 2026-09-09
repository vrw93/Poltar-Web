<?php
/*session_start();

if (!isset($_SESSION['admin_id'])){
    header("Location: ../login");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!isset($_SESSION['admin_id'])) {
        header("Location: ../login");
        exit();
    }

    $id = intval($_POST['Fid']);

    if(!empty($_POST['file'])){
        $targetDir = __DIR__ . "/../../uploads/blog/";

        $filename = basename($_POST['file']);

        $filePath = $targetDir . $filename;

        if(file_exists($filePath)){
            unlink($filePath);
        }else{
            die("file not found " . $filePath);
        }
    }else {
        die("empty");
    }
}*/

function delImage($file, string $type){
    if(!empty($file)){
        $targetDir = __DIR__ . "/../uploads/$type/";

        $filename = basename($file);

        $filePath = $targetDir . $filename;

        if(file_exists($filePath)){
            unlink($filePath);
        }else{
            die("file not found " . $filePath);
        }
    }
}
?>