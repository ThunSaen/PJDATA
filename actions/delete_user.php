<?php
require_once('../includes/db.php');


// ตรวจสอบว่ามีการส่งข้อมูลผ่าน GET หรือไม่
if (isset($_GET['user_id'])) {
    // รับ user_id ที่ต้องการลบ
    $user_id = $_GET['user_id'];

    // เริ่มเซสชันเพื่อดึงข้อมูล user_id , role ของผู้ใช้ปัจจุบัน
    session_start();
    $email = $_SESSION['email'];

    // ค้นหา user_id , role ของผู้ใช้ปัจจุบัน
    $stmt = $conn->prepare("SELECT user_id, role FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_user = $result->fetch_assoc();

    // ตรวจสอบว่าผู้ใช้เป็น admin หรือไม่
    if ($current_user['role'] == 'admin') {

        if ($user_id == $current_user['user_id']) {
            header("Location: ../_page/admin_page.php?deleteuserself_error=1");
            exit();
        }

        $conn->begin_transaction();

        try {
            $query = "DELETE FROM replies WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $query = "DELETE FROM post WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // ลบผู้ใช้จากฐานข้อมูล
            $query = "DELETE FROM user WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            // ตรวจสอบว่าการลบผู้ใช้สำเร็จหรือไม่
            if ($stmt->affected_rows > 0) {
                // ถ้าลบสำเร็จ ยืนยันการทำธุรกรรม
                $conn->commit();
                header("Location: ../_page/admin_page.php?deleteuser_success=1");
                exit();
            } else {
                // หากไม่มีการลบให้แสดงข้อผิดพลาด
                throw new Exception("Error deleting user.");
            }
        } catch (Exception $e) {
            // ยกเลิกการทำธุรกรรมหากมีข้อผิดพลาด
            $conn->rollback();
            header("Location: ../_page/admin_page.php?deleteuser_error=1");
            exit();
        }
    } else {
        // ผู้ใช้ไม่มีสิทธิ์ลบผู้ใช้คนนี้
        header("Location: ../_page/admin_page.php?deleteuser_permission_error=1");
        exit();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
} else {
    // ไม่มี user_id
    header("Location: ../_page/admin_page.php?error=เกิดข้อผิดพลาด");
    exit();
}
?>
