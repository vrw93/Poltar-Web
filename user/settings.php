<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: /login?url=/user/settings");
    exit();
}

$currentPage = "";

require_once __DIR__ . "/../data/menudb.php";
require_once __DIR__ . "/../logic/database.php";
require_once __DIR__ . "/../logic/registration/getUserDataByUserId.php";
require_once __DIR__ . "/../logic/registration/getClass.php";
require_once __DIR__ . "/../logic/getDataFromDB.php";

$userId = (int)$_SESSION['user_id'];
$data = getUserDataByUserId($database, $userId);
$data = $data[0];
$class = getClass($database);

// Pagination
// History Data
$getDBData = new getDbData();

$limit = 2;
$count = $getDBData->getGeneralCountByUserId(
    $database, $userId, 'Users_Update_Request'
);

$historyData = $getDBData->getUpdateRequestHistory(
    $database, $userId, $limit
);

function getTypeNameByCode(string $code): string{
    $types = [
        'account' => '<i class="fa-solid fa-user-gear"></i> Pembaruan Akun',
        'biodata' => '<i class="fa-solid fa-address-card"></i> Pembaruan Biodata'
    ];

    if(isset($types[$code])){
        return $types[$code];
    }else{
        return $code;
    }
}

function getStatusIconByCode(string $code): string{
    $statuses = [
        'pending' => '<i class="fa-solid fa-clock"></i>',
        'accepted' => '<i class="fa-solid fa-circle-check"></i>',
        'rejected' => '<i class="fa-solid fa-circle-xmark"></i>',
        'partialy' => '<i class="fa-solid fa-circle-exclamation"></i>',
        'cancel'  => '<i class="fa-solid fa-ban"></i>'
    ];

    if(isset($statuses[$code])){
        return $statuses[$code];
    }else{
        return '';
    }
}
?>

<!DOCTYPE html>
<html land="id">
    <head>
        <title>User Settings | Poltar Kenhsiro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" content="<?=$_SESSION['csrf_token'] ?? '' ?>">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="stylesheet" href="/static/css/accountForm.css">
        <link rel="stylesheet" href="/static/css/animation.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">PENGATURAN</h1>
        <h2 style="color: var(--accent-gold);">Poltar SMK Negeri 1 Giritontro</h2>

        <?php include __DIR__ . "/../components/navbar.php"?>

        <div class="VContainer" style="margin: 30px">
            <h2 style="text-align: left; margin: 0px">Pengaturan Akun
            <hr></h2>
            <div class="HContainer">
                <div class="itemPanel" style="min-width:350px">
                    <h3 style="text-align: left">
                        <i class="fa-solid fa-user-gear"></i> Detail Akun
                    </h3>
                    <form id="accountDetail">
                        <p><b>Nama Pengguna</b>:</p>
                        <input name="username" type="text" value="<?=$data['username'] ?? ''?>"
                        required placeholder="Masukkan Nama Pengguna Anda">
                        <p><b>Email</b>:</p>
                        <input name="email" type="email" value="<?=$data['email'] ?? ''?>"
                        required placeholder="Masukkan Email Anda">

                        <button class="teritary-btn" type="submit"
                        style="padding: 5px; margin-top: 10px">
                            Perbarui Data <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </form>
                    <h3 style="text-align: left; margin-top: 10px">
                        <b><i class="fa-solid fa-key"></i> Kata Sandi</b>
                    </h3>
                    <form id="password">
                        <p><b>Kata Sandi Lama</b>:</p>
                        <input name="oldPassword" type="password" 
                        placeholder="Masukkan Kata Sandi Lama Anda">
                        <p><b>Kata Sandi Baru</b>:</p>
                        <input name="newPassword" type="password" 
                        placeholder="Masukkan Kata Sandi Baru Anda">
                        <p><b>Konfirmasi Kata Sandi Baru</b>:</p>
                        <input name="confirmPassword" type="password" 
                        placeholder="Masukkan Kata Sandi Baru Anda">
                        <button class="teritary-btn" type="submit" 
                        style="padding: 5px; margin-top: 10px">
                            Perbarui Kata Sandi <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </form>
                </div>
                <div class="VContainer" style="flex-grow:3;flex-basis:0;
                gap: 10px; min-width: 0; max-width:100%;">
                <div class="itemPanel" style="max-height: fit-content">
                    <h3 style="text-align: left">
                        <i class="fa-solid fa-address-card"></i> Detail Pengguna
                    </h3>
                    <form id="biodata">
                        <p><b>Nama Siswa</b>:</p>
                        <input name="name" type="text" required placeholder="Masukkan Nama Anda"
                        value="<?=$data['name'] ?? ''?>">
                        <div class="containerHImune">
                            <div>
                                <p><b>No Absen</b>:</p>
                                <input name="noAbsen" type="number" min="1" max="36" required
                                placeholder="No." value="<?=$data['no_absen'] ?? ''?>">
                            </div>
                            <div style="flex-grow: 1">
                                <p><b>Kelas</b>:</p>
                                <select name="kelas" style="width: 100%">
                                    <option value="" disabled hidden <?= empty($data['kelasId']) ? 'selected' : '' ?>>
                                        -- Pilih Kelas --
                                    </option>

                                    <?php foreach($class as $item): ?>
                                        <option
                                            value="<?=$item['id']?>"
                                            <?= ($data['kelasId'] ?? '') == $item['id'] ? 'selected' : '' ?>
                                        >
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
                <div class="itemPanel">
                    <h3 style="text-align: left">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Histori Permintaan Perubahan Data
                    </h3>
                    <div class="overflow-x" style="max-width: 100%; margin-bottom: 10px;">
                    <table style="min-width: 650px" id="historyPage">
                        <thead>
                            <tr>
                                <th style="width: 30px">No</th>
                                <th><i class="fa-solid fa-calendar-days"></i>Waktu</th>
                                <th><i class="fa-solid fa-pencil"></i>Jenis</th>
                                <th><i class="fa-regular fa-circle-question"></i>Status</th>
                                <th style="width: 110px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($historyData) === 0): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    <i class="fa-solid fa-folder-open fa-xl"></i>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    Belum Ada Permintaan Perubahan Detail Akun Yang Diajukan
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach($historyData as $index => $item): ?>
                            <tr>
                                <td style="text-align: center;"><?=++$index?></td>
                                <td><?=$item['createdAt']?></td>
                                <td><?=getTypeNameByCode($item['type'])?></td>
                                <td>
                                    <p class="tableStatus <?=$item['statusCode']?>">
                                        <?=getStatusIconByCode($item['statusCode'])?>
                                        <?=$item['status']?>
                                    </p>
                                </td>
                                <td class="VContainer" style="gap:1px">
                                    <button class="primary-btn detail" style="padding:3px;"
                                    data-request-id="<?=$item['id']?>" data-request-date="<?=$item['createdAt'] ?? 'N/A'?>">
                                        <i class="fa-solid fa-clipboard-list"></i>
                                        Detail
                                    </button>
                                    <button <?=$item['statusCode'] !== 'pending' ? 'disabled' : ''?>
                                    class="danger-btn cancel" style="padding:3px;"
                                    data-request-id="<?=$item['id']?>">
                                        <i class="fa-regular fa-circle-xmark"></i>
                                        Batalkan
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php
                        $pageId = 'historyPage';
                        $pageCount = ceil(($count/$limit));
                        include __DIR__ . "/../components/pagination.php";
                        unset($pageCount);
                        unset($pageId);
                    ?>
                </div>
            </div>
            </div>
        </div>

        <dialog id="updateDetail" 
        style="background:none; border:none;max-width: 90%;
        color: var(--color-text-primary); outline: none;">
            <div class="VContainer">
                <div class="tr-anchor" style="top:20px; right:20px">
                    <button class="danger-btn detail" style="padding: 1px">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="itemPanel">
                    <h2 style="margin: 0px;">
                        Detail Histori Permintaan Perubahan Data
                        <hr>
                    </h2>
                    <p style="text-align:center" id="updateDetailDateUi">
                        [tanggal]
                    </p>
                    <div class="overflow-x">
                    <table style="min-width: 700px">
                        <thead>
                            <tr>
                                <th style="width: 30px">No</th>
                                <th>Jenis</th>
                                <th>Data Lama</th>
                                <th>Data Baru</th>
                                <th style="width: 150px">Status</th>
                            </tr>
                        </thead>
                        <tbody id="updateDetailUi">
                            <tr>
                                <td style="text-align: center;">1</td>
                                <td>[data lama]</td>
                                <td>[data baru]</td>
                                <td>[status]</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </dialog>

        <?php include __DIR__ . "/../components/footer.php"?>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" defer crossorigin="anonymous"></script>
        <script src="/static/js/updateUserDetail.js" defer></script>
        <script src="/api/js/apiHelper.js" defer></script>
        <script src="/static/js/updatePassword.js" defer></script>
        <script src="/static/js/updateBiodata.js" defer></script>

        <script type="module" src="/static/js/cancelUpdateRequest.js" defer></script>
        <script type="module" src="/static/js/renderUpdateRequestDetail.js" defer></script>
        <script type="module" src="/static/js/pagination/main.js" defer></script>
        <script type="module" src="/static/js/pagination/userUpdateRequestHistory.js" defer></script>
        <script>
            const id = <?=$_SESSION['user_id']?>;
            const limit = "<?=$limit?>";
            
            let oldUsername = "<?=$data['username'] ?? ''?>";
            let oldEmail = "<?=$data['email'] ?? ''?>";
            let oldName = "<?=$data['name'] ?? ''?>";
            let oldNoAbsen = "<?=$data['no_absen'] ?? ''?>";
            let oldKelas = "<?=$data['kelasId'] ?? ''?>";
        </script>
    </body>
</html>