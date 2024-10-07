<?php
include('../includes/header.php');
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg border-0" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Login</h2>
            <form action="../actions/login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
<!--                <a href="#">Forgot password?</a> -->
                <p>If you don't have an account? <a href="../_page/register.php">Sign up</a></p>
            </div>
        </div>
    </div>

<?php
    include('../includes/footer.php');
    if (isset($_REQUEST['error_user'])) {
        echo "<script>
            Swal.fire({
                title: 'ไม่พบผู้ใช้!',
                text: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง!',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        </script>";
    }
    
    if (isset($_REQUEST['s'])) {
        echo "<script> Swal.fire('ลงทะเบียนสำเร็จแล้ว'); </script>";
    }
?>
