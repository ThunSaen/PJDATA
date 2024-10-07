<?php
require_once('../includes/db.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit;
}

$email = $_SESSION['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$new_email = $_POST['email'];

// อัปเดตข้อมูลโดยไม่เปลี่ยนรหัสผ่าน
$query = "UPDATE user SET first_name = ?, last_name = ?, email = ? WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $first_name, $last_name, $new_email, $email);

// ดำเนินการอัปเดตข้อมูล
if ($stmt->execute()) {
    // อัปเดตข้อมูลใน session
    $_SESSION['email'] = $new_email;
    header("location: ../_page/profile.php?edprofile_s=1");
    exit;
} else {
    echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
}

$stmt->close();
$conn->close();
?>
