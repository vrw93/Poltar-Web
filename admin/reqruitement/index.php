<?php
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "daftar panel";
include "../../data/menudb.php";
include "../../logic/database.php";
include "../../data/adminmenudb.php";
include __DIR__ . "/../../logic/getServerStatusByName.php";

$reqruitementStatus = getServerStatusByName($database, 'reqruitementPage');

header('Location: /admin/reqruitement/evaluate');
exit();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pendaftaran | Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Panel Admin Pendaftaran</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include "../components/navbarAdmin.php"; ?>

        <div class="HContainer" style="margin: 30px;">
            <div class="VContainer" style="flex-grow: 1; padding: 20px">
                <p style="margin: 0px;">Jumlah Pendaftar Belum Di Verifikasi : 1</p>
                <p style="margin: 0px;">Jumlah Pendaftar Belum Di Nilai : 1</p>
            </div>
            <div class="itemPanel" style="gap: 20px;">
                <a href="verification" class="primary-btn">Verifikasi Pendaftar</a>
                <a href="evaluate" class="teritary-btn">Nilai Pendaftar</a>
                <?php $reqBtnVisual = $reqruitementStatus['statusCode'] == 'reg_closed' ? 'Buka Pendaftaran' : 'Tutup Pendaftaran'; ?>
                            
                <a class="secondary-btn" id="pendaftaranStateToggleBtn">
                    <?=$reqBtnVisual?>
                </a>
            </div>
        </div>

        <?php include "../../components/footer.php"; ?>

        <script>
            let pendaftaranState = '<?=$reqruitementStatus['statusCode']?>';
        </script>
        <script src="/static/js/admin/togglePendaftaranState.js"></script>
        <script src="/api/js/apiHelper.js"></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html> 