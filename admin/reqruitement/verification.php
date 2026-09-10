<?php
session_start();

require_once __DIR__ . '/../authHelper.php';

$currentPage = "";
include "../../data/menudb.php";
include "../../logic/database.php";
include "../../data/adminmenudb.php";
include __DIR__ . "/../../logic/registration/admin/buildEvaluateData.php";

$buildEvaluateData = new BuildEvaluateData();
$data = $buildEvaluateData->main($database);

#echo("<pre>");
#print_r($data);
#echo("</pre>");
header('Location: /admin/reqruitement/evaluate');
exit();
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
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Panel Admin Pendaftaran</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include "../components/navbarAdmin.php"; ?>

        <div class="VContainer" style="margin: 30px;">
            <div class="itemPanel">
                <p>Total Pendaftar : 1</p>
                <p>Pendaftar Belum Diverifikasi : 1</p>
                <p>Pendaftar Sudah Diverifikasi : 1</p>
            </div>
        
            <table style="margin-top: 10px;">
                <thead>
                    <th style="width: 25px">No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>No Absen</th>
                    <th>Tanggal Daftar</th>
                    <th>status</th>
                    <th style="widht: 20px"></th>
                </thead>
                <tbody>
                    <?php foreach($data['pendaftarData'] as $id => $item): ?>
                    <tr>
                        <td><?=$item['index']?></td>
                        <td><?=$item['name']?></td>
                        <td><?=$item['kelas']?></td>
                        <td><?=$item['no_absen']?></td>
                        <td><?=$item['createdAt']?></td>
                        <td class="status-column">
                            <?=$item['statusPendaftaran']?>
                            <?=$item['statusPendaftaranCode'] == 'verified' ? 
                            ' <i class="fa-solid fa-user-check"></i>' : 
                            ' <i class="fa-solid fa-user-xmark" style="color: rgb(255, 0, 0);"></i>'?>
                        </td>
                        <td>
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

        <?php include "../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js"></script>
        <script src="/static/js/admin/verifyPendaftarById.js"></script>
    </body>
</html> 