<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit;
}

$reply_id = $_GET['reply_id'];
$post_id = $_GET['post_id'];
$email = $_SESSION['email'];

if ($_SESSION['role'] == 'admin') {
    $query = "DELETE FROM replies WHERE reply_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reply_id);
} else {
    $query = "DELETE r FROM replies r 
              JOIN user u ON r.user_id = u.user_id 
              WHERE r.reply_id = ? AND u.email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $reply_id, $email);
}

$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: ../_page/view_post.php?post_id=" . $post_id);
} else {
    header("location: ../index.php");
}

exit;
?>