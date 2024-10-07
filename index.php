<?php
include('includes/header.php');
?>

<?php 
include('includes/all_post.php');
?>

<?php
include('includes/footer.php');
$text = "";
if (!empty($_REQUEST['c'])) {
    $text = "ล็อคอินสำเร็จแล้ว!";
} else if (!empty($_REQUEST['createpost_success'])) { // popup สร้าง
    $text = "สร้างโพสต์สำเร็จแล้ว!";
} else if (!empty($_REQUEST['createpost_error'])) {
    $text = "สร้างโพสต์ไม่สำเร็จ!";
} else if (!empty($_REQUEST['deletepost_success'])) { // popup ลบ   
    $text = "ลบโพสต์สำเร็จแล้ว!";
} else if (!empty($_REQUEST['deletepost_error'])) {   
    $text = "ลบโพสต์ไม่สำเร็จ!";
} else if (!empty($_REQUEST['deletepost_user_error'])) {   
    $text = "คุณไม่มีสิทธิในการลบโพสต์นี้!";
} else if (!empty($_REQUEST['deletepost_post_not_found'])) {   
    $text = "ไม่พบโพสต์ดังกล่าว!";
} else if (!empty($_REQUEST['noPer'])) {
    $text = "คุณไม่มีสิทธิในการใช้งานระบบนี้!";
}

if ($text != "") {
    echo "<script> Swal.fire('".$text."'); </script>";
}
?>
