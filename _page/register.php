<?php
include('../includes/header.php');
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg border-0" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Register</h2>
            <form action="../actions/register.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
                <p>Already have an account? <a href="../_page/login.php">Sign in</a></p>
            </div>
        </div>
    </div>

<?php
include('../includes/footer.php');
$text = "";
if (!empty($_REQUEST['error_empty'])) {
    $text = "กรุณากรอกข้อมูลให้ครบถ้วน!";
} else if (!empty($_REQUEST['error_e'])) {
    $text = "อีเมลนี้ถูกใช้งานแล้ว!";
} else if (!empty($_REQUEST['e'])) {
    $text = "เกิดข้อผิดพลาด! โปรดลองอีกครั้ง";
}

if ($text != "") {
    echo "<script> Swal.fire('".$text."'); </script>";
}
?>
