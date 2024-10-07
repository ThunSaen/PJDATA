<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['reply_content'])) {
    $post_id = $_POST['post_id'];
    $reply_content = trim($_POST['reply_content']);
    $email = $_SESSION['email'];

    // ตรวจสอบว่ามีการป้อนข้อมูลหรือไม่
    if (empty($reply_content)) {
        echo "กรุณากรอกข้อความตอบกลับ";
        exit;
    }

    // ดึง user_id จาก email
    $query = "SELECT user_id FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        // เพิ่มข้อความตอบกลับลงในฐานข้อมูล
        $insert_query = "INSERT INTO replies (content, created_at, updated_at, post_id, user_id) VALUES (?, NOW(), NOW(), ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sii", $reply_content, $post_id, $user_id);
        $insert_stmt->execute();

        // ตรวจสอบว่าการเพิ่มสำเร็จหรือไม่
        if ($insert_stmt->affected_rows > 0) {
            header("Location: ../_page/view_post.php?post_id=" . $post_id);
            exit;
        } else {
            echo "ไม่สามารถเพิ่มข้อความตอบกลับได้";
        }
    } else {
        echo "ไม่พบข้อมูลผู้ใช้";
    }
}

// ถ้าไม่ใช่การ POST หรือไม่มีข้อมูลที่จำเป็น
header("location: ../index.php");
exit;
?>
