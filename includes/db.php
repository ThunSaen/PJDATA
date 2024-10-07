<?php
$dbhost = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "wb_data";

$conn = new mysqli($dbhost, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>