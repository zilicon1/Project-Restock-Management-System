<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับ ID ของวัตถุดิบที่ต้องการลบ
$id = $_GET['id'];

// ลบวัตถุดิบออกจากฐานข้อมูล
$sql = "DELETE FROM Products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "ลบวัตถุดิบสำเร็จ!";
} else {
    echo "เกิดข้อผิดพลาดในการลบวัตถุดิบ: " . $conn->error;
}
?>

<a href="manage_stock.php">กลับไปยังหน้าการจัดการสต็อก</a>
