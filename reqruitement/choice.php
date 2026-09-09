<?php
$currentPage = "daftar";
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: /login?url=/reqruitement"); exit(); }

include "../data/menudb.php";
include "../logic/registration/getJabatan.php";
include "../logic/registration/saveChoice.php";
include "../logic/registration/getChoicesData.php";
include "../logic/registration/components/getPendaftaranIdByuserId.php";
include "../logic/registration/components/getPendaftaranDataByUserId.php";
include "../logic/database.php";
include __DIR__ . "/../logic/getServerStatusByName.php";
$reqruitementStatus = getServerStatusByName($database, 'reqruitementPage');
if($reqruitementStatus['statusCode'] == 'reg_closed'){
    $type = 'reg_closed';
    include __DIR__ . '/../errorpage.php';
    exit;
}

$userId = $_SESSION['user_id'];
$pendaftaranData = getPendaftaranDataByUserId($database, $userId);
$jabatan = getJabatan($database);
$pendaftaranId = getPendaftaranIdByUserId($database, $userId);
$savedChoices = getChoiceData($database, $pendaftaranId);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(!isset($_SESSION['user_id'])){ header("Location: /login?url=/registration"); exit(); }
    if($reqruitementStatus['statusCode'] == 'reg_closed'){
        $type = 'reg_closed';
        include __DIR__ . '/../errorpage.php';
        exit;
    }

    $Choices = []; $arrayId = []; $prio = 0;
    for($i = 1; $i <= 4; $i++){
        if(!isset($_POST["p$i"])) continue;
        $val = $_POST["p$i"];
        if(!is_scalar($val)) die("Pilihan Tidak Valid!");
        $arrayId[] = $val;
        $Choices[] = ["id" => $val, "prio" => ++$prio];
    }

    if(count($arrayId) !== count(array_unique($arrayId)))
        die('Tidak boleh memilih jabatan yang sama lebih dari sekali');

    saveChoice($database, $Choices, $_POST['keahlian'], $userId);
    header("Location: /reqruitement");
}
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Polisi Taruna - SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="/static/css/registration.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align:center;">DAFTAR KEANGGOTAAN</h1>
        <h2 style="color:var(--accent-gold)">SMK NEGERI 1 GIRITONTRO</h2>
        <?php include "../components/navbar.php"; ?>

        <div class="containerV" style="margin:30px;">
            <h2 style="margin:0px;">Pemilihan Keanggotaan</h2>
            <form method="post">
                <div class="containerH" style="margin-top:10px">
                    <div class="itemPanel" style="gap:10px;min-width: 320px">
                        <div class="containerHImune" style="justify-content:space-between;">
                            <h3>Pilihan Jabatan</h3>
                            <button class="primary-btn" style="padding:3px 10px" type="submit">
                                Simpan <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </div>
                        <div class="containerH" style="margin-top:10px; gap: 10px">
                        <?php for($i = 1; $i <= 3; $i++): $id = $i - 1; $kuota = "N/A"; ?>
                            <div class="itemPanel" style="justify-content:flex-end;min-width: 280px">
                                <div class="containerHImune" style="justify-content:space-between;align-items:flex-end">
                                    <small>Prioritas <?=$i?></small>
                                    <button type="button" class="<?= isset($savedChoices[$id]) ? "danger-btn" : "hidden" ?>" style="padding:1px 10px">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                                <p>Pilihan Jabatan:</p>
                                <div class="containerHImune" style="justify-content:unset !important;">
                                    <select name="p<?=$i?>" <?= $i == 1 ? 'required' : '' ?>>
                                        <option value="" disabled selected hidden>-- Pilih Jabatan --</option>
                                        <?php foreach($jabatan as $item):
                                            $isSelected = isset($savedChoices[$id]) && $item['id'] == $savedChoices[$id]['jabatanId'];
                                            if($isSelected) $kuota = strval($item['kouta']);
                                        ?>
                                        <option value="<?=$item['id']?>" data-kouta=<?=$item['kouta']?> <?= $isSelected ? "selected" : "" ?>><?=$item['name']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p>Kouta : <?=$kuota?></p>
                                </div>
                            </div>
                        <?php endfor; ?>
                        </div>
                        <p>Keahlian:</p>
                        <textarea name="keahlian" placeholder="Masukkan Keahlian Kalian [Optional]"
                        ><?= $pendaftaranData['keahlian'] ?? ''?></textarea>
                    </div>
                </div>
            </form>
        </div>

        <?php include "../components/footer.php"; ?>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="/static/js/reqruitementQoutaUpdate.js"></script>
        <script src="/static/js/removeChoiceReqruitement.js"></script>
    </body>
</html>