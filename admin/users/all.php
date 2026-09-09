<?php
session_start();

if (isset($_SESSION['user_id'])){
    if($_SESSION['role_id'] !== 1){
        header("Location: ../login");
        exit();
    }
}else if(!isset($_SESSION['user_id'])){
    header("Location: /login?url=/admin");
    exit();
}

$currentPage = "";
require_once __DIR__ . "/../../data/adminmenudb.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">All Users Polisi Taruna</h1>
        <h2 style="color: var(--accent-gold);text-align: center;">SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../../components/navbar.php"; ?>

        <div class="containerV" style="margin: 10px;">
            <h3 style="text-align: left;">
                Semua Pengguna
                <hr>
            </h3>

            <div class="overflow-x">
            <table style="min-width: 1100px" id="unverifiedPage">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th style="width: 150px">Role</th>
                        <th style="width: 150px">Status</th>
                        <th style="width: 80px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td>[username]</td>
                        <td>[email]</td>
                        <td>[role]</td>
                        <td>[status]</td>
                        <td style="text-align: right;gap: 15px">
                            <a>
                                <i class="fa-solid fa-clipboard-list"></i>
                            </a>
                            <a>
                                <i class="fa-solid fa-user-gear"></i>
                            </a>
                            <a>
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" defer crossorigin="anonymous"></script>
    </body>
</html>