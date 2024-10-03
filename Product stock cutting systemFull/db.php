<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "restaurant_menu";

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($host, $user, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>
