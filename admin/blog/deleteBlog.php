<?php
include "../../logic/database.php";
include "../../logic/Blogs/deleteBlogPost.php";
include "../../logic/Blogs/getBlogById.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_SESSION['user_id'])){
        if($_SESSION['role_id'] !== 1){
            header("Location: /login");
            exit();
        }
    }else if(!isset($_SESSION['user_id'])){
        header("Location: /login");
        exit();
    }

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