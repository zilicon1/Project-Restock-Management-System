<?php
include 'db.php';

// รับข้อมูลจากฟอร์มเติมสต็อก
$product_id = $_POST['product_id'];
$quantity_added = $_POST['quantity'];

// อัปเดตสต็อก
$sql = "UPDATE Products SET stock_quantity = stock_quantity + ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $quantity_added, $product_id);

if ($stmt->execute()) {
    echo "เติมสต็อกสำเร็จ!";
} else {
    echo "เกิดข้อผิดพลาดในการเติมสต็อก";
}

?>

<a href="index.php">กลับไปยังหน้าแสดงสต็อก</a>
