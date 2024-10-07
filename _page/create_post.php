<?php 
include('../includes/header.php');

if (!isset($_SESSION['email'])) {
    header("location: ../_page/login.php");
    exit;
}

?>

<div class="container mt-5">
    <h1>สร้างโพสต์ใหม่</h1>
    <form id="create_postFrom" method="POST" action="../actions/create_post.php">
        <div class="mb-3">
            <label for="title" class="form-label">หัวข้อโพสต์</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">เนื้อหาโพสต์</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">สร้างโพสต์</button>
    </form>
</div>

<script>
    const form = document.getElementById('create_postFrom');
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