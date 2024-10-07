<?php
require_once('../includes/db.php');

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน GET หรือไม่
if (isset($_GET['post_id'])) {
    // รับ post_id ที่ต้องการลบ
    $post_id = $_GET['post_id'];

    // เริ่มเซสชันเพื่อดึงข้อมูล user_id จากเซสชัน
    session_start();
    $email = $_SESSION['email'];

    // ค้นหา user_id ตาม email
    $stmt = $conn->prepare("SELECT user_id, role FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_user_id = $user['user_id'];
    $user_role = $user['role'];

    // ตรวจสอบว่าโพสต์ที่ต้องการลบเป็นของผู้ใช้คนนี้หรือไม่
    $stmt = $conn->prepare("SELECT user_id FROM post WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        
        // ถ้า user_id ของโพสต์ตรงกับ user_id ของผู้ใช้ปัจจุบัน
        if ($post['user_id'] == $current_user_id || $user_role == 'admin') {
            // ลบ replies
            $stmt = $conn->prepare("DELETE FROM replies WHERE post_id = ?");
            $stmt->bind_param("i", $post_id);
            $stmt->execute();

            // ลบโพสต์
            $stmt = $conn->prepare("DELETE FROM post WHERE post_id = ?");
            $stmt->bind_param("i", $post_id);
            
            // ตรวจสอบว่าการลบโพสต์สำเร็จหรือไม่
            if ($stmt->execute()) {
                // ลบโพสต์สำเร็จ
                header("Location: ../index.php?deletepost_success=1");
                exit();
            } else {
                // มีข้อผิดพลาดในการลบโพสต์
                header("Location: ../index.php?deletepost_error=1");
                exit();
            }
        } else {
            // ผู้ใช้ไม่มีสิทธิ์ลบโพสต์นี้
            header("Location: ../index.php?deletepost_user_error=1");
            exit();
        }
    } else {
        // ไม่พบโพสต์
        header("Location: ../index.php?deletepost_post_not_found=1");
        exit();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
} else {
    // ไม่มี post_id
    header("Location: ../index.php?error=เกิดข้อผิดพลาด");
    exit();
}
?>