<a href="/post/<?= $blogPost['slug'] ?>" class="blogContainer">

    <?php if ($blogPost['image'] !== ""): ?>

        <img src="<?= $blogPost['image'] ?>">

    <?php endif; ?>
    
    <h2><?= htmlspecialchars($blogPost['title']) ?></h2>
    <small><?= htmlspecialchars($blogPost['createdAt']) ?></small>
    <p>
        <?= htmlspecialchars($blogPost['description']) ?>
        <br>
        <small style="margin: 0px">Baca Selengkapnya</small>
    </p>
</a>