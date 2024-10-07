<?php

session_start();
require_once('../includes/db.php');

// รับข้อมูลจากฟอร์ม
$f_name = $_POST['name'];
$l_name = $_POST['surname'];
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($f_name) || empty($l_name) || empty($email) || empty($password)) {
    header("Location: http://localhost/_page/register.php?error_empty=1");
    exit();
}

// ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// รับผลลัพธ์
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: http://localhost/_page/register.php?error_e=1");
} else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $f_name, $l_name, $email, $hashed_password);

    if($stmt->execute()) {
        header("Location: http://localhost/_page/login.php?s=1");
    } else {
        
        header("Location: http://localhost/_page/register.php?e=1");
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
