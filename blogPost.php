<?php
session_start();

$currentPage = "";
include "logic/database.php";
include "logic/Blogs/getBlogPosts.php";
include "logic/markdown/markdownParser.php";
include "data/menudb.php";

$blogPosts = getBlogPosts($database);

$id = intval($_GET['id']);
$currentPost = null;

foreach ($blogPosts as $post){
    if ($post['id'] == $id){
        $currentPost = $post;
        break;
    }
}

if ($currentPost == null){
    die("Blogs Not Found");
}

$mardownParser = new markdownParser();

$content = $mardownParser->Parse($currentPost['content']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $currentPost['title'] ?> | SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="static/css/mainStyle.css">
        <link rel="stylesheet" href="static/css/markdown.css">
        <link rel="stylesheet" href="static/css/blogPost.css">
        <link rel="stylesheet" href="static/css/theme.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <?php include "components/navbar.php"; ?>
        <main class="mainPost">
            <div class="blogContent">
                <h1><?= $currentPost['title'] ?></h1>

                <small><?= $currentPost['createdAt'] ?></small><br>

                <?php if ($currentPost['image'] !== ""): ?>

                <div class="thumbnail">
                    <img src="<?= $currentPost['image'] ?>" alt="Thumbnail">
                </div>

                <?php endif; ?>

                <div class="markdown">
                    <?= $content ?>
                </div>
            </div>
        </main>

        <?php include "components/footer.php"; ?>
        
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>