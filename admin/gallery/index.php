<?php 
session_start();

if (isset($_SESSION['user_id'])){
    if($_SESSION['role_id'] !== 1){
        header("Location: /login?url=/admin/gallery");
        exit();
    }
}else if(!isset($_SESSION['user_id'])){
    header("Location: /login?url=/admin/gallery");
    exit();
}

$currentPage = "gallery panel";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../logic/Gallery/getGalleryPost.php";
require_once __DIR__ . "/../../logic/Gallery/getLatestGalleryPost.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";

$getDB = new getDbData();

#pagination
$limit = 10;
$galleryCount = $getDB->getGeneralCount($database, 'gallery_Posts');

$galleryPosts = getGalleryPostWithLimit($database, $limit);
$galleryPostLatest = getLatestGalleryPosts($database, 1)
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
        <link rel="stylesheet" href="/static/css/gallery.css">
        <link rel="stylesheet" href="../static/css/admingallery.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Gallery Dashboard</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="VContainer" style="padding: 30px;">
            <div class="HContainer">
                <div class="itemPanel" style="align-items: center;min-width: 320px;">
                    <h3><i class="fa-solid fa-newspaper"></i> Latest Gallery</h3>
                    <?php foreach($galleryPostLatest as $galleryPost): ?>
                        <div class="galleryCardContainer" style="max-width: 300px;">
                            <?php include __DIR__ . "/../../components/galleryCard.php"; ?>
                            
                            <div class="galleryActionButton">
                                <a href="edit?id=<?=$galleryPost['id']?>" class="secondary-btn">
                                    <i class="fa-solid fa-pen"></i>
                                    Edit
                                </a>
                                <form
                                    method="POST"
                                    action="deleteGallery.php"
                                    onsubmit="
                                        return confirm('Delete This Post?')
                                    "
                                >
                                    <input type="hidden" name="id" value="<?=$galleryPost['id']?>">
                                    <button class="danger-btn" type="submit">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="itemPanel" style="min-width: 320px;flex-grow: 2">
                    <h3><i class="fa-solid fa-screwdriver-wrench"></i> Tools</h3>
                    <a class="teritary-btn" style="margin-top: 20px;text-align: center" href="create">
                        <i class="fa-solid fa-plus"></i>
                        Create New Gallery
                    </a>
                    <a class="primary-btn" style="margin-top: 20px;text-align: center" href="#posts">
                        <i class="fa-solid fa-list"></i>
                        See All Gallery
                    </a>
                    <h3 style="margin-top: 10px">
                        Total Galeri: 
                        <?=$galleryCount?>
                    </h3>
                </div>
            </div>
            <div class="itemPanel" style="min-width: 320px;">
                <h3 id="posts"><i class="fa-solid fa-list"></i> All Gallery Post</h3>
                <div class="galleryContainer" id="galleryListPage">
                
                    <?php foreach($galleryPosts as $galleryPost): ?>
                        <div class="galleryCardContainer">
                            <?php include __DIR__ . "/../../components/galleryCard.php"; ?>
                            
                            <div class="galleryActionButton">
                                <a href="edit?id=<?=$galleryPost['id']?>" class="secondary-btn">
                                    <i class="fa-solid fa-pen"></i>
                                    Edit
                                </a>
                                <form
                                    method="POST"
                                    action="deleteGallery.php"
                                    onsubmit="
                                        return confirm('Delete This Post?')
                                    "
                                >
                                    <input type="hidden" name="id" value="<?=$galleryPost['id']?>">
                                    <button class="danger-btn" type="submit">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach ?>

                </div>
            </div>
            <?php
                $pageId = 'galleryListPage';
                $pageCount = ceil(($galleryCount/$limit));
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
        <script type="module" src="/static/js/pagination/admin/galleryAdminPage.js" defer></script>
    </body>
</html>