<?php
include('../includes/header.php');

if (!isset($_SESSION['email'])) {
    header("location: login");
    exit;
}

if (!isset($_GET['post_id'])) {
    header("location: index.php"); // เปลี่ยนเส้นทางหากไม่มี post_id
    exit;
}
$post_id = $_GET['post_id'];

require_once('../includes/db.php');

$email = $_SESSION['email'];
$query = "SELECT user_id, first_name, last_name, role, created_at FROM user WHERE email = ?";
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


// ดึงข้อมูลโพสต์จากฐานข้อมูลโดยใช้ post_id
$sql = "SELECT p.title, p.content, p.updated_at, p.user_id, u.first_name as owner_first_name, u.last_name as owner_last_name
        FROM post p
        JOIN user u ON p.user_id = u.user_id
        WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่าโพสต์มีอยู่ในฐานข้อมูล
if ($result->num_rows === 0) {
    header("location: ../index.php"); // เปลี่ยนเส้นทางหากไม่มีโพสต์ที่ตรงกัน
    exit;
}

$post = $result->fetch_assoc();


// การตอบกลับ
$r_sql = "SELECT r.reply_id, r.content, r.updated_at, r.user_id, u.first_name, u.last_name 
          FROM replies r 
          JOIN user u ON r.user_id = u.user_id 
          WHERE r.post_id = ? 
          ORDER BY r.updated_at DESC";
$r_stmt = $conn->prepare($r_sql);
$r_stmt->bind_param("i", $post_id);
$r_stmt->execute();
$result_replies = $r_stmt->get_result();

// ปิดการเชื่อมต่อกับฐานข้อมูล
$stmt->close();
$r_stmt->close();
$conn->close();

?>

<div class="container mt-5">
    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>">
    <div class="card" style="background-color: rgba(0, 0, 0, 0.6); border: 1px solid #fff; border-radius: 8px; padding: 20px;">
        <div class="mb-3">
            <label for="title" class="form-label text-white" style="font-size: 1.8rem;"><?= htmlspecialchars($post['title']) ?></label>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label text-white" style="font-size: 1rem; display: block; overflow-wrap: break-word; white-space: normal; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($post['content']) ?></label>
        </div>
        <div class="d-flex align-items-start mb-3" style="background-color: rgba(255, 255, 255, 0.1); border-radius: 5px; padding: 10px;">
            <img src="../images/profile.png" alt="Profile Picture" class="rounded-circle me-3" style="width: 40px; height: 40px;">
            <div>
                <label class="form-label text-white" style="font-size: 1.0rem; margin-bottom: 0;">
                    <?= htmlspecialchars($post['owner_first_name']) . ' ' . htmlspecialchars($post['owner_last_name']) ?>
                </label>
                <div style="margin-top: -5px;">
                    <small class="text-white" style="font-size: 0.8rem;">
                        แก้ไขโพสต์ล่าสุด: <?= htmlspecialchars($post['updated_at']) ?>
                    </small>
                </div>
            </div>
        </div>
        <?php
        if ($post['user_id'] == $user['user_id'] || $user['role'] == 'admin') {
            echo '<a href="../_page/edit_post.php?post_id=' . htmlspecialchars($post_id) . '" class="btn btn-primary btn-sm">แก้ไขโพสต์</a>';
            echo '<a href="#" onclick="confirmDeletePost(' . htmlspecialchars($post_id) . ')" class="btn btn-danger btn-sm">ลบโพสต์</a>';
        }
        ?>
    </div>
</div>


<div class="container mt-5">
    <h5 class="text-white">ข้อความตอบกลับ</h5>
    <?php
    if ($result_replies->num_rows > 0) {
        while ($reply = $result_replies->fetch_assoc()) {
            ?>
            <div class="card mb-3" style="background-color: rgba(0, 0, 0, 0.3); border: 1px solid #fff; border-radius: 8px; padding: 20px;">
                <div class="mb-3">
                    <label class="form-label text-white" style="font-size: 1rem; display: block; overflow-wrap: break-word; white-space: normal; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($reply['content']) ?></label>
                </div>
                <div class="d-flex align-items-start mb-3" style="background-color: rgba(255, 255, 255, 0.1); border-radius: 5px; padding: 10px;">
                    <img src="../images/profile.png" alt="Profile Picture" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                    <div>
                        <label class="form-label text-white" style="font-size: 1.0rem; margin-bottom: 0;">
                            <?= htmlspecialchars($reply['first_name']) . ' ' . htmlspecialchars($reply['last_name']) ?>
                        </label>
                        <div style="margin-top: -5px;">
                            <small class="text-white" style="font-size: 0.8rem;">
                                ตอบกลับล่าสุด: <?= htmlspecialchars($reply['updated_at']) ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php
                // เพิ่มปุ่มแก้ไขถ้าเจ้าของโพสต์เป็นผู้ตอบ
                if ($reply['user_id'] == $user['user_id'] || $user['role'] == 'admin') {
                    echo '<a href="../_page/edit_reply.php?reply_id=' . htmlspecialchars($reply['reply_id']) . '&post_id=' . htmlspecialchars($post_id) . '" class="btn btn-primary btn-sm">แก้ไขตอบกลับ</a>';
                    echo '<a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete(\'' . htmlspecialchars($reply['reply_id']) . '\', \'' . htmlspecialchars($post_id) . '\')">ลบข้อความตอบกลับ</a>';
                }
                ?>

            </div>
            <?php
        }
    } else {
        echo '<p class="text-white">ไม่มีข้อความตอบกลับ</p>';
    }
    ?>
</div>

<!-- ช่องสำหรับการตอบกลับโพสต์ -->
<div class="container mt-3">
    <div class="card" style="background-color: rgba(0, 0, 0, 0.5); border: 1px solid #fff; border-radius: 8px; padding: 20px;">
        <div class="mt-2">
            <h5 class="text-white">ตอบกลับโพสต์</h5>
            <form id="replyFrom" action="../actions/create_reply.php" method="post">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>">
                <div class="mb-2">
                    <textarea name="reply_content" rows="3" class="form-control" placeholder="เขียนตอบกลับ..." required></textarea>
                </div>
                <button type="submit" class="btn btn-success">ส่งตอบกลับ</button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDeletePost(postId) {
    Swal.fire({
        title: 'คุณต้องการจะลบโพสต์หรือไม่?',
        text: "คุณจะไม่สามารถกู้คืนโพสต์นี้ได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบโพสต์!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../actions/delete_post.php?post_id=' + postId;
        }
    });
}
</script>

<script>
    const form = document.getElementById('replyFrom');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มทันที

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณต้องการโพสต์ข้อความตอบกลับนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ตอบกลับข้อความ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // ส่งฟอร์มเมื่อผู้ใช้กดยืนยัน
            }
        });
    });
</script>

<script>
function confirmDelete(replyId, postId) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณจะไม่สามารถกู้คืนข้อความนี้ได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบข้อความ!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../actions/delete_reply.php?reply_id=' + replyId + '&post_id=' + postId;
        }
    });
}
</script>

<?php
include('../includes/footer.php');
?>