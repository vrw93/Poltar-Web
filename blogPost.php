<?php
$currentPage = "";
require_once __DIR__ . "/logic/database.php";
require_once __DIR__ . "/logic/Blogs/getBlogManager.php";
require_once __DIR__ . "/logic/markdown/markdownParser.php";
require_once __DIR__ . "/data/menudb.php";

# Old System Use For Fallback Only!!!
#$id = intval($_GET['id']);

# Clasess
$getBlogMgr = new getBlogManager($database);

$slug = $_GET['slug'] ?? '';
$currentPost = $getBlogMgr->getBlogDataBySlug($slug);

if ($currentPost == null){
    $type = '404';
    include __DIR__ . '/errorpage.php';
    exit;
}

session_start();
$mardownParser = new markdownParser();

$content = $mardownParser->Parse($currentPost['content']);
$titleNonOg = $currentPost['title'];
$title = $currentPost['title'] . " | Blog";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$titleNonOg?> | SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <?php include __DIR__ . "/components/opengraph.php"?>

        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/markdown.css">
        <link rel="stylesheet" href="/static/css/blogPost.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <?php include "components/navbar.php"; ?>
        <main class="mainPost">
            <div class="blogContent">
                <h1><?=$titleNonOg?></h1>

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