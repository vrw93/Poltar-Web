<?php
session_start();

include "../logic/auth/login.php";
include "../logic/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $success = login($database, $_POST['username'], $_POST['password']);

    if ($success) {
        header("Location: .");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login | SMK Negeri 1 Giritontro</title>
        <link rel="stylesheet" href="../static/css/mainStyle.css">
        <link rel="stylesheet" href="../static/css/theme.css">
        <link rel="stylesheet" href="static/css/admin.css">
        <link rel="icon" type="image/x-icon" href="https://poltarkenshiro.kesug.com/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body>
        <dialog id="popup">
            <h2>Login Page Di Pindah!</h2>
            <p>Di Pindah Ke <a style="color:lightblue;" href="../login">Login</a> !</p>
        </dialog>

        <h1>Login Page</h1>
        <h2 style="color: var(--accent-gold);">Poltar SMK Negeri 1 Giritontro</h2>
        <div style="margin-top: 50px;padding: 20px;align-items: center;" class="VContainer">
            <div class="itemPanel">
                <form method="POST" class="loginForm">
                    <input type="text" name="username" placeholder="Username"><br>
                    <input type="password" name="password" placeholder="Password"><br>
                    <button class="primary-btn" style="margin-top: 10px;width: 100%;">
                        Login
                    </button>
                </form>
                <a href="https://poltarkenshiro.kesug.com/" class="secondary-btn" style="margin-top:10px">
                    Back To Home
                </a>
            </div>
        </div>

        <script>
            window.onload = function() {
                const myPopup = document.getElementById('popup');
                myPopup.showModal();
            };
        </script>
    </body>
</html>