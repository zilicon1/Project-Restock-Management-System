<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับ ID ของวัตถุดิบที่ต้องการแก้ไข
$id = $_GET['id'];

// ดึงข้อมูลวัตถุดิบจากฐานข้อมูล
$sql = "SELECT * FROM Products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขวัตถุดิบ</title>
</head>
<body>
    <h1>แก้ไขวัตถุดิบ</h1>

    <!-- ฟอร์มแก้ไขวัตถุดิบ -->
    <form action="update_stock.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="name">ชื่อวัตถุดิบ:</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
        <label for="stock_quantity">ปริมาณ:</label>
        <input type="number" name="stock_quantity" value="<?php echo $row['stock_quantity']; ?>" required>
        <label for="unit">หน่วย:</label>
        <input type="text" name="unit" value="<?php echo $row['unit']; ?>" required>
        <button type="submit">แก้ไขข้อมูล</button>
    </form>

    <a href="manage_stock.php">กลับไปยังหน้าการจัดการสต็อก</a>
</body>
</html>

