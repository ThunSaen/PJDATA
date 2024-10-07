<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E Yung wa board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!--    <style>
        body {
            background-image: url('../images/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style> -->
</head>
<body>

<?php
session_start();

//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';

if (!isset($_SESSION['email'])) {
?>
<header class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="../index.php" class="nav-link px-2 text-secondary">หน้าหลัก</a></li>
            </ul>

            <div class="text-end">
                <a href="../_page/login.php" class="btn btn-outline-light me-2">ล็อคอิน</a>
                <a href="../_page/register.php" class="btn btn-warning">สมัครสมาชิก</a>
            </div>
        </div>
    </div>
</header>
<?php
} else {
?>
<header class="p-3 mb-3 border-bottom text-bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="../index.php" class="nav-link px-2 text-secondary">หน้าหลัก</a></li>
        </ul>

        <div class="dropdown text-end">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../images/profile.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small">
            <li><a class="dropdown-item" href="../_page/create_post.php">สร้างโพสต์ใหม่</a></li>
            <li><a class="dropdown-item" href="../_page/profile.php">โปรไฟล์</a></li>
          
            <?php
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            ?>
              <li><a class="dropdown-item" href="../_page/admin_page.php">หลังบ้าน</a></li>
            <?php
            }
            ?>

            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../actions/logout.php">ออกจากระบบ</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
<?php
}
?>