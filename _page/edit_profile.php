<?php
include('../includes/header.php');
require_once('../includes/db.php'); // เชื่อมต่อกับฐานข้อมูล

if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

$email = $_SESSION['email'];

$query = "SELECT first_name, last_name, email FROM user WHERE email = ?";
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

?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <h2>แก้ไขโปรไฟล์ของคุณ</h2>
        </div>
        <div class="card-body">
            <form id="editProfile" action="../actions/edit_profile.php" method="POST">
                <div class="mb-3">
                    <label for="first_name" class="form-label">ชื่อ</label>
                    <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">นามสกุล</label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
<!--                <div class="mb-3">
                    <label for="password" class="form-label">เปลี่ยนรหัสผ่าน (ถ้าต้องการ)</label>
                    <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่านใหม่">
                </div> -->
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            </form>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('editProfile');
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