<?php
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "editBlog";

require_once __DIR__ . "/../../logic/Blogs/blogManager.php";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../logic/Blogs/getBlogById.php";
require_once __DIR__ . "/../../logic/deleteImage.php";
require_once __DIR__ . "/../../logic/uploadImage.php";

$id = intval($_GET['id']);

$blogPost = getBlogById($database, $id);

if (!$blogPost){
    die("No Post Found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    include __DIR__ . '/../authHelper.php';

    #clasess
    $blogMgr = new blogManager($database);
    $isNewImage = false;
    
    if ($_POST['action'] == "update"){
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
            if (!is_uploaded_file($_FILES['image']['tmp_name'])){
                die("File Invalid");
                exit();
            }
            
            $imagePath = uploadFile($_FILES['image'], "blog");
            $isNewImage = true;
        }else {
            $imagePath = $blogPost['image'];
        }
    }else if($_POST['action'] == "del"){
        $imagePath = "";

        if(!$blogMgr->editBlog(
            $_POST['title'],
            $imagePath,
            $_POST['description'],
            $_POST['content'],
            $id
        )){
            $type = '500';
            include __DIR__ . '/../../errorpage.php';
            exit;
        };
        delImage($blogPost['image'], 'blog');

        header("Location: edit?id=" . $id);
        exit();
    }

    if(!$blogMgr->editBlog(
        $_POST['title'],
        $imagePath,
        $_POST['description'],
        $_POST['content'],
        $id
    )){
        if($isNewImage) delImage($imagePath, 'blog');

        $type = '500';
        include __DIR__ . '/../../errorpage.php';
        exit;
    }
    delImage($blogPost['image'], 'blog');

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
            <?= htmlspecialchars($blogPost['title']) ?>
        </h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>
        
        <?php include __DIR__ . "/../components/blogForm.php"; ?>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>