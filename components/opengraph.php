<?php
$ogUrl = 'https://poltar.smkn1giritontro.sch.id'
    . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$ogImage = !empty($currentPost['image'])
    ? 'https://poltar.smkn1giritontro.sch.id' . $currentPost['image']
    : 'https://poltar.smkn1giritontro.sch.id/static/image/poltarLogo.webp';
$ogType = isset($currentPost) ? 'article' : 'website';
$ogDescription = isset($currentPost['description']) 
    ? $currentPost['description'] 
    : "Sebuah platform berbasis web untuk Polisi Taruna SMK Negeri 1 GIRITONTRO yang menyediakan blog, galeri, dan sistem pendaftaran keanggotaan Poltar.";
?>

<meta property="og:site_name" content="Poltar Web">

<meta
    property="og:description"
    content="<?=htmlspecialchars($ogDescription, ENT_QUOTES, 'UTF-8')?>"
>

<meta
    property="og:image"
    content="<?=htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8')?>"
>

<meta property="og:type" content="<?=$ogType?>">

<meta
    property="og:url"
    content="<?=htmlspecialchars($ogUrl, ENT_QUOTES, 'UTF-8')?>"
>

<meta
    property="og:title"
    content="<?=htmlspecialchars($title ?? 'Poltar Web', ENT_QUOTES, 'UTF-8')?>"
>