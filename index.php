<?php 
session_start();

$currentPage = "home"; 
include "logic/database.php";
include "logic/Blogs/getLatestBlog.php";
include "logic/Gallery/getLatestGalleryPost.php";
include "data/menudb.php";

$blogPosts = getLatestBlogPosts($database, 5);
$galleryPosts = getLatestGalleryPosts($database, 8);
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Polisi Taruna - SMK NEGERI 1 GIRITONTRO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="static/css/mainStyle.css">
        <link rel="stylesheet" href="static/css/theme.css">
        <link rel="stylesheet" href="static/css/gallery.css">
        <link rel="stylesheet" href="static/css/blogPost.css">
        <link rel="stylesheet" href="static/css/layout.css">
        <link rel="icon" type="image/x-icon" href="/static/image/poltarFav.ico">
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet">
    </head>
    <body id="mainPageBody">
        <div class="containerH">
            <div style="min-width: 400px;max-width: 500px;text-align: center; margin-top: 25px">
                <img src="/static/image/poltarLogo.webp" alt="poltarIcon" style="max-width: 55%;">
            </div>
            <div style="max-width: 500px;">
                <h1>
                    POLISI TARUNA <br> 
                    <span style="color: var(--accent-yellow)">SMK NEGERI 1 GIRITONTRO</span>
                </h1>
                <h2 class="tagline" style="text-align: left;">
                    <span>DISIPLIN</span>
                    <span style="color: white">&bull;</span>
                    <span>TANGGUNG JAWAB</span> 
                    <span style="color: white">&bull;</span>
                    <span>BERKARAKTER</span>
                </h2>
                <p class="description" style="margin-bottom: 30px;">
                    Membentuk generasi muda yang disiplin, bertanggung jawab,
                    serta berjiwa kepemimpinan untuk masa depan yang lebih baik.
                </p>

                <div class="containerH" style="gap: 20px; justify-content:left">
                    <a href="/reqruitement" class="primary-btn" ><i class="fa-solid fa-user-plus"></i> DAFTAR SEKARANG</a>
                    <a href="#gallery" class="secondary-btn"><i class="fa-solid fa-images"></i> LIHAT GALERI</a>
                </div>
            </div>
        </div>

        <div class="containerH mConBtn" style="margin-top: 50px;margin-bottom: 20px; gap:20px">
            <a class="containerButtons" href="#sejarah">
                <div class="roundContainer"><i class="fa-solid fa-book fa-xl"></i></div>
                <h3>SEJARAH</h3>
                <p>
                    Mengenal lebih jauh sejarah 
                    berdirinya Polisi Taruna SMK 
                    Negeri 1 Giritontro.
                </p>
            </a>
            <a class="containerButtons">
                <div class="roundContainer"><i class="fa-solid fa-users fa-xl"></i></div>
                <h3>STRUKTUR ANGGOTA</h3>
                <p>
                    Lihat struktur organisasi dan pembagian 
                    tugas Polisi Taruna.
                </p>
            </a>
            <a href="#Blogs" class="containerButtons">
                <div class="roundContainer"><i class="fa-solid fa-bullhorn fa-xl"></i></div>
                <h3>INFORMASI TERUPDATE</h3>
                <p>
                    Dapatkan informasi terbaru seputar kegiatan 
                    dan pengumuman.
                </p>
            </a>
            <a href="/reqruitement" class="containerButtons">
                <div class="roundContainer"><i class="fa-solid fa-clipboard-list fa-xl"></i></div>
                <h3>PENDAFTARAN</h3>
                <p>
                    Bergabunglah menjadi anggota 
                    Polisi Taruna SMK Negeri 1 Giritontro.
                </p>
            </a>
        </div>

        <?php include "components/navbar.php" ?>

        <div class="secondaryBG" id="Blogs">
            <h2 style="margin-top: 50px;">INFORMASI TERBARU</h2>
            <p style="text-align: center;">Informasi Terbaru Terkait Polisi Taruna Kenshiro</p>
            <div class="blogPost">
                <?php foreach ($blogPosts as $blogPost): ?>
                    <?php include "components/blogCard.php"; ?>
                <?php endforeach; ?>

                <a class="blogContainer" href="blogs" style="
                justify-content: center;align-items: center;"
                >
                    <h2>See More</h2>
                    <i class="fa-solid fa-angles-right fa-2xl"></i>
                </a>
            </div>
            <div class="containerHImune" style="margin-top: 10px">
                <a class="primary-btn" href="/blogs"
                style="text-align: center; width:fit-content;">
                    Lihat Lebih Banyak <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>

        <div class="primaryBG">
            <h2 style="margin-top: 50px;" id="gallery">Galeri Poltar</h2>
            <p style="text-align: center;">Galeri Kegiatan Polisi Taruna Kenshiro</p>
            <div class="galleryContainer">
                
                <?php foreach($galleryPosts as $galleryPost): ?>
                    <?php include "components/galleryCard.php"; ?>
                <?php endforeach ?>

            </div>
            <div class="containerHImune">
                <a class="teritary-btn" href="/gallery"
                style="text-align: center; width:fit-content;">
                    Lihat Lebih Banyak <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>

        <div class="secondaryBG" id="sejarah">
            <h2>Sejarah</h2>
            <p style="text-align: center;">Sejarah Singkat Polisi Taruna Kenshiro</p>
            <div class="containerH">
                <p style="font-size: 1.2rem; text-indent: 40px">
                    Poltar Awalnya Merurpakan Organisasi Yang Dibentuk Pada Tahun 
                    2011 Mengikuti Sistem Semi Militer senat batalyon. Namun Pada Tahun 
                    2016 Struktur Ini Dinilai Terlalu Tinggi Dan Diubah Dan Diintergrasikan 
                    Dengan Osis, Meskipun Demikian Poltar Tetaplah Garda Terdepan Dalam Menangani 
                    Ketidak 
                </p>
                <p style="font-size: 1.2rem; text-indent: 40px">
                    Tertipan Melalui Program-Program Seperti Razia Ketertiban Seragam, 
                    Pengajagaan Pintu, Pembinaan Fisik Pada Anggotanya, Dan Pelatihan Baris Berbaris.
                    Hingga Saat Ini Poltar Sudah Menjadi "Polisi Sekolah" Yang Disegani Oleh Banyak 
                    Siswa Siswi, Bukan Karena Ketakutan Tapi Penghormatan.
                </p>
            </div>
        </div>

        <?php include "components/galleryView.php"; ?>

        <?php include "components/footer.php"; ?>
        
        <script src="https://kit.fontawesome.com/c2c5e95263.js" defer crossorigin="anonymous"></script>
        <script src="static/js/main.js" defer></script>
    </body>
</html>