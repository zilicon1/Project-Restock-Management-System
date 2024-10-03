<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากฟอร์มเติมสต็อก
$product_id = $_POST['product_id'];
$quantity_added = $_POST['quantity'];

// อัปเดตสต็อกในฐานข้อมูล
$sql = "UPDATE Products SET stock_quantity = stock_quantity + ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $quantity_added, $product_id);

if ($stmt->execute()) {
    echo "เติมสต็อกสำเร็จ!";
} else {
    echo "เกิดข้อผิดพลาดในการเติมสต็อก: " . $conn->error;
}
?>

<a href="manage_stock.php">กลับไปยังหน้าการจัดการสต็อก</a>
