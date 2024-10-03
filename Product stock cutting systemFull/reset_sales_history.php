<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการกดปุ่มยืนยันการรีเซ็ต
if (isset($_POST['confirm_reset'])) {
    // ลบข้อมูลทั้งหมดในตาราง SalesHistory
    $sql = "DELETE FROM SalesHistory";

    if ($conn->query($sql) === TRUE) {
        echo "รีเซ็ตประวัติการขายทั้งหมดสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาดในการรีเซ็ตประวัติการขาย: " . $conn->error;
    }
}
?>

<a href="sales_history.php">กลับไปหน้าประวัติการขาย</a>
