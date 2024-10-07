<?php

session_start();
require_once('../includes/db.php');

// รับข้อมูลจากฟอร์ม
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    // ตรวจสอบว่ามีการกรอกข้อมูลทั้งสองฟิลด์หรือไม่
    echo "<script> Swal.fire('กรุณากรอกอีเมลและรหัสผ่าน!'); </script>";
    exit();
}

// ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// รับผลลัพธ์
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    // ตรวจสอบรหัสผ่าน
    $row = $result->fetch_assoc();
    
    // ใช้ password_verify เพื่อตรวจสอบรหัสผ่านที่แฮชแล้ว
    if (password_verify($password, $row['password'])) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        header("Location: ../?c=1");
        exit();
    } else {
        header("Location: ../_page/login.php?error_user=1");
        exit();
    }
} else {
    // ไม่พบผู้ใช้
        header("Location: ../_page/login.php?error_user=1");
        exit();
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
