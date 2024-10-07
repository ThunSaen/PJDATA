<?php
require_once('../includes/db.php');

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // รับ user_id จากเซสชัน (คุณควรมีการตั้งค่าเซสชันไว้ก่อน)
    session_start();
    $email = $_SESSION['email'];

    // ค้นหา user_id ตาม email
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];

    // เตรียมคำสั่ง SQL สำหรับการสร้างโพสต์
    $stmt = $conn->prepare("INSERT INTO post (title, content, created_at, user_id) VALUES (?, ?, NOW(), ?)");
    $stmt->bind_param("ssi", $title, $content, $user_id);
    
    // ตรวจสอบว่าการเพิ่มโพสต์สำเร็จหรือไม่
    if ($stmt->execute()) {
        // โพสต์ถูกสร้างสำเร็จ
        header("Location: ../index.php?createpost_success=1");
        exit();
    } else {
        // มีข้อผิดพลาดในการสร้างโพสต์
        header("Location: ../index.php?createpost_error=1");
        exit();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
}
?>