<button class="galleryItem"
data-gallery-description="<?=$galleryPost['description']?>" 
data-gallery-createdAt="<?=$galleryPost['createdAt']?>"
>
    <img src="<?=$galleryPost['image'] ?>">
    <div class="galleryCard">
        <h3><?=$galleryPost['title'] ?></h3>
        <small><?=$galleryPost['summary'] ?></small>
    </div>
</button>