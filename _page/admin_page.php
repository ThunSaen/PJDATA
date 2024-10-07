<?php
include('../includes/header.php');
require_once('../includes/db.php'); // เชื่อมต่อกับฐานข้อมูล

if ($_SESSION['role'] != 'admin') {
    header("location: ../index.php");
    exit;
}

$query = "SELECT user_id, first_name, last_name, email, role FROM user";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-white" style="border-left: 4px solid #d4252c; padding-left: 10px;">ผู้ดูแล</h5>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr class="text-7900FF">
                        <th>ID</th>
                        <th>ชื่อ</th>
                        <th>อีเมล</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($user = $result->fetch_assoc()) {
                            echo '<tr class="text-white">';
                            echo '<td>' . htmlspecialchars($user['user_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['role']) . '</td>';
                            echo '<td>
                                    <a class="text-danger" href="edit_user.php?user_id=' . htmlspecialchars($user['user_id']) . '">แก้ไข</a> | 
                                    <a class="text-danger" href="#" onclick="confirmDelete(' . htmlspecialchars($user['user_id']) . '); return false;">ลบ</a>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">ไม่พบข้อมูลผู้ใช้</td></tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId) {
    Swal.fire({
        title: 'คุณแน่ใจว่าต้องการลบผู้ใช้นี้?',
        text: "คุณจะไม่สามารถกู้คืนข้อมูลได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d4252c',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../actions/delete_user.php?user_id=' + userId;
        }
    });
}
</script>

<?php
include('../includes/footer.php');
$text = "";
if (!empty($_REQUEST['deleteuser_success'])) {
    $text = "ลบผู้ใช้งานสำเร็จแล้ว!";
} else if (!empty($_REQUEST['deleteuser_error'])) {
    $text = "เกิดข้อผิดพลาดในการลบผู้ใช้งาน!";
} else if (!empty($_REQUEST['deleteuser_permission_error'])) {
    $text = "คุณไม่มีสิทธิในการลบผู้ใช้งานนี้!";
} else if (!empty($_REQUEST['deleteuserself_error'])) {
    $text = "คุณไม่สามารถลบบัญชีของคุณเองได้";
} else if (!empty($_REQUEST['updateuser_success'])) {
    $text = "แก้ไขข้อมูลผู้ใช้งานสำเร็จแล้ว!";
} else if (!empty($_REQUEST['updateuser_error'])) {
    $text = "เกิดข้อผิดพลาดในการแก้ไขข้อมูลผู้ใช้งาน!";
}

if ($text != "") {
    echo "<script> Swal.fire('".$text."'); </script>";
}
?>
