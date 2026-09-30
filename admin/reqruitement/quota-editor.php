<?php
session_start();

include __DIR__ . '/../authHelper.php';

$currentPage = "daftar panel";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../logic/registration/admin/getFullJabatanData.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";

#clasess
$getDBData = new getDbData();
$jabatanMgr = new jabatanManager($database);

#datas
$activeJabatanDatas = $jabatanMgr->getFullActiveData();
$inActiveJabatanDatas = $jabatanMgr->getFullInActiveData();

#pages
$limit = 10;
$ACount = $getDBData->getGeneralCount(
    $database,
    'jabatan',
    'isActive'
);
$IACount = $getDBData->getGeneralCount(
    $database,
    'jabatan',
    'isInactive'
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Editor Kuota Dan Jabatan | Admin</title>
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
        <h1 style="text-align: center;">Panel Editor Kuota</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="VContainer" style="padding: 5px">
            <h3 style="text-align:left;">
                <i class="fa-solid fa-list"></i>List Jabatan Dan Kuota Aktif
                <hr>
            </h3>

            <div class="overflow-x">
            <table style="min-width: 900px">
                <thead>
                    <tr>
                        <th style="width: 30px">No</th>
                        <th>Nama</th>
                        <th>Kuota</th>
                        <th style="width: 220px">status</th>
                        <th style="width: 60px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($activeJabatanDatas) === 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            <i class="fa-solid fa-folder-open fa-2xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            Tidak Ada Jabatan Aktif
                        </td>
                    </tr>
                    <?php endif;?>

                    <?php
                    $index = 1;
                    foreach($activeJabatanDatas as $data): 
                    ?>
                    <tr>
                        <td style="text-align: center"><?=$index++?></td>
                        <td><?=$data['name']?></td>
                        <td style="text-align: center">
                            <?=$data['kouta']?>
                        </td>
                        <td><?=$data['StatusName']?></td>
                        <td style="text-align: right">
                            <a class="no-bg-btn edit-btn" title="Edit"
                            data-jabatan-id="<?=$data['id']?>"
                            data-jabatan-name="<?=$data['name']?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="no-bg-btn deactivate" title="Matikan Jabatan"
                            data-jabatan-id="<?=$data['id']?>">
                                <i class="fa-solid fa-lock"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php
                $pageId = 'activeJabatanPage';
                $pageCount = ceil(($ACount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>

            <h3 style="text-align:left;">
                <i class="fa-solid fa-list"></i>List Jabatan Dan Kuota Non-Aktif
                <hr>
            </h3>

            <div class="overflow-x">
            <table style="min-width: 900px">
                <thead>
                    <tr>
                        <th style="width: 30px">No</th>
                        <th>Nama</th>
                        <th>Kuota</th>
                        <th style="width: 220px">status</th>
                        <th style="width: 60px"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if(count($inActiveJabatanDatas) === 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            <i class="fa-solid fa-folder-open fa-2xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            Tidak Ada Jabatan Yang Dinonaktifkan
                        </td>
                    </tr>
                    <?php endif;?>

                    <?php
                    $index = 1;
                    foreach($inActiveJabatanDatas as $data): 
                    ?>
                    <tr>
                        <td style="text-align: center"><?=$index++?></td>
                        <td><?=$data['name']?></td>
                        <td style="text-align: center">
                            <?=$data['kouta']?>
                        </td>
                        <td><?=$data['StatusName']?></td>
                        <td style="text-align: right">
                            <a class="no-bg-btn edit-btn" title="Edit"
                            data-jabatan-id="<?=$data['id']?>"
                            data-jabatan-name="<?=$data['name']?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="no-bg-btn deactivate" title="Matikan Jabatan"
                            data-jabatan-id="<?=$data['id']?>">
                                <i class="fa-solid fa-lock"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php
                $pageId = 'deactiveJabatanPage';
                $pageCount = ceil(($IACount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>
        </div>

        <dialog id="editorDialog" class="popup">
            <div class="tr-anchor">
                <a class="tr-anchor edit-btn" style="top: 20px; right:20px; padding: 2px">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
            <div class="itemPanel">
                    <h2 style="margin:0px">
                        <i class="fa-solid fa-address-card"></i>
                        Edit Jabatan [name]
                        <hr>
                    </h2>
                    <form id="editForm" class="VContainer" style="gap:3px">
                        <p><b>Nama</b>:</p>
                        <input placeholder="Masukkan Nama Jabatan"
                        type="text" required value="">
                        <p><b>Kuota</b>:</p>
                        <input placeholder="Masukkan Kuota Jabatan"
                        type="number" required value="" min="1">
                        <button type="submit" class="teritary-btn">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </form>
            </div>
        </dialog>

        <?php include __DIR__ . "/../../components/footer.php"; ?>
        
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script src="/static/js/admin/jabatan/jabatanEditorDialog.js" defer></script>

        <script type="module" src="/static/js/pagination/main.js" defer></script>
    <body>
</html>