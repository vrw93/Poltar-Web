<form method="post" class="formContainer" enctype="multipart/form-data">
    <div class="HContainer" style="padding:50px">
        <div class="itemPanel" style="flex-grow: 3; flex-shrink:2;min-width:320px">
            <p>Editing <?=$galleryPost['title'] ?? 'New Post' ?></p>
            <textarea name="description" placeholder="Description" required><?=$galleryPost['description'] ?? '' ?></textarea>
            <small>*Note Markdown Is Not Supported</small>
        </div>
        <div class="itemPanel" style="max-width: 400px; min-width:320px">
            <div class="submitContainer">
                <p>Details</p>
                <button class="primary-btn" type="submit" name="action" value="update">
                    <i class="fa-solid fa-paper-plane"></i>
                    Publish Post
                </button>
            </div>
            <input 
            type="text" name="title" maxlength="40" required
            placeholder="Title" value="<?=$galleryPost['title'] ?? '' ?>">

            <div class="imageUp">
                <?php if (isset($galleryPost) && $galleryPost['image'] !== ""): ?>
                    <img src="<?= $galleryPost['image'] ?>" alt="thumbnail" id='img'>
                    <input type="hidden" name="file" value="<?= $galleryPost['image'] ?>">
                    <input type="hidden" name="Fid" value="<?= $id ?>">
                <?php else: ?>
                    <img src="" alt="preview" id="img" hidden>
                <?php endif; ?>

                <label for="thumbUpInpt" class="inputFile">
                    <button type="button" id="upBtn">
                        <i class="fa-solid fa-upload"></i>
                        Thumbnail
                    </button>
                    <span id="fileName" style="margin-left: 5px;text-overflow: ellipsis;">
                        Belum Ada File
                    </span>
                </label>

                <input type="file" accept="image/png, image/jpeg, image/webp" name="image" 
                id='thumbUpInpt'
                <?php if (!isset($galleryPost)): ?>
                required
                <?php endif; ?>
                >
            </div>
            <textarea name="summary" placeholder="Summary" maxlength="60"><?=$galleryPost['summary'] ?? '' ?></textarea>
        </div>
    </div>
</form>
<script src="/static/js/admin/uploadThumbnailBlogBtn.js" defer></script>