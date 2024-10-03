<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เติมสต็อกวัตถุดิบ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>เติมสต็อกวัตถุดิบ</h1>

    <!-- ฟอร์มสำหรับการเติมสต็อก -->
    <form action="restock_action.php" method="POST">
        <label for="product">เลือกวัตถุดิบ:</label>
        <select name="product_id">
            <?php
            // ดึงรายการวัตถุดิบทั้งหมดจาก Products Table
            $sql = "SELECT id, name FROM Products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                }
            }
            ?>
        </select>
        <label for="quantity">จำนวนที่ต้องการเติม:</label>
        <input type="number" name="quantity" min="1" required>
        <button type="submit">เติมสต็อก</button>
    </form>

    <h2>รายการวัตถุดิบในสต็อก</h2>

    <!-- ตารางแสดงรายการวัตถุดิบและสต็อกปัจจุบัน -->
    <table>
        <tr>
            <th>ชื่อวัตถุดิบ</th>
            <th>ปริมาณที่เหลือ</th>
            <th>หน่วย</th>
        </tr>
        <?php
        // ดึงข้อมูลจาก Products Table เพื่อแสดงสต็อกวัตถุดิบปัจจุบัน
        $sql = "SELECT name, stock_quantity, unit FROM Products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // แสดงข้อมูลวัตถุดิบในตาราง
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["stock_quantity"] . "</td><td>" . $row["unit"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>ไม่มีข้อมูลวัตถุดิบในสต็อก</td></tr>";
        }
        ?>
    </table>

    <a href="index.php">กลับไปยังหน้าแสดงสต็อก</a>
</body>
</html>


