<?php
session_start();

if (isset($_SESSION['user_id'])){
    if($_SESSION['role_id'] !== 1){
        header("Location: ../../login?url=/admin/blog/create");
        exit();
    }
}else if(!isset($_SESSION['user_id'])){
    header("Location: ../../login?url=/admin/blog/create");
    exit();
}

$currentPage = "";

include "../../logic/Blogs/createBlogPost.php";
include "../../logic/database.php";
include "../../data/adminmenudb.php";
include "../../logic/uploadImage.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])){
        if($_SESSION['role_id'] !== 1){
            header("Location: /login?url=/admin/blog/create");
            exit();
        }
    }else if(!isset($_SESSION['user_id'])){
        header("Location: /login?url=/admin/blog/create");
        exit();
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        if (!is_uploaded_file($_FILES['image']['tmp_name'])){
            die("File Invalid");
            exit();
        }
        
        $imagePath = uploadFile($_FILES['image'], "blog");
    }else {
        $imagePath = "";
    }

    createBlogPost(
        $database,
        $_POST['title'],
        $imagePath,
        $_POST['description'],
        $_POST['content']
    );

    header("Location: /admin/blog");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Blog</title>
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
        <h1 style="text-align: center;">Create Blog Post</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">
            Poltar SMK Negeri 1 Giritontro
        </h2>
        <?php include __DIR__ . "/../../components/navbar.php"?>

        <?php include "../components/blogForm.php" ?>

        <?php include "../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>