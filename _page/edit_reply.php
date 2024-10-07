<?php
include('../includes/header.php');

if (!isset($_SESSION['email'])) {
    header("location: ../_page/login.php");
    exit;
}

if (!isset($_GET['reply_id'])) {
    header("location: ../index.php");
    exit;
}

$reply_id = $_GET['reply_id'];
$post_id = $_GET['post_id'];
include('../includes/db.php');

// ดึง user_id ของผู้ใช้ที่เข้าสู่ระบบจากฐานข้อมูล
$email = $_SESSION['email'];
$user_sql = "SELECT user_id, role FROM user WHERE email = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    echo "ไม่พบผู้ใช้ที่เข้าสู่ระบบ";
    exit;
}

$user = $user_result->fetch_assoc();
$user_id_session = $user['user_id'];
$user_role = $user['role'];

// ดึงข้อมูลการตอบกลับจากฐานข้อมูล
$r_sql = "SELECT content, user_id FROM replies WHERE reply_id = ?";
$r_stmt = $conn->prepare($r_sql);
$r_stmt->bind_param("i", $reply_id);
$r_stmt->execute();
$result = $r_stmt->get_result();

if ($result->num_rows === 0) {
    echo "ไม่พบการตอบกลับที่ระบุ";
    exit;
}

$reply = $result->fetch_assoc();

// ตรวจสอบว่าผู้ใช้เป็นเจ้าของการตอบกลับหรือไม่
if ($reply['user_id'] !== $user_id_session && $user_role != 'admin') {
    header("location: ../index.php");
    exit;
}

?>

<div class="container mt-5">
    <div class="card" style="background-color: rgba(0, 0, 0, 0.5); border: 1px solid #fff; border-radius: 8px; padding: 20px;">
        <h5 class="text-white">แก้ไขการตอบกลับ</h5>
        <form id="editForm" action="../actions/update_reply.php" method="post">
            <input type="hidden" name="reply_id" value="<?= htmlspecialchars($reply_id) ?>">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>">
            <div class="mb-2">
                <textarea name="reply_content" rows="3" class="form-control" required><?= htmlspecialchars($reply['content']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
        </form>
    </div>
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