<form method="post" class="formContainer" enctype="multipart/form-data">
    <div class="HContainer" style="padding:50px;">
        <div class="itemPanel" style="flex-grow: 3; flex-shrink:2; min-width:320px">
            <p>Editing <?=$blogPost['title'] ?? 'New Post' ?></p>
            <textarea name="content" placeholder="Content" required><?=$blogPost['content'] ?? '' ?></textarea>
        </div>
        <div class="itemPanel" style="max-width: 400px; min-width: 320px">
            <div class="submitContainer">
                <p>Details</p>
                <button class="primary-btn" type="submit" name="action" value="update">
                    <i class="fa-solid fa-paper-plane"></i>
                    Publish Post
                </button>
            </div>
            <input 
            type="text" name="title" maxlength="80" required
            placeholder="title" value="<?=$blogPost['title'] ?? '' ?>">

            <div class="imageUp">
                <?php if (isset($blogPost) && $blogPost['image'] !== ""): ?>
                    <img src="<?= $blogPost['image'] ?>" alt="thumbnail" id='img'>
                    <input type="hidden" name="file" value="<?= $blogPost['image'] ?>">
                    <input type="hidden" name="Fid" value="<?= $id ?>">
                    <button type="submit" name="action" value="del"
                    onclick="return confirm('Delete This Thumbnail?');"
                    class="delThumbnailBtn">
                        <i class="fa-solid fa-trash"></i>
                        Delete Thumbnail
                    </button>
                <?php else: ?>
                    <img src="" alt="preview" id="img" hidden>
                    <button type="button" id="delImg" class="delThumbnailBtn" hidden>
                        <i class="fa-solid fa-trash"></i>
                        Delete Thumbnail
                    </button>
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

                <input type="file" accept="image/png, image/jpeg, image/webp" name="image" id='thumbUpInpt'>
            </div>
            <textarea name="description" placeholder="Description" maxlength="150"><?=$blogPost['description'] ?? '' ?></textarea>
        </div>
    </div>
</form>
<script src="/static/js/admin/uploadThumbnailBlogBtn.js" defer></script>