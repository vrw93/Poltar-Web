<?php
session_start();

require_once __DIR__ . '/../authHelper.php';

$currentPage = "daftar panel";
require_once __DIR__ . "/../../data/menudb.php";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/registration/admin/buildEvaluateData.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";

function checkConflictJabatan(string $currentJabatan, array $data, int $jabatanId, int $pendaftarId){
    if(isset($data['conflictData'][$jabatanId]['pemilih'][$pendaftarId])){
        return $currentJabatan . ' <a class="danger-btn conflictBtn" style="padding: 2px 5px" 
        title="Siswa Ini Memiliki Nilai Yang Sama Dengan Siswa Lain" data-pendaftar-id="' . $pendaftarId . '"
        data-jabatan-id="' . $jabatanId . '">
        <i class="fa-solid fa-triangle-exclamation fa-sm fa-shake"></i>
        </a>';
    }
    return $currentJabatan;
}

$getDB = new getDbData();

$limit = 10;
$allPenCount = $getDB->getGeneralCount($database, 'pendaftaran');

$buildEvaluateData = new BuildEvaluateData();
$verifiedData = $buildEvaluateData->getVerifiedData($database, $limit);
$unverifiedData = $buildEvaluateData->getUnverifiedData($database, $limit);

#echo("<pre>");
#print_r($unverifiedData);
#echo("</pre>");
#die();
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
        <link rel="stylesheet" href="/static/css/evaluateDialog.css">
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="stylesheet" href="/static/css/animation.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Panel Admin Pendaftaran</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="VContainer" style="margin: 30px;">
            <div class="itemPanel">
                <p>
                    Total Pendaftar : 
                    <?=$allPenCount?>
                </p>
                <p>
                    Pendaftar Belum Dinilai : 
                    <?=$getDB->getEvalPenCount($database, 'nEval')?>
                </p>
                <p>
                    Pendaftar Sudah Dinilai : 
                    <?=$getDB->getEvalPenCount($database, 'eval')?>
                </p>
                <p>Pendaftar Diterima : 1</p>
            </div>

            <h3 style="margin: 0px">
                <i class="fa-solid fa-circle-question"></i>
                Pendaftar Belum Diverifikasi
                <hr>
            </h3>
            <div class="overflow-x">
            <table style="min-width:900px">
                <thead>
                    <th style="width: 20px">No</th>
                    <th>Nama</th>
                    <th style="width: 100px">Kelas</th>
                    <th>Jabatan</th>
                    <th style="width: 130px">Nilai</th>
                    <th>status</th>
                    <th style="width: 50px"></th>
                </thead>
                <tbody>
                    <?php if(count($unverifiedData) === 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            <i class="fa-solid fa-folder-open fa-2xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            Tidak Ada User Yang Mendaftar/Belum Diverifikasi
                        </td>
                    </tr>
                    <?php endif;?>

                    <?php
                    $index = 1;
                    foreach($unverifiedData as $id => $item):
                    ?>
                    <tr>
                        <td><?=$index++?></td>
                        <td><?=$item['name']?></td>
                        <td><?=$item['kelas']?></td>
                        <td><?=checkConflictJabatan(
                            $item['currentJabatan'], $verifiedData,
                            $item['currentJabatanId'], $id
                            )?>
                        </td>
                        <td class="sekor-column"><?=$item['sekor'] ?? 'Belum Di Nilai'?></td>
                        <td class="status-column"><?=$item['statusPendaftaran']?></td>
                        <td style="text-align: right">
                            <a class="edit-btn" href="/admin/users/verification"
                            title="Verifikasi Siswa">
                            <i class="fa-solid fa-check-double"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
            </div>

            <h3 style="margin: 0px">
                <i class="fa-solid fa-circle-check"></i>
                Pendaftar Sudah Diverifikasi
                <hr>
            </h3>
            <div class="overflow-x">
            <table style="min-width:900px" id="verifiedPage">
                <thead>
                    <th style="width: 20px">No</th>
                    <th>Nama</th>
                    <th style="width: 100px">Kelas</th>
                    <th>Jabatan</th>
                    <th style="width: 130px">Nilai</th>
                    <th>status</th>
                    <th style="width: 50px"></th>
                </thead>
                <tbody data-current-page="1">
                    <?php if(count($verifiedData) === 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            <i class="fa-solid fa-folder-open fa-2xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            Belum Ada User Yang Mendaftar/Belum Diverifikasi
                        </td>
                    </tr>
                    <?php endif;?>

                    <?php
                    $index = 1;
                    foreach($verifiedData['pendaftarData'] as $id => $item):
                    ?>
                    <tr>
                        <td><?=$index++?></td>
                        <td><?=$item['name']?></td>
                        <td><?=$item['kelas']?></td>
                        <td><?=checkConflictJabatan(
                            $item['currentJabatan'], $verifiedData,
                            $item['currentJabatanId'], $id
                            )?>
                        </td>
                        <td class="sekor-column"><?=$item['sekor'] ?? 'Belum Di Nilai'?></td>
                        <td class="status-column"><?=$item['statusPendaftaran']?></td>
                        <td style="text-align: right">
                            <a class="detail-btn" data-pendaftar-id="<?=$id?>"
                            title="Detail Siswa">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </a>
                            <a class="edit-btn" data-pendaftaran-id="<?=$id?>"
                            title="Edit Nilai Siswa">
                            <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
            </div>
            <?php
                $pageId = 'verifiedPage';
                $pageCount = ceil(($allPenCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>
        </div>

        <dialog class="popup" id="detailDialog" style="max-width: 1100px">
            <a class="tr-anchor detail-btn" style="top: 20px; right:20px; padding: 2px">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="itemPanel">
                <h2>Data Siswa</h2>
                <div class="dataDetail">
                    <span>Nama Lengkap</span><span>:</span><span>[name]</span>
                    <span>Kelas</span><span>:</span><span>[kelas]</span>
                    <span>No Absen</span><span>:</span><span>[no_absen]</span>
                    <span>Mendaftar Pada</span><span>:</span><span>[createdAt]</span>
                </div>
                <p style="margin-top: 8px">Keahlian : </p>
                <p id="keahlianDetail">[keahlian]</p>
            </div>
        </dialog>

        <dialog class="popup" id="conflictDialog">
            <a class="tr-anchor conflictBtn" style="top: 20px; right:20px; padding: 2px">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="itemPanel">
                <h2 style="text-align: left">Pilih [n] Dari [n] Pendaftar</h2>
                <p>Pilihan ini akan menentukan siapa yang akan dipilih menjadi [jabatan]</p>
                <ul class="participantList">
                    <p style="text-align: center">Loading...</p>
                    <!--<li>
                        <div class="participantItem">
                        <p>[peserta 1]</p>
                        <a class="primary-btn" style="padding: 2px 5px">
                            <i class="fa-solid fa-check"></i>
                        </a>
                        </div>
                    </li>-->
                </ul>
            </div>
        </dialog>

        <?php include "../../components/footer.php"; ?>

        <script>
            let conflictData = <?=json_encode($buildEvaluateData->getConflictedData($database))?>;
            let userData = {};
            const limit = <?=$limit?>;
        </script>
        
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script src="/static/js/admin/getDetailPendaftarData.js" defer></script>
        <script src="/static/js/admin/conflictResolver.js" defer></script>

        <script type="module" src="/static/js/admin/nilaiPendaftarByPendaftaranId.js" defer></script>
        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/userEvaluateVerifiedPage.js" defer></script>
    </body>
</html> 