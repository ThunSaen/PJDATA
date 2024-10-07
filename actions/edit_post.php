<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit;
}

// ตรวจสอบว่ามีข้อมูลที่ส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $new_title = $_POST['title'];
    $new_content = $_POST['content'];

    // อัปเดตโพสต์ในฐานข้อมูล
    $update_sql = "UPDATE post SET title = ?, content = ?, updated_at = NOW() WHERE post_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $new_title, $new_content, $post_id);
    $update_stmt->execute();

    header("Location: ../_page/view_post.php?post_id=" . $post_id);
    exit;
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
