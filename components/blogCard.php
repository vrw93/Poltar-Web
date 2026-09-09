<a href="/Post?id=<?= $blogPost['id'] ?>" class="blogContainer">

    <?php if ($blogPost['image'] !== ""): ?>

        <img src="<?= $blogPost['image'] ?>">

    <?php endif; ?>
    
    <h2><?= $blogPost['title'] ?></h2>
    <small><?= $blogPost['createdAt'] ?></small>
    <p>
        <?= $blogPost['description'] ?>
        <br>
        <small style="margin: 0px">Baca Selengkapnya</small>
    </p>
</a>