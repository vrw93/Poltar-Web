<?php 
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../logic/uploadImage.php";
require_once __DIR__ . "/../../logic/Gallery/createGalleryPost.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include __DIR__ . '/../authHelper.php';

    $isNewImage = false;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        if (!is_uploaded_file($_FILES['image']['tmp_name'])){
            die("File Invalid");
            exit();
        }
        
        $imagePath = uploadFile($_FILES['image'], "gallery");
        $isNewImage = true;
    }else {
        $imagePath = "";
    }

    if(!createGalleryPost(
        $database, 
        $_POST['title'], 
        $imagePath, 
        $_POST['summary'], 
        $_POST['description']
    )){
        if($isNewImage) delImage($imagePath, 'gallery');

        $type = '500';
        include __DIR__ . '/../../errorpage.php';
        exit;
    }

    header("Location: ../gallery");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Gallery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="../static/css/adminblog.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Create Gallery Post</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">
            Poltar SMK Negeri 1 Giritontro
        </h2>
        <?php include __DIR__ . "/../../components/navbar.php"?>

        <?php include __DIR__ . "/../components/galleryForm.php" ?>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>