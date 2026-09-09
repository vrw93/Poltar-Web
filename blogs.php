<?php 
session_start();

$currentPage = "blog";
require_once __DIR__ . "/logic/database.php";
require_once __DIR__ . "/logic/Blogs/getBlogPosts.php";
require_once __DIR__ . "/data/menudb.php";
require_once __DIR__ . "/logic/getDataFromDB.php";

#pagination
$getDB = new getDbData();
$limit = 12;
$blogCount = $getDB->getGeneralCount($database, 'blog_posts');

$blogPosts = getBlogPostWithLimit($database, $limit);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blogs | Poltar SMK Negeri 1 Giritontro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="static/css/mainStyle.css">
        <link rel="stylesheet" href="static/css/blogPost.css">
        <link rel="stylesheet" href="static/css/theme.css">
        <link rel="stylesheet" href="static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center; color: var(--accent-gold)">INFORMASI TERBARU</h1>
        <h2>Polisi Taruna SMK Negeri 1 Giritontro</h2>

        <?php include "components/navbar.php" ?>

        <div class="blogPostList" id="blogListPage">
            <?php foreach ($blogPosts as $blogPost): ?>
                <?php include "components/blogCard.php"; ?>
            <?php endforeach; ?>
        </div>
        <?php
            $pageId = 'blogListPage';
            $pageCount = ceil(($blogCount/$limit));
            include __DIR__ . "/components/pagination.php";
            unset($pageCount);
            unset($pageId);
        ?>

        <?php include "components/footer.php"; ?>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script>
            const limit = <?=$limit?>;
        </script>

        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/blogListPage.js" defer></script>
    </body>
</html>