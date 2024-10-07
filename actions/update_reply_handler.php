<?php
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_id = $_POST['reply_id'];
    $reply_content = $_POST['reply_content'];
    $reply_postid = $_POST['post_id'];

    $update_sql = "UPDATE replies SET content = ?, updated_at = NOW() WHERE reply_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $reply_content, $reply_id);
    $stmt->execute();

    header("location:../_page/view_post.php?post_id=" . $reply_postid);
    exit;
}
?>