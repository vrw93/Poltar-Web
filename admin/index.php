<?php
session_start();

require_once __DIR__ . '/authHelper.php';

$currentPage = "home";
require_once __DIR__ . "/../logic/Blogs/getLatestBlog.php";
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../data/adminmenudb.php";
require_once __DIR__ . "/../logic/getServerStatusByName.php";
require_once __DIR__ . "/../logic/getDataFromDB.php";

$getDB = new getDbData();

$latestBlog = getLatestBlogPosts($database, 1);
$reqruitementStatus = getServerStatusByName($database, 'reqruitementPage');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/blogPost.css">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Dashboard Admin Polisi Taruna</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../components/navbar.php"; ?>
        
        <div class="VContainer">
            <div class="HContainer" style="padding: 30px; padding-bottom:10px; gap: 20px;">
                <div class="itemPanel" style="min-width: 320px">
                    <h3>Blogs Summary Panel</h3>
                    <p>
                        Jumlah Blog : 
                        <?=$getDB->getGeneralCount($database, 'blog_posts')?>
                    </p>
                    <p>Jumlah Pengunjung : WIP</p>
                    <a href="blog" class="primary-btn" style="margin-top: 20px;">
                        <i class="fa-solid fa-bullhorn"></i>
                        Panel Blog
                    </a>
                </div>
                <div class="itemPanel" style="min-width: 320px">
                    <h3>Gallery Summary Panel</h3>
                    <p>
                        Jumlah Foto :
                        <?=$getDB->getGeneralCount($database, 'gallery_Posts')?>
                    </p>
                    <p>Jumlah Pengunjung : WIP</p>
                    <a class="primary-btn" style="margin-top: 20px;" href="gallery">
                        <i class="fa-solid fa-images"></i>
                        Panel Gallery
                    </a>
                </div>
            </div>
            <div class="HContainer" style="padding: 30px; padding-top:0px; gap: 20px;">
                <div class="itemPanel" style="gap: 5px;min-width: 320px">
                    <h3>Pendaftaran</h3>
                    <p>
                        Jumlah Pendaftar : 
                        <?=$getDB->getGeneralCount($database, 'pendaftaran')?>
                    </p>
                    <p>
                        Jumlah Pendaftar Belum Di Nilai : 
                        <?=$getDB->getEvalPenCount($database, 'nEval')?>
                    </p>
                    <a class="primary-btn" href="reqruitement/evaluate">
                        <i class="fa-solid fa-pen-clip"></i>
                        Nilai Pendaftar
                    </a>
                    
                    <?php 
                    $reqBtnVisual = $reqruitementStatus['statusCode'] == 'reg_closed' ? 
                        '<i class="fa-solid fa-door-open"></i> Buka Pendaftaran' : 
                        '<i class="fa-solid fa-door-closed"></i> Tutup Pendaftaran'; 
                    ?>
                    
                    <a class="secondary-btn" id="pendaftaranStateToggleBtn">
                        <?=$reqBtnVisual?>
                    </a>
                </div>
                <div class="itemPanel" style="gap: 5px;min-width: 320px">
                    <h3>User</h3>
                    <p>
                        Belum Diverifikasi : 
                        <?=$getDB->getGeneralCount($database, 'pendaftaran', 'unverified')?>
                    </p>
                    <p>
                        Perubahan Belum Di Cek : 
                        <?=$getDB->getGeneralCount($database, 'Users_Update_Request', 'isActive')?>
                    </p>
                    <a class="teritary-btn" href="users/verification">
                        <i class="fa-solid fa-user-check"></i>
                        Verifikasi User
                    </a>
                    <a class="primary-btn" href="users/update-request">
                        <i class="fa-solid fa-user-pen"></i>
                        Verifikasi Perubahan Akun
                    </a>
                    <a class="secondary-btn" href="users/all">
                        <i class="fa-solid fa-users"></i>
                        List Pengguna
                    </a>
                </div>
            </div>
        </div>
        
        <?php include "../components/footer.php"; ?>

        <script>
            let pendaftaranState = '<?=$reqruitementStatus['statusCode']?>';
        </script>
        <script src="/static/js/admin/togglePendaftaranState.js"></script>
        <script src="/api/js/apiHelper.js"></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>