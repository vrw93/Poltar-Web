<?php
session_start();

require_once __DIR__ . '/../authHelper.php';

$currentPage = "";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";

$getDB = new getDbData();

#pagination
$limit = 1;
$usrCount = 10;

$usersData = $getDB->getUsersData($database);
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
                        <th>
                            <i class="fa-solid fa-id-card"></i>
                            Nama Pengguna
                        </th>
                        <th>
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </th>
                        <th style="width: 150px">
                            <i class="fa-solid fa-user-tie"></i>
                            Role
                        </th>
                        <th style="width: 150px">
                            <i class="fa-solid fa-user-tag"></i>
                            Status
                        </th>
                        <th style="width: 80px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    foreach($usersData as $data):
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <?=$index++?>
                        </td>
                        <td><?=$data['username'] ?></td>
                        <td><?=$data['email'] ?></td>
                        <td><?=$data['roleName'] ?></td>
                        <td><?=$data['statusName'] ?></td>
                        <td style="text-align: right;gap: 15px">
                            <a class="no-bg-btn detail">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </a>
                            <a class="no-bg-btn edit">
                                <i class="fa-solid fa-user-gear"></i>
                            </a>
                            <a class="no-bg-btn role">
                                <i class="fa-solid fa-user-tie"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" defer crossorigin="anonymous"></script>
    </body>
</html>