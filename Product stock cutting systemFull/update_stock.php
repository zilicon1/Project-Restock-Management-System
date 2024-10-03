<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากฟอร์ม
$id = $_POST['id'];
$name = $_POST['name'];
$stock_quantity = $_POST['stock_quantity'];
$unit = $_POST['unit'];

// อัปเดตข้อมูลวัตถุดิบในฐานข้อมูล
$sql = "UPDATE Products SET name = ?, stock_quantity = ?, unit = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisi", $name, $stock_quantity, $unit, $id);

if ($stmt->execute()) {
    echo "อัปเดตข้อมูลสำเร็จ!";
} else {
    echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error;
}
?>

<a href="manage_stock.php">กลับไปยังหน้าการจัดการสต็อก</a>
