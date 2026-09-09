<?php
session_start();

if (isset($_SESSION['user_id'])){
    if($_SESSION['role_id'] !== 1){
        header("Location: /login?url=/admin/users/verification");
        exit();
    }
}else if(!isset($_SESSION['user_id'])){
    header("Location: /login?url=/admin/users/verification");
    exit();
}

$currentPage = "";
include __DIR__ . "/../../data/menudb.php";
include __DIR__ . "/../../logic/database.php";
include __DIR__ . "/../../logic/getDataFromDB.php";
include __DIR__ . "/../../data/adminmenudb.php";
include __DIR__ . "/../../logic/registration/admin/buildEvaluateData.php";

$limit = 10;

$buildEvaluateData = new BuildEvaluateData();
$verifiedData = $buildEvaluateData->getVerifiedData($database, $limit);
$unverifiedData = $buildEvaluateData->getUnverifiedData($database, $limit);

$getDbData = new getDbData();
$vDCount = $getDbData->getGeneralCount($database, 'pendaftaran', 'verified');
$uDCount = $getDbData->getGeneralCount($database, 'pendaftaran', 'unverified');

#echo("<pre>");
#print_r($verifiedData);
#echo("</pre>");
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
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="stylesheet" href="/static/css/animation.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Panel Admin Pengguna</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="VContainer" style="margin: 30px;">
            <div class="itemPanel">
                <p>User Belum Diverifikasi : <?=$uDCount?></p>
                <p>User Sudah Diverifikasi : <?=$vDCount?></p>
            </div>
        
            <h3>
                <i class="fa-solid fa-list-ul"></i>
                Pendaftar Menunggu Diverifikasi
                <hr>
            </h3>
            <div class="overflow-x">
            <table style="min-width: 900px" id="unverifiedPage">
                <thead>
                    <th style="width: 25px">No</th>
                    <th>Nama</th>
                    <th style="width: 100px">Kelas</th>
                    <th style="width: 50px">No Absen</th>
                    <th>Tanggal Daftar</th>
                    <th style="width: 180px">status</th>
                    <th style="width: 50px"></th>
                </thead>
                <tbody>
                    <?php if($uDCount === 0): ?>
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
                    foreach($unverifiedData as $id => $item):
                    ?>
                    <tr>
                        <td><?=$index++?></td>
                        <td><?=$item['name']?></td>
                        <td><?=$item['kelas']?></td>
                        <td style="text-align: center"><?=$item['no_absen']?></td>
                        <td><?=$item['createdAt']?></td>
                        <td class="status-column">
                            <?=$item['statusPendaftaran']?>
                            <?=$item['statusPendaftaranCode'] == 'verified' ? 
                            ' <i class="fa-solid fa-user-check"></i>' : 
                            ' <i class="fa-solid fa-user-xmark" style="color: rgb(255, 0, 0);"></i>'?>
                        </td>
                        <td style="text-align: right">
                            <a class="edit-btn" data-pendaftaran-id="<?=$id?>"
                            data-nama-pendaftar="<?=$item['name']?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            
            <?php
                $pageId = 'unverifiedPage';
                $pageCount = ceil(($uDCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>

            <h3>
                <i class="fa-solid fa-list-ul"></i>
                Pendaftar Sudah Diverifikasi
                <hr>
            </h3>
            <div class="overflow-x">
            <table style="min-width: 900px" id="verifiedPage">
                <thead>
                    <th style="width: 25px">No</th>
                    <th>Nama</th>
                    <th style="width: 100px">Kelas</th>
                    <th style="width: 50px">No Absen</th>
                    <th>Tanggal Daftar</th>
                    <th style="width: 180px">status</th>
                    <th style="width: 50px"></th>
                </thead>
                <tbody>
                    <?php if(count($verifiedData) === 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            <i class="fa-solid fa-folder-open fa-2xl"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: center">
                            Belum Ada User Yang Sudah Diverifikasi
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
                        <td style="text-align: center"><?=$item['no_absen']?></td>
                        <td><?=$item['createdAt']?></td>
                        <td class="status-column">
                            <?=$item['statusPendaftaran']?>
                            <?=$item['statusPendaftaranCode'] == 'verified' ? 
                            ' <i class="fa-solid fa-user-check"></i>' : 
                            ' <i class="fa-solid fa-user-xmark" style="color: rgb(255, 0, 0);"></i>'?>
                        </td>
                        <td style="text-align: right">
                            <a class="edit-btn" data-pendaftaran-id="<?=$id?>"
                            data-nama-pendaftar="<?=$item['name']?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <?php
                $pageId = 'verifiedPage';
                $pageCount = ceil(($vDCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>

        </div>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script>
            const limit = <?=$limit?>;
        </script>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script src="/static/js/admin/verifyPendaftarById.js" defer></script>
        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/userVerifyVerifiedPage.js" defer></script>
        <script type="module" src="/static/js/pagination/userVerifyUnverifiedPage.js" defer></script>
    </body>
</html> 