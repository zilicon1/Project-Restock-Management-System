<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือนวัตถุดิบที่ต้องซื้อ</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            height: 100vh;
            background-image: url('image02.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }
        .content {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 700px;
            z-index: 2;
            position: relative;
        }
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }
        .ok {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="background-overlay"></div>

<div class="content">
    <h1>การแจ้งเตือนวัตถุดิบที่ต้องซื้อ</h1>

    <!-- ตารางแสดงวัตถุดิบที่ต้องซื้อ -->
    <table>
        <tr>
            <th>ชื่อวัตถุดิบ</th>
            <th>จำนวนคงเหลือ</th>
            <th>จำนวนขั้นต่ำ</th>
            <th>หน่วย</th>
            <th>สถานะ</th>
        </tr>

        <?php
        // ดึงข้อมูลวัตถุดิบที่มีอยู่จาก Products
        $sql = "SELECT name, stock_quantity, minimum_stock, unit FROM Products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = $row['name'];
                $stock_quantity = $row['stock_quantity'];
                $minimum_stock = $row['minimum_stock'];
                $unit = $row['unit'];

                // ตรวจสอบว่าสต็อกต่ำกว่าค่าขั้นต่ำหรือไม่
                if ($stock_quantity < $minimum_stock) {
                    echo "<tr class='alert'>";
                    echo "<td>$name</td>";
                    echo "<td>$stock_quantity</td>";
                    echo "<td>$minimum_stock</td>";
                    echo "<td>$unit</td>";
                    echo "<td><span style='color:red;'>ต้องซื้อเพิ่ม</span></td>";
                    echo "</tr>";
                } else {
                    echo "<tr class='ok'>";
                    echo "<td>$name</td>";
                    echo "<td>$stock_quantity</td>";
                    echo "<td>$minimum_stock</td>";
                    echo "<td>$unit</td>";
                    echo "<td><span style='color:green;'>เพียงพอ</span></td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='5'>ไม่มีข้อมูลวัตถุดิบ</td></tr>";
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        $conn->close();
        ?>
    </table>

    <a href="index.php">กลับไปหน้า Index</a>
</div>

</body>
</html>
