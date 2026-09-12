<?php
session_start();

require_once __DIR__ . '/../authHelper.php';

$currentPage = "";
require_once __DIR__ . "/../../logic/database.php";
require_once __DIR__ . "/../../data/adminmenudb.php";
require_once __DIR__ . "/../../data/iconData.php";
require_once __DIR__ . "/../../logic/getDataFromDB.php";
require_once __DIR__ . "/../../logic/registration/getClass.php";

$getDB = new getDbData();
$class = getClass($database);

#pagination
$limit = 10;
$usrCount = $getDB->getGeneralCount($database, 'Users');

$usersData = $getDB->getUsersData($database, $limit);

function getIconByCode($code, $icons){
    if(isset($icons[$code])){
        return $icons[$code];
    }else{
        return '';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Semua Pengguna | Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/animation.css">
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
                        <td>
                            <?=getIconByCode(
                                'usr-role-' . $data['roleCode'],
                                $icons
                            )
                            ?>
                            <?=$data['roleName'] ?? 'N/a'?>
                        </td>
                        <td>
                            <?=getIconByCode(
                                'usr-' . ($data['statusCode'] ?? 'unverified'),
                                $icons
                            )
                            ?>
                            <?=$data['statusName'] ?? 'Tidak Daftar'?>
                        </td>
                        <td style="text-align: right;gap: 15px">
                            <a class="no-bg-btn detail" title="Lihat Detail Pengguna"
                            data-user-id="<?=$data['id']?>">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </a>
                            <a class="no-bg-btn edit" title="Edit Biodata Pengguna"
                            data-user-id="<?=$data['id']?>">
                                <i class="fa-solid fa-user-gear"></i>
                            </a>
                            <a class="no-bg-btn role" title="Edit Role Pengguna"
                            data-user-id="<?=$data['id']?>">
                                <i class="fa-solid fa-user-tie"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php
                $pageId = 'allUserPage';
                $pageCount = ceil(($usrCount/$limit));
                include __DIR__ . "/../../components/pagination.php";
                unset($pageCount);
                unset($pageId);
            ?>
        </div>

        <dialog id="userDetailDialog" class="popup">
            <div class="VContainer" style="padding:10px">
                <div class="tr-anchor" style="top:25px; right:25px;">
                    <button class="secondary-btn detail" style="padding: 2px 4px">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <div class="itemPanel">
                    <h2 style="padding-bottom: 5px; margin-bottom:3px;
                    border-bottom:2px solid var(--color-light)">
                        Detail Pengguna
                    </h2>
                    <p style="color: var(--text-secondary);">
                        <i class="fa-solid fa-user"></i>
                        <b>Detail Akun</b>
                    </p>
                    <div class="dataDetail">
                        <span>Nama Pengguna</span><span>:</span><span>[username]</span>
                        <span>Email</span><span>:</span><span>[email]</span>
                        <span>Role</span><span>:</span><span>[role]</span>
                        <span>Dibuat Pada</span><span>:</span><span>[createdAt]</span>
                    </div>

                    <hr style="width: 100%">
                    
                    <p style="color: var(--text-secondary);">
                        <i class="fa-solid fa-address-card"></i>
                        <b>Detail Biodata</b>
                    </p>
                    <div class="dataDetail">
                        <span>Nama Lengkap</span><span>:</span><span>[name]</span>
                        <span>No Absen</span><span>:</span><span>[no_absen]</span>
                        <span>Kelas</span><span>:</span><span>[kelas]</span>
                    </div>
                    
                    <hr style="width: 100%">
                    
                    <p style="color: var(--text-secondary);">
                        <i class="fa-solid fa-user-plus"></i>
                        <b>Detail Pendaftaran</b>
                    </p>
                    <div class="dataDetail">
                        <span>Mendaftar Pada</span><span>:</span><span>[pendaftaranCreationTime]</span>
                        <span>Sekor</span><span>:</span><span>[sekor]</span>
                        <span>Verifikasi</span><span>:</span><span>[statusName]</span>
                    </div>
                </div>
            </div>
        </dialog>

        <dialog id="editBiodataDialog" class="popup"
        data-user-id="">
            <div class="VContainer">
                <div class="tr-anchor" style="top:25px; right:25px;">
                    <button class="secondary-btn edit" style="padding: 2px 4px">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <div class="itemPanel">
                    <h2 style="padding-bottom: 5px; margin-bottom:3px;
                    border-bottom:2px solid var(--color-light)">
                        Edit Biodata Pengguna
                    </h2>
                    <p style="color: var(--text-secondary);">
                        <i class="fa-solid fa-address-card"></i>
                        <b>Detail Biodata</b>
                    </p>
                    <form id="editBiodataForm" style="display:flex; flex-direction: column">
                        <p><b>Nama Lengkap:</b></p>
                        <input name="name" type="text" required style="flex-grow:1"
                        placeholder="Nama Lengkap Pengguna" value="">
                        <div class="containerHImune" style="justify-content: unset;">
                            <div>
                                <p><b>No Absen</b>:</p>
                                <input name="noAbsen" type="number" min="1" max="36" required
                                placeholder="No." value="" style="flex-grow:1">
                            </div>
                            <div>
                                <p><b>Kelas</b>:</p>
                                <select name="kelas" style="flex-grow:1">
                                    <option value="" disabled hidden selected> <!-- Make It Auto Select Using Selected -->
                                        -- Pilih Kelas --
                                    </option>

                                    <?php foreach($class as $item): ?>
                                        <option value="<?=$item['id']?>">
                                            <?=$item['name']?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <button class="teritary-btn" type="submit" 
                        style="padding: 5px; margin-top: 10px">
                            Perbarui Data <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </form>
                </div>
            </div>
        </dialog>

        <?php include __DIR__ . "/../../components/footer.php"; ?>

        <script src="https://kit.fontawesome.com/c2c5e95263.js" defer crossorigin="anonymous"></script>
        <script src="/api/js/apiHelper.js" defer></script>
        
        <script type="module" src="/static/js/admin/allUserPage/getUsersDetail.js" defer></script>
        <script type="module" src="/static/js/admin/allUserPage/editBiodata.js" defer></script>
        <script type="module" src="/static/js/pagination/main.js" defer></script>
    </body>
</html>