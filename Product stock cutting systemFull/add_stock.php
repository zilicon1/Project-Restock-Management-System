<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากฟอร์ม
$name = $_POST['name'];
$stock_quantity = $_POST['stock_quantity'];
$unit = $_POST['unit'];

// เพิ่มข้อมูลวัตถุดิบใหม่ในฐานข้อมูล
$sql = "INSERT INTO Products (name, stock_quantity, unit) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $name, $stock_quantity, $unit);

if ($stmt->execute()) {
    echo "เพิ่มวัตถุดิบใหม่สำเร็จ!";
} else {
    echo "เกิดข้อผิดพลาดในการเพิ่มวัตถุดิบ: " . $conn->error;
}
?>

<a href="manage_stock.php">กลับไปยังหน้าการจัดการสต็อก</a>
