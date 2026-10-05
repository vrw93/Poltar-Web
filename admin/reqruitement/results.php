<?php
session_start();

require_once __DIR__ . '/../authHelper.php';

$currentPage = "pendaftaranResults";

require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/registration/admin/resultDataManager.php";

#classes
$resultDataMgr = new resultDataManager($database);
$acceptedPendaftar = $resultDataMgr->getAcceptedPendaftar();

/*echo('<pre>');
print_r($acceptedPendaftar);
echo('</pre>');*/
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
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="stylesheet" href="/static/css/animation.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Dashboard Admin Polisi Taruna</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>
        
        <div class="VContainer" style="padding: 5px">
            <h2 style="margin-top: 10px;margin-bottom: 0px">
                <i class="fa-solid fa-people-group"></i>
                Hasil Pendaftaran
            </h2>
            <div class="VContainer" style="gap: 0px; margin-bottom: 2px">
                <div class="containerH" style="margin:0px; justify-content: space-between;
                align-items: center;">
                    <h3 style="margin: 0px;text-align: left">
                        <i class="fa-solid fa-user-check"></i>
                        Pendaftar Diterima
                    </h3>
                    <a class="teritary-btn download-btn" style="padding: 3px 6px"
                    data-action="accepted">
                        <i class="fa-solid fa-download"></i>
                        Download CSV
                    </a>
                </div>
                <hr style="width: 100%">
            </div>

            <div class="overflow-x">
                <table style="min-width: 900px">
                    <thead>
                        <tr>
                            <th style="width:30px">No</th>
                            <th>
                                <i class="fa-solid fa-address-card"></i>
                                Nama Lengkap
                            </th>
                            <th style="width:200px">
                                <i class="fa-solid fa-id-badge"></i>
                                Diterima Sebagai
                            </th>
                            <th style="width:200px">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                Kelas
                            </th>
                            <th style="width:200px">
                                <i class="fa-solid fa-chart-line"></i>
                                Nilai
                            </th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $index = 1;
                            foreach($acceptedPendaftar as $data): 
                        ?>
                        <tr>
                            <td style="text-align: center"><?=$index++?></td>
                            <td><?=$data['fullName']?></td>
                            <td>
                                <?php if($data['jabatanIcons']): ?>
                                    <i class="fa-solid fa-<?=$data['jabatanIcons']?>"></i>
                                <?php endif; ?>
                                <?=$data['jabatanName']?>
                            </td>
                            <td><?=$data['kelasName']?></td>
                            <td style="text-align: center"><?=$data['sekor']?></td>
                            <td style="text-align: right">
                                <a class="no-bg-btn detail-btn" 
                                data-pendaftar-id="<?=$data['pendaftarId']?>">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <dialog class="popup" id="detailDialog" style="max-width: 1100px">
            <a class="tr-anchor detail-btn" style="top: 20px; right:20px; padding: 2px">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="itemPanel">
                <h2>
                    Data Siswa
                    <hr>
                </h2>
                <div class="dataDetail">
                    <span>Nama Lengkap</span><span>:</span><span>[name]</span>
                    <span>Kelas</span><span>:</span><span>[kelas]</span>
                    <span>No Absen</span><span>:</span><span>[no_absen]</span>
                    <span>Mendaftar Pada</span><span>:</span><span>[createdAt]</span>
                </div>
                <p style="margin-top: 8px">Keahlian : </p>
                <p id="keahlianDetail" class="itemPanel"
                style="background-color:var(--color-bg-primary)">
                    [keahlian]
                </p>
            </div>
        </dialog>

        <dialog class="popup" id="csvloading">
        <div class="itemPanel">
            <h2 style="margin:0px">
                <i class="fa-solid fa-address-card"></i>
                Membuat CSV
                <hr>
            </h2>
            <div class="VContainer">
                <div style="text-align: center;font-size: 2rem;
                margin-top: 50px; margin-bottom: 50px">
                    <div style="animation: spining 1.5s linear infinite">
                        <i class="fa-solid fa-rotate fa-2xl"></i>
                    </div>
                    <br>
                    Loading...
                </div>
            </div>
        </div>
        </dialog>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script>
            let userData = {};
        </script>

        <script src="/api/js/apiHelper.js" defer></script>
        <script src="/static/js/admin/exportReqResultCSV.js" defer></script>
        <script src="/static/js/admin/evaluatePendaftarPage/getDetailPendaftarData.js" defer></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>