<?php
session_start();

$currentPage = "gallery";
require_once __DIR__ . "/data/menudb.php";
require_once __DIR__ . "/logic/database.php";
require_once __DIR__ . "/logic/Gallery/getGalleryPost.php";
require_once __DIR__ . "/logic/getDataFromDB.php";

$getDB = new getDbData();

#pagination
$limit = 12;
$galleryCount = $getDB->getGeneralCount($database, 'gallery_Posts');

$galleryPosts = getGalleryPostWithLimit($database, $limit);
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Polisi Taruna - SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="static/css/mainStyle.css">
        <link rel="stylesheet" href="static/css/theme.css">
        <link rel="stylesheet" href="static/css/gallery.css">
        <link rel="stylesheet" href="static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center">Gallery</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>
        <?php include "components/navbar.php" ?>

        <div class="galleryContainer" id="galleryListPage">
                
            <?php foreach($galleryPosts as $galleryPost): ?>
                <?php include __DIR__ . "/components/galleryCard.php"; ?>
            <?php endforeach ?>

        </div>
        <?php
            $pageId = 'galleryListPage';
            $pageCount = ceil(($galleryCount/$limit));
            include __DIR__ . "/components/pagination.php";
            unset($pageCount);
            unset($pageId);
        ?>

        <?php include "components/galleryView.php"; ?>

        <?php include "components/footer.php" ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/static/js/main.js"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script>
            const limit = <?=$limit?>;
        </script>

        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/galleryPage.js" defer></script>
    </body>
</html>