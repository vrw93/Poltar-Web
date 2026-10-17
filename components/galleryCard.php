<button class="galleryItem"
data-gallery-description="<?=htmlspecialchars($galleryPost['description'])?>" 
data-gallery-createdAt="<?=htmlspecialchars($galleryPost['createdAt'])?>"
>
    <img src="<?=$galleryPost['image'] ?>">
    <div class="galleryCard">
        <h3><?=htmlspecialchars($galleryPost['title']) ?></h3>
        <small><?=htmlspecialchars($galleryPost['summary']) ?></small>
    </div>
</button>