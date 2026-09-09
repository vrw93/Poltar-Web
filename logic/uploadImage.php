<?php
function uploadFile($file, $dir){
    //Config
    $maxSize = 5 * 1024 * 1024;

    $allowedMimeTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    $uploadDir = __DIR__ . "/../uploads/$dir/";

    if($file['error'] !== 0){
        die("Upload File Failed");
    }

    if($file['size'] > $maxSize){
        die("Upload Failed, File To Large!!");
    }

    $mimeType = mime_content_type($file['tmp_name']);

    if (!in_array($mimeType, $allowedMimeTypes)){
        die("Invalid File Type");
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    $safeFileName = bin2hex(random_bytes(16)) . "." . strtolower($extension);

    $targetFile = $uploadDir . $safeFileName;

    if(!move_uploaded_file($file['tmp_name'], $targetFile)){
        die("Failed to Move Uploaded File");
    }

    return "/uploads/$dir/" . $safeFileName;
}
?>