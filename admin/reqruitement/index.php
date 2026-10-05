<?php
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "daftar dashboard";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/getServerStatusByName.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";

#classes
$getData = new getDbData();

#count
$penCount = $getData->getGeneralCount($database, 'pendaftaran');
$unverifyPenCount = $getData->getGeneralCount($database, 'pendaftaran', 'unverified');
$unevaluatedPenCount = $getData->getGeneralCount($database, 'pendaftaran', 'isEvaluated');
$acceptedPenCount = $getData->getGeneralCount($database, 'pilihan', 'isAccepted');

$reqruitementStatus = getServerStatusByName($database, 'reqruitementPage');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Pendaftaran</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Dashboard Pendaftaran</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="containerV" style="padding: 15px;gap: 15px;">
            <div class="containerH" style="gap: 15px;">
                <div class="itemPanel">
                    <h3 style="text-align: left">
                        <i class="fa-solid fa-chart-column"></i>
                        Statistik Dasar Pendaftaran
                        <hr>
                    </h3>
                    <div class="dataDetail" style="grid-template-columns: 260px 10px auto;">
                        <span><i class="fa-solid fa-users"></i> Jumlah Pendaftar</span><span>:</span><span><?=$penCount?></span>
                        <span><i class="fa-solid fa-user-clock"></i> Pendaftar Belum Diverifikasi</span><span>:</span><span><?=$unverifyPenCount?></span>
                        <span><i class="fa-solid fa-user-clock"></i> Pendaftar Belum Dinilai</span><span>:</span><span><?=$unevaluatedPenCount?></span>
                        <span><i class="fa-solid fa-user-check"></i> Pendaftar Diterima</span><span>:</span><span><?=$acceptedPenCount?></span>
                    </div>
                </div>
                <div class="itemPanel" style="gap: 5px">
                    <h3 style="text-align: left">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        Alat Dasar Pendaftaran
                        <hr>
                    </h3>
                    <a class="teritary-btn" href="quota-editor">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit Jabatan Dan Kuota
                    </a>
                    <a class="primary-btn" href="evaluate">
                        <i class="fa-solid fa-pen-clip"></i>
                        Nilai Pendaftar
                    </a>
                    <a class="secondary-btn" href="results">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Lihat Hasil Pendaftaran
                    </a>
                    
                    <?php 
                    $reqBtnVisual = $reqruitementStatus['statusCode'] == 'reg_closed' ? 
                        '<i class="fa-solid fa-door-open"></i> Buka Pendaftaran' : 
                        '<i class="fa-solid fa-door-closed"></i> Tutup Pendaftaran'; 
                    ?>
                    
                    <a class="danger-btn" id="pendaftaranStateToggleBtn">
                        <?=$reqBtnVisual?>
                    </a>
                </div>
            </div>
        </div>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script>
            let pendaftaranState = '<?=$reqruitementStatus['statusCode']?>';
        </script>
        <script src="/static/js/admin/togglePendaftaranState.js"></script>
        <script src="/api/js/apiHelper.js"></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html> 