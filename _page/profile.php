<?php
include('../includes/header.php');
require_once('../includes/db.php'); // เชื่อมต่อกับฐานข้อมูล

if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$email = $_SESSION['email'];
$query = "SELECT first_name, last_name, role, created_at FROM user WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit;
}

$query_posts = "SELECT COUNT(*) as post_count FROM post WHERE user_id = (SELECT user_id FROM user WHERE email = ?)";
$stmt_posts = $conn->prepare($query_posts);
$stmt_posts->bind_param("s", $email);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();
$post_data = $result_posts->fetch_assoc();
$post_count = $post_data['post_count'];
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <h2>โปรไฟล์ของคุณ</h2>
        </div>
        <div class="card-body text-center">
            <img src="../images/profile.png" alt="รูปโปรไฟล์" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
            
            <h3>ชื่อ: <strong><?php echo htmlspecialchars($user['first_name']); ?></strong> 
                    <strong><?php echo htmlspecialchars($user['last_name']); ?></strong></h3>

            <h4>สถานะ: <span class="badge bg-primary"><?php echo htmlspecialchars($user['role']); ?></span></h4>
            
            <h4>Email: <strong><?php echo htmlspecialchars($email); ?></strong></h4>
            
            <h4>วันที่เข้าร่วมครั้งแรก: <strong><?php echo htmlspecialchars($user['created_at']); ?></strong></h4>

            <h4>จำนวนโพสต์: <strong><?php echo htmlspecialchars($post_count); ?></strong></h4>

        </div>
        <div class="card-footer text-center">
            <a href="../_page/edit_profile.php" class="btn btn-warning">แก้ไขโปรไฟล์</a>
        </div>
    </div>
</div>


<?php
    include('../includes/footer.php');
    $text = "";
    if (!empty($_REQUEST['edprofile_s'])) {
        $text = "แก้ไขข้อมูลแล้ว!";
    }

    if ($text != "") {
        echo "<script> Swal.fire('".$text."'); </script>";
    }
?>
