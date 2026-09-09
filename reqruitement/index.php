<?php
$currentPage = "daftar";

include __DIR__ . "/../data/menudb.php";
include __DIR__ . "/../logic/database.php";
include __DIR__ . "/../logic/registration/getUserDataByUserId.php";
include __DIR__ . "/../logic/registration/buildLeaderBoardWithStatusFilter.php";
include __DIR__ . "/../logic/getServerStatusByName.php";
$reqruitementStatus = getServerStatusByName($database, 'reqruitementPage');
if($reqruitementStatus['statusCode'] == 'reg_closed'){
    $type = 'reg_closed';
    include __DIR__ . '/../errorpage.php';
    exit;
}

session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: /login?url=/reqruitement");
    exit();
}
$id = $_SESSION['user_id'];

$data = getUserDataByUserId($database, $id);
$leaderboardData = buildLeaderBoardWithStatusFilter($database, ['unevaluated', 'pending']);
/*echo('<pre>');
print_r($leaderboardData);
echo('</pre>');*/
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Polisi Taruna - SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">DAFTAR KEANGGOTAAN</h1>
        <h2 style="color: var(--accent-gold)">SMK NEGERI 1 GIRITONTRO</h2>
        
        <?php include "../components/navbar.php" ?>

        <div class="containerV" style="margin: 30px">
            <h2 style="margin: 0px; text-align: left;">
                Dashboard
                <hr style="width: 100%;margin: 0px;">
            </h2>
            <div class="containerH" style="margin-top: 10px; gap: 10px;">
                <div class="itemPanel" style="min-width: 320px">
                    <h3 style="text-align: left;">Selamat Datang <?=$_SESSION['username']?>!</h3>
                    <p style="text-align: center"><?=$data[0]['name']?></p>
                    <p style="text-align: center"><?=$data[0]['kelas']?></p>
                    <small style="text-align: center"><?=$data[0]['no_absen']?></small>
                </div>
                <div class="itemPanel" style="min-width: 320px">
                    <div class="containerHImune" style="justify-content: space-between;">
                        <h3>Pendaftaran</h3>
                        <a class="primary-btn" style="padding: 5px 10px" href='choice'>
                            Daftar <i class="fa-solid fa-pen"></i></i>
                        </a>
                    </div>
                    <p id="status"><b>Status:</b> <?=$data[0]['statusPendaftaranCode'] == 'unverified' ? 
                    $data[0]['statusPendaftaran'] : $data[0]['statusPilihan'] ?? 'Belum Daftar'?></p>
                    <p id="rank"><b>Peringkat:</b> <?=isset($data[0]['statusPendaftaranCode']) ? 'N/A' : 'Belum Daftar'?></p>
                    <p><b>Sekor:</b> <?=$data[0]['sekor'] ?? 'Belum Dinilai'?></p>
                </div>
            </div>
            <div class="itemPanel" style="margin-top: 10px;">
                <h3 style="text-align: left;">Peringkat</h3>
                <?php if(isset($data[0]['jabatan']) && $data[0]['status_id'] != 1): ?>
                <div class="containerH">
                    <?php if(isset($data[0]['jabatan'])): ?>
                    <?php foreach($data as $item): ?>
                    <a class="teritary-btn jbtn-btn" data-jabatan-id="<?=$item['jabatanId']?>">
                        <?=$item['jabatan']?>
                    </a>
                    <?php endforeach; ?>
                    <?php endif;?>
                </div>
                <table style="margin-top: 10px;">
                    <tr>
                        <thead>
                            <th style="width: 40px">Rank</th>
                            <th>Nama</th>
                            <th class="THdesktop" style="width: 300px">Kelas</th>
                            <th class="THdesktop" style="width: 100px">Sekor</th>
                            <th class="THdesktop" style="width: 160px">Status</th>
                        </thead>
                    </tr>
                    <tr>
                        <tbody class="tbody-class">
                            <td>1</td>
                            <td>[nama]</td>
                            <td>[kelas]</td>
                            <td>[sekor]</td>
                        </tbody>
                    </tr>
                </table>
                <?php else: ?>
                    <p>Anda Belum Mendaftar/Memilih/Belum Di Verifikasi Jabatan Keanggotaan Poltar</p>
                    <p>Daftar Jika Anda Ingin Melihat Leaderboard</p>
                <?php endif;?>
            </div>
        </div>

        <?php include "../components/footer.php"; ?>

        <script>
            let leaderboard = <?=json_encode($leaderboardData)?>;
            const name = '<?=$data[0]['name']?>';
            const userId = <?=$id?>;
            const firstId = <?=$data[0]['jabatanId']?>;
        </script>
        <script src="/static/js/renderLeaderboardByJabatan.js"></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
    </body>
</html>