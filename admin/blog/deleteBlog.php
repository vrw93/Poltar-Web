<?php
include "../../logic/database.php";
include "../../logic/Blogs/deleteBlogPost.php";
include "../../logic/Blogs/getBlogById.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    include __DIR__ . '/../authHelper.php';

    $id = intval($_POST['id']);

    $blogPost = getBlogById($database, $id);

    if ($blogPost) {
        $imagePath = __DIR__ . "/../.." . $blogPost['image'];

        if(file_exists($imagePath)){
            unlink($imagePath);
        }
    }

    deletBlogPost($database, $id);
}

header("Location: /admin/blog");
exit();
?>