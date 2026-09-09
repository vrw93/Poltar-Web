<?php
session_start();

$currentPage = '';

include 'data/menudb.php';
include 'data/errorPageDB.php';

$data = $datas[$type ?? 'unknown'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Polisi Taruna - SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">Polisi Taruna</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include "components/navbar.php"; ?>

        <div style="display:flex; flex-direction:column; align-items:center; text-align: center;">
            <h2 style="margin-top: 50px"><?=$data['title']?></h2>
            <p><?=$data['description']?></p>
            <a href='/' class='primary-btn' style="max-width:fit-content">Kembali Ke Halaman Utama</a>
        </div>

        <?php include "components/footer.php"; ?>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>