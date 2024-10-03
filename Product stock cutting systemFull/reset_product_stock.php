<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รีเซ็ตปริมาณวัตถุดิบทั้งหมดให้เป็น 0
$sql = "UPDATE Products SET stock_quantity = 0";

if ($conn->query($sql) === TRUE) {
    echo "รีเซ็ตสต็อกทั้งหมดสำเร็จ!";
} else {
    echo "เกิดข้อผิดพลาดในการรีเซ็ตสต็อก: " . $conn->error;
}
?>

<a href="manage_stock.php">กลับไปยังหน้าการจัดการสต็อก</a>
