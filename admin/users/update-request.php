<?php
session_start();

require_once __DIR__ . '/../authHelper.php';

$currentPage = "";
include __DIR__ . "/../../logic/database.php";
include __DIR__ . "/../../logic/getDataFromDB.php";
include __DIR__ . "/../../data/adminmenudb.php";

$limit = 10;

$getDtaHlpr = new getDbData();
$inactiveUserRequest = $getDtaHlpr->getUserDataUpdateRequest($database, 'inactive', $limit);
$activeUserRequest = $getDtaHlpr->getUserDataUpdateRequest($database, 'active', $limit);

$iURCount = $getDtaHlpr->getGeneralCount($database, 'Users_Update_Request', 'isInactive');
$aURCount = $getDtaHlpr->getGeneralCount($database, 'Users_Update_Request', 'isActive');

function setTypeName(string $type): string{
    if($type === 'account'){
        return 'Pembaruan Akun';
    }
    if($type === 'biodata'){
        return 'Pembaruan Biodata';
    }
    return $type;
}

/*echo('<pre>');
print_r($userRequest);
echo('</pre>');*/
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Verifikasi | Pendaftar</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="/static/css/animation.css">
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Panel Admin Pengguna</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="VContainer" style="margin: 30px;">
            <div class="itemPanel">
                <p>Verifikasi Permintaan Pembaruan Data Pengguna</p>
                <p>User Belum Diverifikasi : <?=$aURCount?></p>
                <p>User Sudah Diverifikasi : <?=$iURCount?></p>
            </div>

            <h3>
                <i class="fa-solid fa-clock"></i>
                Permintaan Menunggu Konfirmasi:
                <hr>
            </h3>
            <div class="overflow-x">
            <table style="min-width: 800px" id="activePage">
                <thead>
                    <th style="width: 30px">No</th>
                    <th>Nama Pengguna</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>status</th>
                    <th style="width: 60px"></th>
                </thead>
                <tbody>
                    <?php if(count($activeUserRequest) === 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            <i class="fa-solid fa-folder-open fa-xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            Belum Ada Permintaan Perubahan Detail Akun Yang Baru
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach($activeUserRequest as $index => $request): ?>
                    <tr>
                        <td style="text-align: center"><?=$index + 1?></td>
                        <td><?=$request['username']?></td>
                        <td><?=setTypeName($request['type'])?></td>
                        <td><?=$request['createdAt']?></td>
                        <td><?=$request['statusName']?></td>
                        <td style="text-align: right">
                            <a class="detail-btn" data-request-id="<?=$request['id']?>"
                            data-created-at="<?=$request['createdAt']?>"
                            data-user-id="<?=$request['userId']?>" data-is-active='true'>
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php
                $pageId = 'activePage';
                $pageCount = ceil(($aURCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>

            <h3>
                <i class="fa-solid fa-check-double"></i>
                Permintaan Sudah Dikonfirmasi:
                <hr>
            </h3>
            <div class="overflow-x">
            <table style="min-width: 800px" id="inactivePage">
                <thead>
                    <th style="width: 30px">No</th>
                    <th>Nama Pengguna</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>status</th>
                    <th style="width: 60px"></th>
                </thead>
                <tbody>
                    <?php if(count($inactiveUserRequest) === 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            <i class="fa-solid fa-folder-open fa-xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            Belum Ada Permintaan Perubahan Detail Akun Yang Dikonfirmasi
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach($inactiveUserRequest as $index => $request): ?>
                    <tr>
                        <td style="text-align: center"><?=$index + 1?></td>
                        <td><?=$request['username']?></td>
                        <td><?=setTypeName($request['type'])?></td>
                        <td><?=$request['createdAt']?></td>
                        <td><?=$request['statusName']?></td>
                        <td style="text-align: right">
                            <a class="detail-btn" data-request-id="<?=$request['id']?>"
                            data-created-at="<?=$request['createdAt']?>"
                            data-user-id="<?=$request['userId']?>" data-is-active='false'>
                                <i class="fa-solid fa-clipboard-list"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php
                $pageId = 'inactivePage';
                $pageCount = ceil(($iURCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>
        </div>

        <dialog class="popup" id="requestDetailDig">
            <div class="itemPanel" style="margin: 10px">
                <div class="tr-anchor" style="top: 30px; right: 30px">
                    <a class="primary-btn" id="digCloseBtn" style="padding: 2px 5px">
                        <i class="fa-solid fa-x"></i>
                    </a>
                </div>
                <h3>
                    Detail Permintaan Perubahan
                    <hr style="width: 100%">
                </h3>
                <div class="dataDetail">
                    <span>Tanggal Di Buat</span><span>:</span><span>[createdAt]</span>
                    <span>Id Pengguna</span><span>:</span><span>[userId]</span>
                </div>
                <div class="itemPanel" 
                style="background-color: var(--bg-primary);margin-top:10px; overflow: auto;
                min-height: fit-content;">
                    <table style="width:520px;">
                        <thead>
                            <th style="width:20px">No</th>
                            <th style="width:130px">Jenis</th>
                            <th style="width:240px">Data Baru</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>[type]</td>
                                <td>[newValue]</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </dialog>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script>
            const limit = <?=$limit?>;
        </script>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script src="/static/js/admin/verifyUserUpdateRequest.js" defer></script>
        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/userUpdateInactivePage.js" defer></script>
        <script type="module" src="/static/js/pagination/userUpdateActivePage.js" defer></script>
    </body>
</html>