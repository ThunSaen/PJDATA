<?php
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $query = "UPDATE user SET first_name = ?, last_name = ?, email = ?, role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $role, $user_id);

    if ($stmt->execute()) {
        header("location: ../_page/admin_page.php?updateuser_success=1");
    } else {
        header("location: ../_page/admin_page.php?updateuser_error=1");
    }
}
?>