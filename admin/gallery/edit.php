<?php
$currentPage = "";

include "../../logic/database.php";
include "../../data/menudb.php";
include "../../logic/Gallery/getGalleryPostById.php";
include "../../logic/Gallery/updateGallery.php";
include "../../logic/uploadImage.php";
include "../../logic/deleteImage.php";
include "../../data/adminmenudb.php";

session_start();

include __DIR__ . '/../authHelper.php';

$id = intval($_GET['id']);

$galleryPost = getGalleryPostById($database, $id);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    include __DIR__ . '/../authHelper.php';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        if (!is_uploaded_file($_FILES['image']['tmp_name'])){
            die("File Invalid");
            exit();
        }
        
        $imagePath = uploadFile($_FILES['image'], "gallery");
        delImage($galleryPost['image'], 'gallery');
    }else {
        $imagePath = $galleryPost['image'];
    }

    updateGalleryPost(
        $database,
        $id,
        $_POST['title'],
        $imagePath,
        $_POST['summary'],
        $_POST['description']
    );

    header("Location: /admin/gallery");
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
        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <?php include "../components/galleryForm.php"; ?>

        <?php include "../../components/footer.php"; ?>
        
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>