<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการขายและวัตถุดิบที่ใช้</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ensure body takes full viewport height */
            margin: 0; /* Remove default margin */
            font-family: Arial, sans-serif;
            background-image: url('image02.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3); /* Semi-transparent background */
            z-index: 0;
        }

        /* Back button styles */
        .back-button {
            position: absolute; /* Absolute positioning */
            top: 10px; /* Distance from the top */
            left: 10px; /* Distance from the left */
            z-index: 1000; /* Ensure it appears above other elements */
            
        }

        .content {
            background-color: rgba(255, 255, 255, 0.9); /* More transparent background */
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            width: 90%;
            max-width: 900px;
            z-index: 2;
            text-align: center;
        }

        h1, h2 {
            margin: 20px 0;
        }

        table {
            margin: 20px auto; /* Center the table */
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-size: 14px;
        }

        td {
            word-wrap: break-word; /* Allow content to wrap */
            white-space: normal; /* Let content wrap to the next line */
        }

        .ingredients-cell {
            width: 45%;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="background-overlay"></div>

    <!-- Back button at top-left -->
    <a href="index.php">
        <button type="button" class="back-button">กลับไปหน้า Index</button>
    </a>

    <div class="content">
        <h1>ประวัติการขายและวัตถุดิบที่ใช้</h1>

        <!-- Table for displaying sales history -->
        <table>
            <tr>
                <th style="width: 15%;">วันที่และเวลา</th>
                <th style="width: 10%;">โต๊ะ</th> <!-- เพิ่มคอลัมน์โต๊ะ -->
                <th style="width: 15%;">เมนูที่ขาย</th>
                <th style="width: 10%;">จำนวนที่ขาย</th>
                <th style="width: 15%;">ยอดรวม (บาท)</th> <!-- เพิ่มยอดรวม -->
                <th class="ingredients-cell">วัตถุดิบที่ใช้ (รวมหน่วย)</th>
            </tr>

            <?php
            // Query for sales history and ingredients used
            $sql = "
                SELECT 
                    sale_date, 
                    table_number,  /* ดึงข้อมูลโต๊ะ */
                    menu_name, 
                    quantity_sold, 
                    (quantity_sold * price) AS total_price,  /* คำนวณยอดรวมต่อเมนู */
                    GROUP_CONCAT(CONCAT(product_name, ' (', quantity_used, ' ', unit, ')') SEPARATOR ', ') AS ingredients 
                FROM SalesHistory 
                JOIN Menus ON SalesHistory.menu_name = Menus.name  /* เชื่อมข้อมูลราคา */
                GROUP BY sale_date, table_number, menu_name, quantity_sold, price 
                ORDER BY sale_date DESC";

            $result = $conn->query($sql);

            // Display sales history if available
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['sale_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['table_number']) . "</td>";  // แสดงเลขโต๊ะ
                    echo "<td>" . htmlspecialchars($row['menu_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity_sold']) . "</td>";
                    echo "<td>" . number_format($row['total_price'], 2) . " บาท</td>";  // แสดงยอดรวม
                    echo "<td>" . htmlspecialchars($row['ingredients']) . "</td>";
                    echo "</tr>";
                }
            } else {
                // If no sales history available
                echo "<tr><td colspan='6'>ไม่มีข้อมูลประวัติการขาย</td></tr>";
            }

            // ปิดการเชื่อมต่อฐานข้อมูล
            $conn->close();
            ?>
        </table>

        <!-- Reset sales history form -->
        <form action="reset_sales_history.php" method="POST" onsubmit="return confirm('คุณต้องการรีเซ็ตประวัติการขายทั้งหมดหรือไม่?');">
            <input type="hidden" name="confirm_reset" value="1">
            <button type="submit">รีเซ็ตประวัติการขายทั้งหมด</button>
        </form>
    </div>
</body>
</html>
