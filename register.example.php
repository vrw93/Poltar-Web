<?php
session_start();
include "data/menudb.php";
include "logic/account/createAccount.php";
include "logic/account/createBiodata.php";
include "logic/database.php";
include "logic/registration/getClass.php";
require_once "logic/rateLimiter.php";

$class = getClass($database);
$currentPage = "";
$url = $_GET['url'] ?? "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if (
        isRateLimited(
            $database,
            'register',
            5,
            600
        )
    ) {
        die('Terlalu banyak percobaan. Coba lagi 10 menit lagi.');
    }

    $token = $_POST['cf-turnstile-response'] ?? '';

    $data = [
        'secret' => 'Your Secret',
        'response' => $token,
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ]
    ];

    $context = stream_context_create($options);

    $result = file_get_contents(
        'https://challenges.cloudflare.com/turnstile/v0/siteverify',
        false,
        $context
    );

    $response = json_decode($result, true);

    if (!$response || !isset($response['success'])) {
        $_SESSION['success'] = "false";
        recordAttempt($database, 'register');
        die('Verifikasi captcha gagal (system error)');
    }

    $database->begin_transaction();
    
    #Html Escape
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $nama = htmlspecialchars($_POST['nama'], ENT_QUOTES, 'UTF-8');
    $no = htmlspecialchars($_POST['no'], ENT_QUOTES, 'UTF-8');
    $kelas = htmlspecialchars($_POST['kelas'], ENT_QUOTES, 'UTF-8');

    if($response['success']){
        try{
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userId = createAccount(
                $database, 
                $username ?? '', 
                $password_hash, 
                $email ?? '', 
                3
            );
            #die("userid: " . $userId . "|kelas: " . $_POST['kelas']);
            createbiodata(
                $database,
                $userId,
                $nama,
                $kelas, 
                $no
            );

            recordAttempt(
                $database,
                'register'
            );
            $_SESSION['success'] = "true";
            $_SESSION['msg'] = "Akun berhasil dibuat";
            $database->commit();

            usleep(300000);
            header("Location: /login?url=" . $url);
        }catch(Exception $e){
            $database->rollBack();
            $_SESSION['success'] = "false";
            $_SESSION['msg'] = "Username/Email sudah digunakan";
            recordAttempt(
                $database,
                'register'
            );
        }
        
    }else{
        $_SESSION['success'] = "false";
        $_SESSION['msg'] = "Captcha Gagal";
        recordAttempt(
            $database,
            'register'
        );
    }
}
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Register | Poltar Kenshiro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="static/css/mainStyle.css">
        <link rel="stylesheet" href="static/css/theme.css">
        <link rel="stylesheet" href="static/css/gallery.css">
        <link rel="stylesheet" href="static/css/layout.css">
        <link rel="stylesheet" href="static/css/registration.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">REGISTER</h1>
        <h2 style="color:var(--accent-gold);">Polisi Taruna SMK NEGERI 1 GIRITONTRO</h2>

        <?php include "components/navbar.php"?>

        <div class="VContainer" style="align-items: center; padding: 30px">
            <form method="post" class="containerH reg" style="flex-grow: 1;">
                <div class="itemPanel">
                    <h2 style="margin-bottom:5px">Polisi Taruna</h2>
                    <h3 style="color: var(--text-secondary);text-align: center !important;">
                        SMK NEGERI 1 GIRITONTRO
                    </h3>
                    <p style="color: var(--accent-yellow);">Daftar Akun</p>
                    <p><b>Nama Pengguna:</b></p>
                    <input name="username" type="text" placeholder="Masukkan Username" required
                    value="<?=$_POST['username'] ?? '' ?>">
                    <p><b>Email:</b></p>
                    <input name="email" type="email" placeholder="Masukkan Email" required
                    value="<?=$_POST['email'] ?? '' ?>" minlength="8">
                    <p><b>Kata Sandi:</b></p>
                    <div>
                        <input name="password" type="password" placeholder="Buat Kata Sandi Anda" 
                        required minlength="8" value="<?=$_POST['password'] ?? '' ?>" id="pwField">
                        <button type="button" id="pwBtn"><i class="fa-solid fa-eye"></i></button>
                    </div>
                    <p><b>Captcha:</b></p>
                    <div class="cf-turnstile"
                        data-sitekey="0x4AAAAAADnNJ12ESavAPF-H">
                    </div>

                    <?php if(isset($_SESSION['success'])): ?>
                        <?php if($_SESSION['success'] == "false"): ?>
                            <p style="color: var(--danger-red)"><?= $_SESSION['msg'] ?? 'Terjadi kesalahan' ?></p>
                        <?php endif; ?>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <button class="primary-btn" style="padding: 5px; margin-top: 10px;" 
                    type="submit">Register</button>
                    <a style="color: var(--accent-light-blue);" href="/login?url=<?=$url ?? '.'?>">Sudah Punya Akun?</a>
                </div>
                <?php include 'components/biodataForm.php'; ?>
            </form>
        </div>

        <?php include "components/footer.php"; ?>
        
        <script src="static/js/passwordShow.js"></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    </body>
</html>