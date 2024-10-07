<?php 
include('../includes/header.php');
require_once('../includes/db.php');

// ตรวจสอบว่าได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

// ตรวจสอบว่าได้ส่ง post_id มาหรือไม่
if (!isset($_GET['post_id'])) {
    header("location: index.php"); // เปลี่ยนเส้นทางหากไม่มี post_id
    exit;
}

$post_id = $_GET['post_id'];

// ดึง user_id ของผู้ใช้งานจาก email ในเซสชัน
$email = $_SESSION['email'];
$sql_user = "SELECT user_id, role FROM user WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    header("location: login.php"); // เปลี่ยนเส้นทางหากไม่พบผู้ใช้งาน
    exit;
}

$user = $result_user->fetch_assoc();
$user_id = $user['user_id'];
$user_role = $user['role'];

$sql = "SELECT title, content, user_id FROM post WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if ($result->num_rows === 0) {
    header("location: index.php");
    exit;
}

// ตรวจสอบว่าผู้ใช้งานเป็นเจ้าของโพสต์ / แอดมิน หรือไม่
if ($post['user_id'] != $user_id && $user_role != 'admin') {
    header("location: ../index.php");
    exit;
}

$stmt_user->close();
$stmt->close();
$conn->close();
?>

<div class="container mt-5">
    <h1>แก้ไขโพสต์</h1>
    <form id="editForm" method="POST" action="../actions/edit_post.php">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>">
        <div class="mb-3">
            <label for="title" class="form-label">หัวข้อโพสต์</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">เนื้อหาโพสต์</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>

<script>
    const form = document.getElementById('editForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณต้องการบันทึกการเปลี่ยนแปลงนี้?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, บันทึก!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<?php
include('../includes/footer.php');
?>

