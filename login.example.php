<?php
$currentPage = "";
$url = $_GET['url'] ?? ".";

session_start();
include "data/menudb.php";
include "logic/auth/login.php";
include "logic/database.php";
require_once "logic/rateLimiter.php";

if(isset($_SESSION['user_id'])){
    header("Location: $url");
}

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

    if($response['success']){
        $success = login($database, $_POST['username'], $_POST['password']);
        $_SESSION['success'] = $success;

        if($success){
            session_regenerate_id(true);

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            header("Location: $url");
            exit();
        }
        recordAttempt(
            $database,
            'login failed'
        );
        $_SESSION['success'] = false;
        $_SESSION['err_message'] = 'Password Atau Username Salah';
    }else{
        $_SESSION['success'] = false;
        $_SESSION['err_message'] = 'Captcha Gagal';
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
        <title>Login | Poltar Kenshiro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/mainStyle.css">
        <link rel="stylesheet" href="/static/css/theme.css">
        <link rel="stylesheet" href="/static/css/gallery.css">
        <link rel="stylesheet" href="/static/css/accountForm.css">
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <h1 style="text-align: center;">LOGIN</h1>
        <h2 style="color:var(--accent-gold)">Polisi Taruna SMK NEGERI 1 GIRITONTRO</h2>

        <?php include "components/navbar.php"?>

        <div class="VContainer" style="align-items: center; padding: 30px">
            <div class="centerPanel">
                <form method="post">
                    <h2>Polisi Taruna</h2>
                    <h3 style="margin-top: 5px; color: var(--text-secondary);">SMK NEGERI 1 GIRITONTRO</h3>
                    <p style="color: var(--accent-yellow);">Masuk Akun</p>
                    <p><b>Nama Pengguna Atau Email:</b></p>
                    <input name="username" type="text" placeholder="Masukkan Nama Pengguna Anda" required
                    value="<?=$_POST['username'] ?? '' ?>">
                    <p><b>Kata Sandi:</b></p>
                    <div class="containerHImune">
                        <input name="password" type="password" placeholder="Masukkan Kata Sandi Anda" 
                        required id="pwField">
                        <button class="primary-btn" type="button" id="pwBtn" 
                        style="padding: 5px 10px">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p><b>Captcha:</b></p>
                    <div class="cf-turnstile"
                        data-sitekey="0x4AAAAAADnNJ12ESavAPF-H">
                    </div>

                    <?php if(isset($_SESSION['success'])): ?>
                        <?php if($_SESSION['success'] == false): ?>
                            <p style="color: var(--danger-red)"><?=$_SESSION['err_message']?></p>
                        <?php endif; ?>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>


                    <button class="primary-btn" style="padding: 5px; margin-top: 10px;" 
                    type="submit">Masuk</button>
                    <a style="color: var(--accent-light-blue);" href="/register?url=<?=$url ?? ''?>">Tidak Punya Akun?</a>
                </form>
            </div>
        </div>

        <?php include "components/footer.php"; ?>

        <script src="static/js/passwordShow.js"></script>
        <script src="https://kit.fontawesome.com/c2c5e95263.js" crossorigin="anonymous"></script>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    </body>
</html>