<?php 
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "blog panel";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../logic/Blogs/getBlogPosts.php";
require_once __DIR__ . "/../../logic/Blogs/getLatestBlog.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";

$getDB = new getDbData();

#pagination
$limit = 12;
$blogCount = $getDB->getGeneralCount($database, 'blog_posts');

$latestBlog = getLatestBlogPosts($database, 1);
$blogPosts = getBlogPostWithLimit($database, $limit);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/blogPost.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="/static/css/animation.css">
        <link rel="stylesheet" href="../static/css/adminblog.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Blog Dashboard</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">
            Poltar SMK Negeri 1 Giritontro
        </h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="VContainer" style="padding: 10px; gap:20px">
            <div class="HContainer" style="gap: 20px;">
                <div class="itemPanel" style="align-items: center; padding: 10px;">
                    <h3><i class="fa-solid fa-newspaper"></i> Latest Blog</h3>
                    <?php foreach ($latestBlog as $blogPost): ?>
                    <div class="blogitem">
                        <?php include "../../components/blogCard.php"; ?>
                        <div class="blogaction">
                            <a href="edit?id=<?=$blogPost['id'] ?>" class="secondary-btn">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form 
                                method="POST" 
                                action="deleteBlog.php"
                                onsubmit="
                                    return confirm(
                                        'Delete this post?'
                                    )
                                "
                            >
                                <input type="hidden" name="id" value="<?=$blogPost['id']?>">
                                <button class="danger-btn" type="submit">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="itemPanel" style="flex-grow: 2;min-width: 350px">
                    <h3><i class="fa-solid fa-screwdriver-wrench"></i> Tools</h3>
                    <a class="teritary-btn" href="create" style="margin-top: 20px;text-align: center">
                        <i class="fa-solid fa-plus"></i>
                        Create New Post
                    </a>
                    <a href="#allpost" class="primary-btn" style="margin-top: 20px">
                        <i class="fa-solid fa-list"></i>
                        See All Post
                    </a>
                    <a class="secondary-btn" style="margin: 20px 0px">
                        Commentar
                    </a>
                    <h3>
                        Total Blogs : 
                        <?=$blogCount?>
                    </h3>
                    <h3>Total New Commentar : WIP</h3>
                </div>
            </div>
            <div class="itemPanel">
                <h3 id="allpost"><i class="fa-solid fa-list"></i> All Post</h3>
                <div class="blogList" id="blogListPage">
                    <?php foreach ($blogPosts as $blogPost): ?>
                    <div class="blogitem">
                        <?php include __DIR__ . "/../../components/blogCard.php"; ?>
                        <div class="blogaction">
                            <a href="edit?id=<?=$blogPost['id'] ?>" class="secondary-btn">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form 
                                method="POST" 
                                action="deleteBlog.php"
                                onsubmit="
                                    return confirm(
                                        'Delete this post?'
                                    )
                                "
                            >
                                <input type="hidden" name="id" value="<?=$blogPost['id']?>">
                                <button class="danger-btn" type="submit">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
                $pageId = 'blogListPage';
                $pageCount = ceil(($blogCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>
        </div>

        <?php include "../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script>
            const limit = <?=$limit?>;
        </script>

        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/admin/blogAdminPage.js" defer></script>
    </body>
</html>