<?php
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "editBlog";

include "../../data/adminmenudb.php";
include "../../logic/database.php";
include "../../logic/Blogs/getBlogById.php";
include "../../logic/Blogs/updateBlogPost.php";
include "../../logic/deleteImage.php";
include "../../logic/uploadImage.php";

$id = intval($_GET['id']);

$blogPost = getBlogById($database, $id);

if (!$blogPost){
    die("No Post Found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    include __DIR__ . '/../authHelper.php';

    if ($_POST['action'] == "update"){
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
            if (!is_uploaded_file($_FILES['image']['tmp_name'])){
                die("File Invalid");
                exit();
            }
            
            $imagePath = uploadFile($_FILES['image'], "blog");
            delImage($blogPost['image'], 'blog');
        }else {
            $imagePath = $blogPost['image'];
        }
    }else if($_POST['action'] == "del"){
        delImage($blogPost['image'], 'blog');

        $imagePath = "";

        updateBlogPost(
            $database,
            $id,
            $_POST['title'],
            $imagePath,
            $_POST['description'],
            $_POST['content']
        );

        header("Location: edit?id=" . $id);
        exit();
    }

    updateBlogPost(
        $database,
        $id,
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
        <title>Edit | Blog</title>
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
        <h1 style="text-align: center;">EDIT BLOG POST</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">
            <?= $blogPost['title'] ?>
        </h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>
        
        <?php include "../components/blogForm.php"; ?>

        <?php include "../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>