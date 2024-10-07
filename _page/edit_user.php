<?php
include('../includes/header.php');
require_once('../includes/db.php'); // เชื่อมต่อกับฐานข้อมูล

if ($_SESSION['role'] != 'admin') {
    header("location: ../index.php");
    exit;
}

if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

// ตรวจสอบว่ามีการส่ง user_id ผ่าน URL หรือไม่
if (!isset($_GET['user_id'])) {
    header("location: admin_page.php");
    exit;
}

$user_id = $_GET['user_id'];

$query = "SELECT user_id, first_name, last_name, email, role FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit;
}

?>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-white" style="border-left: 4px solid #d4252c; padding-left: 10px;">แก้ไขผู้ใช้</h5>
            <form id="editUser" action="../actions/ad_user_edit.php" method="POST">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                <div class="mb-3">
                    <label for="first_name" class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">สถานะ</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Member</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                <a href="../_page/admin_page.php" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('editUser');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มทันที

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
                form.submit(); // ส่งฟอร์มเมื่อผู้ใช้กดยืนยัน
            }
        });
    });
</script>

<?php
    include('../includes/footer.php');
?>