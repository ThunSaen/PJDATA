<div class="container my-5">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h6 class="border-bottom pb-2 mb-0">Posts updates</h6>

        <?php
            require_once 'includes/db.php';
            
            if (!isset($_SESSION['email'])) {
                echo "กรุณาล็อกอินเพื่อดูโพสต์.";
                exit;
            }

            // ดึง user_id ของผู้ใช้งานที่ล็อกอิน
            $email = $_SESSION['email'];
            $sql_user = "SELECT user_id, role FROM user WHERE email = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("s", $email);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user_data = $result_user->fetch_assoc();
            $current_user_id = $user_data['user_id'];
            $user_role = $user_data['role'];
            
            
            if ($user_data === null) {
                // ถ้าไม่มีข้อมูลผู้ใช้ ให้เด้งไปหน้าล็อกอิน
                header("Location: ../_page/login.php");
                exit;
            }
            
            // กำหนดจำนวนโพสต์ที่จะแสดงต่อหน้า
            $posts_per_page = 10;

            // ตรวจสอบหน้าปัจจุบันที่ผู้ใช้เลือก
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $current_page = max(1, $current_page); 
            $offset = ($current_page - 1) * $posts_per_page;

            // ตรวจสอบการเลือกการเรียงลำดับของผู้ใช้
            $order = 'updated_at DESC'; // ค่าเริ่มต้น
            if (isset($_GET['order'])) {
                switch ($_GET['order']) {
                    case 'created_at ASC':
                        $order = 'created_at ASC';
                        break;
                    case 'created_at DESC':
                        $order = 'created_at DESC';
                        break;
                    case 'updated_at ASC':
                        $order = 'updated_at ASC';
                        break;
                    case 'updated_at DESC':
                        $order = 'updated_at DESC';
                        break;
                }
            }

            // คำสั่ง SQL เพื่อดึงข้อมูลโพสต์โดยการเรียงลำดับ
            $sql = "SELECT post.post_id, post.title, post.content, post.created_at, post.updated_at, user.user_id, user.first_name, user.last_name 
                    FROM post 
                    JOIN user ON post.user_id = user.user_id 
                    ORDER BY post.$order 
                    LIMIT ?, ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $offset, $posts_per_page);
            $stmt->execute();
            $result = $stmt->get_result();

            // ดึงจำนวนโพสต์ทั้งหมดจากฐานข้อมูล
            $total_sql = "SELECT COUNT(*) as total FROM post";
            $total_result = $conn->query($total_sql);
            $total_posts = $total_result->fetch_assoc()['total'];
            $total_pages = ceil($total_posts / $posts_per_page);
        ?>

        <!-- เรียงลำดับ -->
        <div class="mb-3">
            <form method="get">
                <select id="order" name="order" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="created_at DESC" <?= $order == 'created_at DESC' ? 'selected' : '' ?>>โพสต์ล่าสุด</option>
                    <option value="created_at ASC" <?= $order == 'created_at ASC' ? 'selected' : '' ?>>โพสต์เก่าสุด</option>
                    <option value="updated_at DESC" <?= $order == 'updated_at DESC' ? 'selected' : '' ?>>อัพเดทล่าสุด</option>
                    <option value="updated_at ASC" <?= $order == 'updated_at ASC' ? 'selected' : '' ?>>อัพเดทเก่าสุด</option>
                </select>
            </form>
        </div>

        <?php
            // แสดงโพสต์
            while ($post = $result->fetch_assoc()) { 
                echo '<div class="d-flex justify-content-between text-body-secondary pt-3" style="overflow: hidden;">';
                echo '<div class="flex-grow-1" style="max-width: 100%;">';
                echo '<p class="pb-3 mb-0 small lh-sm border-bottom" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">';
                echo '<strong class="d-block text-gray-dark">เจ้าของโพสต์: ' . htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) . '</strong>';
                echo '<strong class="d-block mt-1" style="font-size: 1.5rem;">' . htmlspecialchars($post['title']) . '</strong>';
                echo '<span style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">' . htmlspecialchars($post['content']) . '</span>';   
                echo '<br><small class="text-muted">โพสต์เมื่อ: ' . htmlspecialchars($post['created_at']) . ' | อัพเดทล่าสุด: ' . htmlspecialchars($post['updated_at']) . '</small>';
                
                echo '<br><a href="../_page/view_post.php?post_id=' . htmlspecialchars($post['post_id']) . '" class="btn btn-primary btn-sm">ดูโพสต์</a> ';
                if ($post['user_id'] == $current_user_id || $user_data['role'] == 'admin') {
                    echo '<a href="../_page/edit_post.php?post_id=' . htmlspecialchars($post['post_id']) . '" class="btn btn-warning btn-sm">แก้ไขโพสต์</a> ';
                    echo '<a href="#" onclick="confirmDelete(' . htmlspecialchars($post['post_id']) . ')" class="btn btn-danger btn-sm">ลบโพสต์</a>';
                
                }
                echo '</p>';
                echo '</div>';
                echo '</div> <br>';
            }

            // แสดงลิงก์สำหรับการเปลี่ยนหน้า
            echo '<nav aria-label="Page navigation">';
            echo '<ul class="pagination justify-content-center">';

            //$order_param = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : 'created_at DESC';
            //$order_param = 'created_at DESC';
            //if (isset($_GET['order'])) {
            //    $order_param = htmlspecialchars($_GET['order']);
            //}

            // ลิงก์ไปยังหน้าก่อนหน้า
            if ($current_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '&order=' . $order . '">Previous</a></li>';
            }
            
            // ลิงก์สำหรับแต่ละหน้า
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<li class="page-item' . ($i === $current_page ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '&order=' . $order . '">' . $i . '</a></li>';
            }
            
            // ลิงก์ไปยังหน้าถัดไป
            if ($current_page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '&order=' . $order . '">Next</a></li>';
            }

            echo '</ul>';
            echo '</nav>';

            // ปิดการเชื่อมต่อกับฐานข้อมูล
            $stmt->close();
            $conn->close();
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(postId) {
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
